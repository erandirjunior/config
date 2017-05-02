<?php

namespace Config\Model;

class Conn
{
    private static $instance;

    /**
     * Obtém os dados do arquivo db.ini da pasta App/db.ini
     * 
     * @return array valores que estão no arquivo
     */
    private static function findData()
    {
        if (file_exists("../App/db.ini")) {
            return parse_ini_file("../App/db.ini");
        }
    }

    /**
     * Cria a conexão com o banco de dados retorna a mesma
     * 
     * @return object PDO objeto de conexão pdo
     */
    public static function getConection() : \PDO
    {
        if (self::$instance === null) {
            $con = self::findData();
            self::$instance = new \PDO("mysql:host=".$con['HOST'].";dbname=".$con['DBNAME'], $con['USER'], $con['PASS']);
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
        }
        return self::$instance;
    }
}