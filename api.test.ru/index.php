<?php
use api\PostsController;
use api\UsersController;
$request = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
$query = $_SERVER['QUERY_STRING'];
spl_autoload_register(function ($class) {
    require __DIR__ . "\\$class.php";
});
header("Content-Type: application/json; charset=UTF-8");
$method = $_SERVER['REQUEST_METHOD'];
$entity = $request[1];
$id = $request[2];
switch ($entity) {
    case 'posts':
        $posts = new PostsController($method,$entity, $id,$query);
        var_dump($posts->actionsPosts());
        break;
    case 'users':
        $list = $request[3];
        $users = new UsersController($method, $entity,$id,$list,$query);
        var_dump($users->actionsPosts());

        break;
    default: 
        http_response_code(404);
        var_dump(json_encode(['error' => 'not found api method']));
}

