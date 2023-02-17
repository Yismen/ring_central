<?php

if (function_exists('str') == false) {
    function str(string $string)
    {
        return \Illuminate\Support\Str::of($string);
    }
}

if (function_exists('strToArray') == false) {
    function strToArray($string, $limit = -1)
    {
        return preg_split('/[,;|]+/', $string, $limit, PREG_SPLIT_NO_EMPTY);
    }
}
