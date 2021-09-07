<?php
namespace Blog\Ctrl;
class ErrorHandler
{
    public function __construct($err) {
        die(var_dump($err));
    }
}