<?php

namespace Blog\Ctrl;
use HTMLPurifier;
use HTMLPurifier_Config;

class SafeData{

    public $post;
    public $uri;
    public $method;
    private $purifier;



    public function __construct(Array $rules) {


        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);

        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING); 
        $this->securizeURI();
        if (isset($rules["post"] )) {
            $this->post = filter_input_array ( INPUT_POST , $rules["post"]);
            $this->post = $this->usePurifier($this->post, $rules["post"]);
        }

    }

    private function securizeURI(){
        $this->uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $this->uri = substr($this->uri, 1);
        $this->uri = explode("/", $this->uri);
    }

    private function usePurifier($sanitizedData, $rules){
        foreach ($rules as $key => $value) {
            if ($value === "FILTER_SANITIZE_STRING") $sanitizedData[$key] =  $this->purifier->purify($sanitizedData[$key]);
        }
        return $sanitizedData;
    }
   
}