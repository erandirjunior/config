<?php

namespace Config\Init;

/**
 * Classe base para as rotas
 */
abstract class Route
{
    /**
     * Atributo que receberá todas as rota chamadas pelo método add()
     * @var array
     */
    private static $routes;

    /**
     * Adiciona rotas ao atributo $routes
     * 
     * @param array $route rotas a serem adicicionadas
     */
    public static function add(array $route)
    {
        self::$routes[] = $route;
    }

    /**
     * Retorna todos os valores do atributo $routes
     * 
     * @return array rotas que estão armazenadas
     */
    public static function getRoutes() : array
    {
        return self::$routes;
    }

}