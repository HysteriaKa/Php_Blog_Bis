<?php

namespace Blog\Ctrl;

class Page{

    public $template;
    public $data = [];
    public $status = 200;
    public $current_page;

    public function __construct(SafeData $safeData){
        $action = $safeData->uri[0];
        if ($action === "") $action = "home";
        if ( ! method_exists ( $this , $action )){
            $this->template ="page404";
            $this->status = 404;
            return;
        }
        $this->$action($safeData);
        die(var_dump($this->$action($safeData)));
    }

}