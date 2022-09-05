<?php

namespace Texier\Framework;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class View
{
    private string $file;
    private string $path;
    private string $title;
    private FilesystemLoader $loader;
    private Environment $twig;

    /**
     * Constructeur de classe. Initialise et configure l'environnement Twig.
     * @param string $path
     * @param string $action
     * @param $controller
     */
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

    /**
     * Génère la vue
     * @param array $data
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generate(array $data)
    {
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->display($this->file, $data);
    }
}
