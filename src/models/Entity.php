<?php

namespace Blog\Models;
use Blog\Models\Database;

class Entity extends Database{
    public function hydrate($data){
        if (gettype($data) === "string") return;
        foreach ($data as $key => $value){
            $this->$key = $value;
           
        }
    }
}