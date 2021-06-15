<?php

namespace Blog\Ctrl;
// session_start();
class SessionManager
{

    public array $ack = [];  //tableau contenant des tableaux associatifs contenant type et message : utile pour les notifications
    private String $user; // nom d'utilisateur
    private  $expiration; //stock la date d'expiration de la session

    public function __construct()
    {
        $started = session_start();   
         $_SESSION =[];
        if ($started) {
            foreach ($_SESSION as $key => $value) {
                $this->$key = $value;
            }
            // die(var_dump($value));
        }
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
        $this->update();
        // die(var_dump($this->update()));
     
    }

    public function getNotifications()
    {
        $response = $this->ack;
        $this->ack = [];
        $this->update();
        return $response;
        // die(var_dump($response));
    }

    private function update()
    {
        foreach ($this as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}
