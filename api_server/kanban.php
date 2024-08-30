<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    //------------------------- board ----------------------------
    $app->get('/board',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM boards';
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

    $app->get('/board_column',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM `boards` INNER JOIN columns ON boards.id = columns.board_id;';
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

    $app->post('/board/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `boards`(`user_id`, `board_name`) VALUES (?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $jsonData['user_id'], $jsonData['board_name']);
        $stmt->execute();

        $data = ["message" => "Board created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->put('/board/update/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "UPDATE `boards` SET `user_id` = ?, `board_name` = ? WHERE `id` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $jsonData['user_id'], $jsonData['board_name'], $id);
        $stmt->execute();

        $data = ["message" => "Board updated"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->delete('/board/delete/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $conn = $GLOBALS['connect'];
        $sql = "DELETE FROM `boards` WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $data = ["message" => "Board deleted"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    //------------------------- column ----------------------------
    $app->get('/column',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM columns';
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

    $app->post('/column/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `columns`(`user_id`, `column_name`, `board_id`) VALUES (?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $jsonData['user_id'], $jsonData['column_name'], $jsonData['board_id']);
        $stmt->execute();

        $data = ["message" => "Column created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->put('/column/update/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "UPDATE `columns` SET `user_id` = ?, `column_name` = ?, `board_id` = ? WHERE `id` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $jsonData['user_id'], $jsonData['column_name'], $jsonData['board_id'], $id);
        $stmt->execute();

        $data = ["message" => "Column updated"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->delete('/column/delete/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $conn = $GLOBALS['connect'];
        $sql = "DELETE FROM `columns` WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $data = ["message" => "Column deleted"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    //------------------------- task ----------------------------
    $app->get('/task',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM tasks';
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

    $app->post('/task/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `tasks`(`user_id`, `task_name`, `column_id`) VALUES (?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $jsonData['user_id'], $jsonData['task_name'], $jsonData['column_id']);
        $stmt->execute();

        $data = ["message" => "Task created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->put('/task/update/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "UPDATE `tasks` SET `user_id` = ?, `task_name` = ?, `column_id` = ? WHERE `id` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $jsonData['user_id'], $jsonData['task_name'], $jsonData['column_id'], $id);
        $stmt->execute();

        $data = ["message" => "Task updated"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    $app->delete('/task/delete/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $conn = $GLOBALS['connect'];
        $sql = "DELETE FROM `tasks` WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $data = ["message" => "Task deleted"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

     //------------------------- tag ----------------------------
    $app->get('/tag',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM tags';
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

    $app->post('/tag/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `tags`(`user_id`, `tag_name`, `task_id`) VALUES (?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $jsonData['user_id'], $jsonData['tag_name'], $jsonData['task_id']);
        $stmt->execute();

        $data = ["message" => "Tag created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    //------------------------- board group ----------------------------
    $app->get('/board_group',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM board_groups';
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

    $app->post('/board_group/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `board_groups`(`user_id`, `board_id`) VALUES (?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $jsonData['user_id'], $jsonData['board_id']);
        $stmt->execute();

        $data = ["message" => "Board_Group created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    //------------------------- task groups ----------------------------
    $app->get('/task_group',function(Request $request, Response $response){
        $conn = $GLOBALS['connect'];
        $sql = 'SELECT * FROM task_groups';
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

    $app->post('/task_group/insert', function (Request $request, Response $response, $args) {
        $json = $request->getBody();
        $jsonData = json_decode($json, true);
        $conn = $GLOBALS['connect'];
        $sql = "INSERT INTO `task_groups`(`user_id`, `task_id`) VALUES (?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $jsonData['user_id'], $jsonData['task_id']);
        $stmt->execute();

        $data = ["message" => "Task_Group created"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    });

    