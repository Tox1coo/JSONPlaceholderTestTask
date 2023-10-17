<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json");

use api\PostsController;
use api\UsersController;


$request = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
$query = $_SERVER['QUERY_STRING'];
spl_autoload_register(function ($class) {
    require __DIR__ . "\\$class.php";
});

$method = $_SERVER['REQUEST_METHOD'];
$entity = $request[1];
$id = $request[2];
switch ($entity) {
    case 'posts':
        $posts = new PostsController($method,$entity, $id,$query);
        if(!$id && $method === 'GET') {
            print_r($posts->getCollection());
        } else {
            print_r($posts->actionsPosts());
        }
        break;
    case 'users':
        $list = $request[3];
        $users = new UsersController($method, $entity,$id,$list,$query);
        if(!$id && $method === 'GET') {
            $users->getCollection();

        } else {
            print_r($users->actionsUsers());
        }
        break;
    default:
        http_response_code(404);
        return json_encode(['error' => 'not found api method']);
}
