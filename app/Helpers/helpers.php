<?php

if (! function_exists('media_url')) {
    function media_url(string $path): string
    {
        return '/storage/'.ltrim($path, '/');
    }
}
