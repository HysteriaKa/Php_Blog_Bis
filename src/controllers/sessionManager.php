<?php

namespace Blog\Ctrl;
// session_start();
class SessionManager
{

    public array $ack = [];  //tableau contenant des tableaux associatifs contenant type et message : utile pour les notifications
    private String $user; // nom d'utilisateur
    private  $expiration; //stock la date d'expiration de la session
    private $role;

    public function __construct()
    {
        $started = session_start(); 
        //  $_SESSION =[];
        if ($started) {
            
            // var_dump($_SESSION);
            foreach ($_SESSION as $key => $value) {
                // var_dump("started", $key, $value);
                $this->$key = $value;
            }
            // die(var_dump($value));
        }
          
        // die(var_dump($this));
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
        // var_dump("addNotification", $type, $message);
        array_push($this->ack, ["type" => $type, "message" => $message]);
        $this->update("ack");
        // die(var_dump($this->update()));
     
    }

    public function getNotifications()
    {
        // var_dump("getNotifications");
        $response = $this->ack;
        $this->ack = [];
        $this->update("ack");
        return $response;
        // die(var_dump($response));
    }

    private function update($key)
    {
        $_SESSION[$key] = $this->$key;
        
        // var_dump("updated", $_SESSION);
    }

    public function init($username, $role){
        $this->name = $username;
        $this->role = $role;
        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));
        $this->expiration = $date;
        $this->update("name");
        $this->update("role");
        $this->update("expiration");
    }
}
