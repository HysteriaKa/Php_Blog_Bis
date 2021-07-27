<?php

namespace Blog;

class Debug
{
    function vardump($enter){
        echo '<pre style="background-color: #000000; color:#ffff10; padding:1rem;">';
        var_dump($enter);
        echo '</pre>';
    }
}