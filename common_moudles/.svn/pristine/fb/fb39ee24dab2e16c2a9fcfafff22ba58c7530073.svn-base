<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];
echo $method . PHP_EOL;
$path = $_SERVER['PATH_INFO'];
$post = $_POST;
$content = file_get_contents('php://input');
$input = json_decode($content, true);
$name = $_GET["name"];
echo 'Hello ' . $name . '!' . PHP_EOL;
echo $content . PHP_EOL;
echo print_r($input, true) . PHP_EOL;