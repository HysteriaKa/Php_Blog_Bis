<?php
namespace Controller;
class ErrorHandler
{
    public function __construct($err) {
        die(var_dump($err));
    }
}