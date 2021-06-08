<?php

namespace Blog\Ctrl;

class Entity{
    public function hydrate($data){
        foreach ($data as $key => $value){
            $this->$key = $value;
           
        }
    }
}