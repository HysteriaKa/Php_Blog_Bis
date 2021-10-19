<?php

namespace Blog\Models;
use Blog\Models\Database;

class Entity extends Database{
    public function hydrate($data){
        foreach ($data as $key => $value){
            $this->$key = $value;
           
        }
    }
}