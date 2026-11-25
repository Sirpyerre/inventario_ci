<?php

class LocalValetDriver extends \Valet\Drivers\ValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return is_file($sitePath.'/application/controllers/Welcome.php');
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        $publicPath = realpath($sitePath.'/public');
        $file = realpath($sitePath.$uri);

        if ($publicPath && $file && strpos($file, $publicPath.'/') === 0 && $this->isActualFile($file)) {
            return $file;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/index.php';
        $_SERVER['REQUEST_URI'] = '/'.ltrim($_SERVER['REQUEST_URI'], '/');

        if ($uri === '/ubicaciones' || $uri === '/ubicaciones/') {
            $_SERVER['REQUEST_URI'] = '/';
        }

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
