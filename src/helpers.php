<?php

if (!function_exists('app_path')) {
    function app_path(string $path = ''): string
    {
        $pathDir = __DIR__;
        if ($path) {
            $pathDir .= "/$path";
        }
        return $pathDir;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        $pathDir = __DIR__ . '/public';
        if ($path) {
            $pathDir .= "/$path";
        }
        return $pathDir;
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        $pathDir = __DIR__ . '/storage';
        if ($path) {
            $pathDir .= "/$path";
        }
        return $pathDir;
    }
}
