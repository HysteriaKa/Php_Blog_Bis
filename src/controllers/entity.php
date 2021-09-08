<?php

namespace Controller;

class Entity{
    public function hydrate($data){
        foreach ($data as $key => $value){
            $this->$key = $value;
           
        }
    }
}