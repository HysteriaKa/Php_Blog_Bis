<?php

namespace Controller;

use Controller\Entity;
use Models\UserModel;

class User extends Entity
{

    protected $id;
    protected $username;
    protected $email;

    public function __construct($data)
    {
        $this->model = new UserModel($data);
        $this->hydrate($data);
    }

    public function create()
    {
        try { //has
            $this->model->addUser($this->crypt());
            return true;
        } catch (\Throwable $th) {

            return false;
        }
    }

    public function login()
    {
        global $currentSession;
        try {
            $data = $this->model->getDataFromEmail();
            if (!password_verify($this->password, $data->password)) throw ("mot de passe invalide");
            unset($data->password);
            unset($this->password);
            $this->hydrate($data);
            $currentSession->set("role", $data->user_type);
            $currentSession->set("user", $data->username);
            $currentSession->set("idUser", $data->id);
            // die(var_dump($data));
            //TODO : ajouter la durée de la session
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    private function crypt()
    {
        $pwd = $this->password;
        unset($this->password);
        // $toCrypt="aaa";
        return password_hash($pwd, PASSWORD_DEFAULT);
    }
    public function logout()
    {
        global $currentSession;
        $currentSession->clear();
        $currentSession->addNotification("success", "Vous êtes déconnectés");
    }
}
