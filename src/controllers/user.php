<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Entity;
use Blog\Models\UserModel;

class User extends Entity
{

    protected $id;
    protected $username;
    protected $email;

    public function __construct($data)
    {
        if ($data["password"]) $data["password"] = $this->crypt($data["password"]); //has
        $this->model = new UserModel($data);
        $this->hydrate($data);
    }

    public function create()
    {
        try {
            $this->model->addUser();
            return true;
        } catch (\Throwable $th) {

            return false;
        }
    }

    public function exists()
    {
        try {
            $data = $this->model->exists();
            die(var_dump($data));
            $this->hydrate($data);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
        
    }

    private function crypt($toCrypt){
        // $toCrypt="aaa";
        return password_hash($toCrypt, PASSWORD_DEFAULT );
    }
   
}
