<?php

namespace Texier\Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private string $file;
    private string $path;
    private string $title;
    private FilesystemLoader $loader;
    private Environment $twig;

    public function __construct(string $path, string $action, $controller = "")
    {
        if(strpos($path, '/', -1) === false && strpos($path, '\\', -1) === false) {
            $path .= '/';
        }

        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader);

        $path = "";

        if($controller != "") {
            $path .= $controller . "/";
        }
        $this->file = $path . $action . ".html.twig";
    }

    public function generate(array $data)
    {
        $this->twig->display($this->file, $data);
    }
}