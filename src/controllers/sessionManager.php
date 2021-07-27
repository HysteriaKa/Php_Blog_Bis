<?php

namespace Blog\Ctrl;
// session_start();
class SessionManager
{

    public array $ack = [];  //tableau contenant des tableaux associatifs contenant type et message : utile pour les notifications
    private  $user; // nom d'utilisateur
    private  $expiration; //stock la date d'expiration de la session
    private $role;
    private $idUser;

    public function __construct()
    {
        session_cache_expire(280);
        $expiration = session_cache_expire();
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
        $this->idUser ="";
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

    public function set($key, $value)
    {
        $this->$key = $value;
        $this->update($key);
    }

    public function get($value)
    {

        return $this->$value;
        // return $this->$value ? $this->$value : null;
    }

    public function init($username, $role, $id)
    {
        $this->name = $username;
        $this->role = $role;
        $this->idUser =$id;
        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));
        $this->expiration = $date;
        $this->update("name");
        $this->update("role");
        $this->update("expiration");
        $this->update("idUser");
    }

    /**
     * remove supergolbales value
     *
     * @return  void    clear superglobal. After you need to create a new iteration of SessionManger
     */
    public function clear()
    {
        session_destroy();
        foreach ($this as $key => $value) {
            unset($this->key);
        }
        $this->initValue();
    }
}
