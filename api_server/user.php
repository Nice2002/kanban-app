<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $app->post('/login', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $email = $jsonData['email'];
        $password = $jsonData['password'];

        $conn = $GLOBALS['connect'];
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {

            $query = 'SELECT id, email, name FROM users';
            $result = $conn->query($query);
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            $data = [
                "message" => "Login successful",
                "user" => $user,
                // "all_users" => $users
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }

        $data = ["message" => "Invalid credentials"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    });

    $app->get('/user',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM users';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        foreach($result as $row){
            array_push($data, $row);
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    });

    $app->post('/user/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `users`(`email`, `password`, `name`) VALUES (?,?,?)";
        $hashedPassword = password_hash($jsonData['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $jsonData['email'], $hashedPassword, $jsonData['name']);
        $stmt->execute();

        $data = ["message" => "User created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });