<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ((strpos($uri, "failed-pm-transaction") !== false || strpos($uri, "success-pm-transaction") !== false) && strpos($uri, "user#") == false) {
    $link = $_SERVER['REQUEST_URI'];
    $uri = str_replace("user", "user#",$link);
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
    $url = $protocol.$_SERVER['HTTP_HOST'].$uri;
    echo '<script type="text/javascript">
           window.location = "'.$url.'"
      </script>';
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
