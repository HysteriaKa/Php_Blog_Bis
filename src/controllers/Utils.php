<?php

namespace Blog\Ctrl;

class Utils {
    public function titleToURI($title)
    {
        $title = explode(" ", $title);
        $title = implode("_", $title);
        return $title;
    }
    public function uriToTitle($title)
    {
        $title = explode("_", $title);
        $title = implode(" ", $title);
        return $title;
    }
    /**
     * [end Gestion des messages et des redirections]
     *
     * @param  $props  données envoyées 
     *
     * @return  message header fin de session      
     */
    public function end($props){
        if (isset($props["message"])){
            global $currentSession;
            $currentSession->addNotification($props["messageType"], $props["message"] );
        }
        if (isset($props["header"])) header($props["header"]);
        if (isset($props["exit"])) exit(0);
    }
}
