<?php

namespace api;

class UsersController extends APIEngine
{
    private $id = '';
    private $list = '';
    public function __construct ($method,$entity, $id = '',$list = '',$query='')
    {
        parent::__construct($method,$entity,$query);

        if(!empty($id)) $this->id = $id;
        if(!empty($list)) $this->list = $list;

    }

    public function actionsUsers() {
        switch($this->method) {
            case 'GET':
                if(!is_null($this->list)) {
                    return $this->getListActionsUserById();
                }
                if(!is_null($this->id)) {
                    return $this->getCurrentUser();
                }
                break;
        }
    }

    public function getCurrentUser() {
       return  $this->request(self::BASE_URL . "$this->entity/$this->id");

    }
    public function getListActionsUserById() {
        return $this->request(self::BASE_URL . "$this->entity/$this->id/$this->list");
    }
}