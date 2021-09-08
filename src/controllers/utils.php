<?php

namespace Controller;

class Utils {
    public static function titleToURI($title)
    {
        $title = explode(" ", $title);
        $title = implode("_", $title);
        return $title;
    }
    public static function uriToTitle($title)
    {
        $title = explode("_", $title);
        $title = implode(" ", $title);
        return $title;
    }
}
