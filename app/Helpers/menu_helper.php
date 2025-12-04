<?php

function isActive(string $path): string
{
    $uri = service('request')->getUri(); // CORRETO

    $current = trim($uri->getPath(), '/');
    $path    = trim($path, '/');

    return $current === $path ? 'active' : '';
}