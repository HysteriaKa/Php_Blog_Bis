<?php


namespace Blog\Models;

use Blog\Models\DataBase;

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
        
        try {
            unset($this->props->password);
            $req = $this->db->prepare("INSERT INTO `users` (`email`, `password`, `username`, `user_type`) VALUES (:email, :pwd, :username, '0');");
            $req->execute([
                ":email" => $this->props->email,
                ":pwd" => $cryptedPwd,
                ":username" => $this->props->username
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
                ":email" => $this->props->email
            ]);
            unset($this->props->password);
            return $req->fetch();
        } catch (\Exception $e) {
            return false;
        }
    }
}
