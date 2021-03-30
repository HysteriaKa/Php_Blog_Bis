<?php

namespace Blog\Ctrl;

class SafeData{

    public $post;
    public $uri;



    public function __construct(Array $rules) {
        $this->securizeURI();
        if (isset($rules["post"] )) $this->post = filter_input_array ( INPUT_POST , $rules["post"]);

    }

    private function securizeURI(){
        $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $this->uri = substr($this->uri, 1);
        $this->uri = explode("/", $this->uri);
    }
}