<?php

namespace api;

class APIEngine
{
    const BASE_URL = "https://jsonplaceholder.typicode.com/";
    protected $entities = null;
    protected $response = null;
    protected $http_code = null;
    protected $method = null;
    protected $entity = null;
    protected $query = null;

    public function __construct ($method,$entity,$query)
    {
        $this->method = $method;
        $this->entity = $entity;
        $this->query = '?' . $query;
    }

    function getCollection() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::BASE_URL . "$this->entity/" . $this->query);
        $this->entities = curl_exec($ch);
        $this->http_code= curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
    function request($url,$body = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $this->query);
        switch ($this->method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->response = curl_exec($ch);
        $this->http_code= curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($this->http_code === 404) {
            http_response_code(404);
            return json_encode(['error' => 'not found post']);
        }
        return $this->response;
    }
}