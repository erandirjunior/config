<?php

namespace Config\Controller;

use Config\Controller\Error;

/**
 * Classe para o manuseamento de views e para obtenção de valores de formulários e da url.
 */
abstract class Controller
{
    /**
     * Atributo que receberá uma instância de classe anônima
     * 
     * @var object
     */
    protected $view;

    /**
     * Inicializa o atributo $view como uma instância de uma classe anônima
     */
    public function __construct()
    {
        $this->view = new \stdClass;
    }

    /**
     * Verifica se existe uma view na pasta App/Views, caso exista, faz um include dela,
     * manda uma mensagem de erro
     * 
     * @param  string $view nome do arquivo
     */
    public function template(string $view)
    {
        if (file_exists("../App/Views/".$view.".php")) {
            require_once "../App/Views/".$view.".php";
        } else {
            $error = new Error();
            $error->errorMessage("Error: view não encontrada. Verifique o nome do arquivo na pasta view ou na sua classe");
        }
    }

    /**
     * Retorna o valor de um campo de formulário enviado pelo método post
     * 
     * @param  string $post name do input
     * 
     * @return string       retorna um ou todos os valores dos campos de um formulário
     */
    public function input($post = null)
    {
        if (is_null($post)) {
            return $_POST;
        } else {
            return $_POST[$post];
        }
    }

    /**
     * Retorna a url como um array
     * 
     * @return array array da url
     */
    protected function get() : array
    {
        $barra = explode('/', $_SERVER["REQUEST_URI"]);

        array_shift($barra);

        return $barra;
    }

}