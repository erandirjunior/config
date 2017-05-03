<?php

namespace Config\Init;

use Config\Controller\Error;

class Bootstrap
{
    /**
     * Recebe as rotas
     * @var arra
     */
    private $routes;

    /**
     * Recebe a quantidade de erros de rota não encontrada.
     * @var integer
     */
    private $urlError = 0;

    /**
     * Adiciona as rotas ao atributo $routes, executa o método index() passando o método getUrl() como parametro.
     *
     * @param array $routes rotas
     *
     * @see index() verificador de rotas
     */
    public function __construct()
    {
        $this->routes[] = Route::getRoutes();
        $this->index($this->getUrl());
    }

    /**
     * Verifica a existência de rotas dinâmicas, caso exista, chama o método run(), caso não exista rotas dinâmica,
     * chama o método run()
     *
     * @param  string $url url da página
     *
     * @see run() método de criação de objeto e execução de método
     */
    public function index(string $url)
    {
        array_walk($this->routes[0], function($route) use ($url) {

            if (strpos($route[0], "@")) {

                //$rotaLimpa = substr($route[0], 0, strpos($route[0], "@"));
                $rotaTratada = str_replace("@", '', $route[0]);

                // obtendo quantos caracteres tem na rota
                $tamanhoDaRota = strlen($rotaTratada);

                // obtendo quantos caracteres tem na url
                $tamanhoDaUrl = strlen($url);

                // verifica se a quantidade de caracteres da url é maior que a quantidade de caracteres da rota
                if ($tamanhoDaUrl > $tamanhoDaRota) {

                    // cortando a url pelo tamanho da rota
                    $url = substr($url, 0, $tamanhoDaRota);

                    // compara se o vlaor da $url é igual ao valor da $rotaTratada
                    if (strcmp($url,$rotaTratada) === 0){

                        // substituindo o valor da rota pelo valor da url
                        $route[0] = str_replace($route[0], $url, $route[0]);

                        $this->run($url, $route);

                        exit();
                    }
                }

                $this->urlError++;

            } else {

                $this->run($url, $route);

            }

            $this->countError(count($this->routes[0]));

        });
    }

    /**
     * Faz a verificação se a rota é igual com a url atual, caso seja, cria um objeto e executa determinado método do mesmo,
     * que esteja em determinado índice da rota
     *
     * @param  string $url   url da página
     * @param  array  $route rotas
     */
    private function run(string $url, array $route)
    {
        if ($url == $route[0]) {

            $class = "App\\Controllers\\".ucfirst($route[1]);

            $instance = new $class;

            $action = $route[2];

            if (method_exists($instance, $action)) {

                $instance->$action();

            } else {

                $error = new Error();
                $error->errorMessage("Error: método não existe. Verifique no arquivo web.php ou na sua classe.");

            }

        } else {

            $this->urlError++;

        }
    }

    /**
     * Verifica se o valor do atributo urlError é igual ao número de caminhos que existe no array de rotas
     * caso seja igual, cria uma instância da classe Error, e manda a mensagem de error de caminho não existente
     *
     * @param  int $value caminhos que existe na rota
     */
    private function countError($value)
    {
        if ($value == $this->urlError) {

            $error = new Error();
            $error->errorMessage("Error: rota não existe. Verifique no arquivo web.php.");

        }
    }

    /**
     * Retorna o path da uri da página atual, exemplo: /, /exemplo, /exemplo/algumaCoisa
     *
     * @return string path da url
     */
    private function getUrl() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

}

