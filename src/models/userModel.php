<?php

namespace Blog\Models;

use Blog\Models\DataBase;
// use Blog\Ctrl\Utils;

class UserModel extends DataBase
{

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
        // var_dump($this->props);
        try {
            unset($this->props->password);
            $req = $this->db->prepare("INSERT INTO `users` (`email`, `password`, `username`, `user_type`) VALUES (:email, :pwd, :username, '0');");
            $req->execute([
                ":email" => $this->props->email,
                ":pwd" => $cryptedPwd,
                ":username" => $this->props->username
            ]);
            // $req->execute(array($email, $password, $username));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }



    // public function getArticle()
    // {

    //     $resultData = $this->db->prepare("SELECT * FROM `articles` WHERE `titre`=:titre ORDER BY `created_at` DESC");
    //     $resultData->execute(["titre" => Utils::uriToTitle($this->props->titre)]);
    //     // die(var_dump($resultData->debugDumpParams()));

    //     while ($data = $resultData->fetchObject()) {
    //         return $data;
    //     }
    // }

    public function getDataFromEmail()
    {
        try {
            $req = $this->db->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
            $req->execute([
                ":email" => $this->props->email
            ]);
            unset($this->props->password);
            return $req->fetch();
        } catch (\Exception $e) {
            return false;
        }
    }
}
