<?php
namespace api;
class PostsController extends APIEngine
{
    private $id = '';

    public function __construct ($method,$entity, $id = '',$query='')
    {
        parent::__construct($method,$entity,$query);
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
            case 'PUT':
            case 'PATCH':
                if(!empty($this->id)) {
                    return $this->updatePost(file_get_contents("php://input"));
                }else {
                    http_response_code(404);
                    return json_encode(['error' => 'not found post']);
                }
            case 'DELETE':
                if(!empty($this->id)) {
                    return $this->deletePost(file_get_contents("php://input"));
                }else {
                    http_response_code(404);
                    return json_encode(['error' => 'not found post']);
                }
        }
    }
    private function getCurrentPost() {
        return $this->request(self::BASE_URL . "$this->entity/$this->id");
    }
    
    private function createPosts($post) {

        if(empty($post['body']) || empty($post['title']) || empty($post['userId'])) {
            http_response_code(400);
            return json_encode(['error' => 'empty required fields']);
        }
        $postString = '';
        foreach ($post as $key => $value) {
            $postString .= "$key=$value&";
        }
        return $this->request(self::BASE_URL . "$this->entity/$this->id", $postString);


    }
    private function updatePost($body) {

        $body = json_decode($body,true);

        $id = $body['id'];
        $bodyString = '';
        foreach ($body as $key => $value) {
            $bodyString .= "$key=$value&";
        }

        $prevMethod = $this->method;
        $this->method = 'GET';
        $response = $this->getCurrentPost();
        if($this->http_code === 404) {
            http_response_code(404);
            return json_encode(['error' => 'not fount post']);
        }
        $this->method = $prevMethod;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/text.txt', $id);
        if($id != 0)
            if((int)$id !== (int)$this->id) {
                http_response_code(400);
                return json_encode(['error' => 'id it\'s not correctly']);
            }
        return $this->request(self::BASE_URL . "$this->entity/$this->id", $bodyString);
    }
    private function deletePost() {
        return $this->request(self::BASE_URL . "$this->entity/$this->id");
    }
}
