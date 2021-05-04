<?php

namespace Blog\Ctrl;

class SafeData{

    public $post;
    public $uri;
    public $method;



    public function __construct(Array $rules) {
        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING); 
        $this->securizeURI();
        if (isset($rules["post"] )) $this->post = filter_input_array ( INPUT_POST , $rules["post"]);

    }

    private function securizeURI(){
        $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $this->uri = substr($this->uri, 1);
        $this->uri = explode("/", $this->uri);
    }
    // private function securizeURI(){
    //     $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
    //     if ( mb_substr_count($this->uri,'/') === 1 ) {
    //         $this->uri = substr($this->uri, 1);
    //         $this->uri = explode("/", $this->uri);
    //     }else{
    //         switch ($this->uri) {
    //             case '/article/home':
    //                 $this->uri = substr($this->uri, 9);
    //                 $this->uri = explode("/", $this->uri);
    //                 break;
                
    //             default:
    //                 # code...
    //                 break;
    //         }
    //     }
    // }
}