<?php
namespace api;
class PostsController extends APIEngine
{
    private $id = '';

    public function __construct ($method,$entity, $id = '',$query='')
    {
        parent::__construct($method,$entity,$query);
        if(empty($id) && $method === 'GET') {
            $this->getCollection();
        }
        if(!empty($id)) $this->id = $id;

    }

    public function actionsPosts() {
        switch($this->method) {
            case 'GET':
                if(!is_null($this->id)) {
                    return $this->getCurrentPost();
                }
                break;
            case 'POST':
                return $this->createPosts($_POST);
                break;
            case 'PUT':
            case 'PATCH':
                if(!empty($this->id)) {
                    return $this->updatePost(file_get_contents("php://input"));
                }else {
                    http_response_code(404);
                    return json_encode(['error' => 'not found post']);
                }
                break;
            case 'DELETE':
                if(!empty($this->id)) {
                    return $this->deletePost(file_get_contents("php://input"));
                }else {
                    http_response_code(404);
                    return json_encode(['error' => 'not found post']);
                }
                break;
        }
    }
    private function getCurrentPost() {
        return $this->request(self::BASE_URL . "$this->entity/$this->id");
    }
    
    private function createPosts($post) {

        if(!isset($post['body']) || !isset($post['title']) || !isset($post['userId'])) {
            http_response_code(400);
            return json_encode(['error' => 'empty required fields']);
        }
        return $this->request(self::BASE_URL . "$this->entity/$this->id", $post);


    }
    private function updatePost($body) {
        if($this->method !== 'PATCH') {
            if(!strpos($body, 'body')
                || !strpos($body, 'title')
                || !strpos($body, 'userId')
                || !strpos($body, 'id')
            ) {
                http_response_code(400);
                return json_encode(['error' => 'empty required fields']);
            }
        }

        $id = preg_replace('/(.*)id=(.*)/', '$2', $body);

        $this->method = 'GET';
        $response = $this->getCurrentPost();
        if($this->http_code === 404) {
            http_response_code(404);
            return json_encode(['error' => 'not fount post']);
        }
        $this->method = 'PUT';
        if((int)$id !== 0)
            if((int)$id !== (int)$this->id) {
                http_response_code(400);
                return json_encode(['error' => 'id it\'s not correctly']);
            }
        return $this->request(self::BASE_URL . "$this->entity/$this->id", $body);
    }
    private function deletePost() {
        return $this->request(self::BASE_URL . "$this->entity/$this->id");
    }
}
