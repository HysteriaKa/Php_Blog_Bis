<?php

namespace Blog\Models;

use Blog\Models\Entity;



class User extends Entity
{

    protected $id;
    protected $username;
    protected $email;

    public function __construct($data)
    {
        parent::__construct();
        $this->hydrate($data);
    }

    public function create()
    {
        try { 
            $this->addUser($this->crypt());
            return true;
        } catch (\Throwable $th) {

            return false;
        }
    }

    public function login()
    {
        global $currentSession;
        try {
            $data = $this->getDataFromEmail();
            if (!password_verify($this->password, $data->password)) throw ("mot de passe invalide");
            unset($data->password);
            unset($this->password);
            $this->hydrate($data);
            $currentSession->set("role", $data->user_type);
            $currentSession->set("user", $data->username);
            $currentSession->set("idUser", $data->id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    private function crypt()
    {
        $pwd = $this->password;
        unset($this->password);
        
        return password_hash($pwd, PASSWORD_DEFAULT);
    }
    public function logout()
    {
        global $currentSession;
        $currentSession->clear();
        $currentSession->addNotification("success", "Vous êtes déconnectés");
    }
    /**
     * [addUser description]
     *
     * @param   String  $username  [$username description]
     * @param   String  $email     [$email description]
     * @param   String  $password  crypted password
     *
     * @return  Boolean             [return description]
     */
    public function addUser($cryptedPwd)
    {
        
        try {
            
            $req = $this->db->prepare("INSERT INTO `users` (`email`, `password`, `username`, `user_type`) VALUES (:email, :pwd, :username, '0');");
            $req->execute([
                ":email" => $this->email,
                ":pwd" => $cryptedPwd,
                ":username" => $this->username
            ]);
           
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
   /**
     * permet d'obtenir les infos user à partir du mail
     *
     * @param   String  $email
     *
     * @return   Object User
     */
    public function getDataFromEmail()
    {
        try {
            $req = $this->db->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
            $req->execute([
                ":email" => $this->email
            ]);
            return $req->fetch();
        } catch (\Exception $e) {
            return false;
        }
    }
}
