<?php

namespace Blog\Ctrl;
use Blog\Ctrl\Page;

class Front extends Page{


    // public function __construct(Array $uri){
    // }

    protected function home(){
        $this->template ="home";
        $this->data = []; //données du modele
    }

    protected function bidule(){
        $this->template ="contact";
        $this->data = []; //données du modele

    }
}