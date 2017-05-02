<?php

namespace Config\Controller;

/**
 * Classe para envio de mesnagem de erros
 */
class Error
{
    /**
     * Atributo que receberá uma instância de objeto anôninma e mensagens de erro
     * @var [type]
     */
    public $message;

    /**
     * Atributo que receberá uma instância de classe anônima
     * 
     * @var object
     */
    function __construct()
    {
        $this->message = new \stdClass();
    }

    /**
     * Atribui a mensagem de erro ao atributo $message inclue o arquivo error exibindo a mensagem de erro.
     * 
     * @param  string $message mensagem de erro
     */
    public function errorMessage($message)
    {
        $this->message = $message;
        
        if (file_exists("../App/Views/error/error.php")) {
            require_once "../App/Views/error/error.php";
        }
    }
}