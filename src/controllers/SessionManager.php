<?php

namespace Blog\Ctrl;

class SessionManager
{

    public  $ack;  //tableau contenant des tableaux associatifs contenant type et message : utile pour les notifications
    private  $user; // nom d'utilisateur
    private  $expiration; //stock la date d'expiration de la session
    private $role;
    private $idUser;

    public function __construct()
    {
        session_cache_expire(280);
        session_start();
        $this->initValue();
        foreach ($_SESSION as $key => $value) {
            $this->$key = $value;
        }
    }

    private function initValue()
    {
        $this->user = "";
        $this->ack = [];
        $this->role = 0; //utilisateur
        $this->idUser = "";
    }

    /**
     * [addNotification description]
     *
     * @param   ("error" | "success" | "warn")  $type     type de notificaation
     * @param   string                          $message  le texte de la notification
     *
     * @return  void                                      ajoute au tableau des notidfications
     */
    public function addNotification($type, $message)
    {
       
        array_push($this->ack, ["type" => $type, "message" => $message]);
        $this->update("ack");
    
    }

    public function getNotifications()
    {
        
        $response = $this->ack;
        $this->ack = [];
        $this->update("ack");
        return $response;
       
    }

    private function update($key)
    {
        $_SESSION[$key] = $this->$key;
    }

    public function set($key, $value)
    {
        $this->$key = $value;
        $this->update($key);
    }

    public function get($value)
    {

        return $this->$value;
        
    }

    public function init($username, $role, $id)
    {
        $this->name = $username;
        $this->role = $role;
        $this->idUser = $id;
        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));
        $this->expiration = $date;
        $this->update("name");
        $this->update("role");
        $this->update("expiration");
        $this->update("idUser");
    }

    /**
     * remove superglobales value
     *
     * @return  void    clear superglobal. After you need to create a new iteration of SessionManger
     */
    public function clear()
    {
        session_destroy();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
        $this->initValue();
    }
}
