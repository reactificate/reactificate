<?php

if (!function_exists('root_path')) {
    function root_path(?string $path = null): string
    {
        return dirname(__DIR__, 2) . "/{$path}";
    }
}

if (!function_exists('resources_path')) {
    function resources_path(?string $path = null): string
    {
        return root_path("resources/{$path}");
    }
}

if (!function_exists('view_path')) {
    function view_path(?string $path = null): string
    {
        return resources_path("views/{$path}");
    }
}

if (!function_exists('public_path')) {
    function public_path(?string $path = null): string
    {
        return root_path("public/{$path}");
    }
}