<?php

namespace Config\Model;

use Config\Model\Conn;

use Config\Controller\Error;

abstract class Table
{
    /**
     * Atributo que receberá a conexão com o banco
     * @var object
     */
    private static $db;

    /**
     * recebe o nome da tabela
     * @var string
     */
    protected static $table;

    /**
     * Adiciona ao parâmetro $db uma conexao da classe Conn
     */
    public static function instance()
    {
        self::$db = Conn::getConection();
    }

    /**
     * Retorna todos os valores de determinada tabela no banco de dados
     * 
     * @return array valores que existem em determinada tabela
     */
    public static function all() : array
    {
        try {

            self::instance();

            $tableStatic = static::$table;

            $stmt        = self::$db->query("SELECT * FROM {$tableStatic}");

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            $error = new Error();
            $error->errorMessage("Error: não foi possível encontrar os dados. Verifique se os dados estão do banco estão corretos. ".$e->getMessage());

        }
    }

    /**
     * Retorna todos os valores de uma única linha de determinada tabela no banco de dados
     * 
     * @param  string $param coluna da tabela do banco de dados
     * @param  string $value valor de determinada campo na tabela
     * @return array        array com os valores da linha da tabela
     */
    public static function find($param, $value)
    {
        try {

            self::instance();

            $tableStatic = static::$table;

            $stmt        = self::$db->prepare("SELECT * FROM {$tableStatic} where {$param} = :campo");
            
            
            $stmt->bindValue(":campo", $value);

            $stmt->execute();

            $data        = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $data[0];

        } catch (PDOException $e) {

            $error = new Error();
            $error->errorMessage("Error: não foi possível encontrar o dado. Verifique se os dados estão corretos. ".$e->getMessage());

        }
    }

    /**
     * Insere valores no banco de dados
     * 
     * @param  array $column valores dos campos e valores a serem adicionado na tabela
     * @return bool         se os dados foram inseridos corretamente
     */
    public static function insert($column)
    {
        try {

            self::instance();

            $tableStatic = static::$table;

            foreach ($column as $key => $value) {
                $col[]    = "$key";
                $values[] = ":$key";
            }

            $columns = implode(', ', $col);
            
            $values  = implode(', ', $values);
            
            $stmt    = self::$db->prepare("insert into {$tableStatic} ({$columns}) values ({$values})");

            foreach ($column as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                return true;
            }

        } catch (PDOException $e) {

            $error = new Error();
            $error->errorMessage("Error: não foi possível inserir o dado. Verifique se os dados corretos. ".$e->getMessage());

        }
    }

    /**
     * Remove valores de uma tabela no banco de dados
     * 
     * @param  string $column coluna da tabela
     * @param  string $value  valor na tabela
     * @return boolean         se todos os valores foram deletados com sucesso
     */
    public static function delete($column, $value)
    {
        try {

            self::instance();

            $tableStatic = static::$table;
            
            $stmt        = self::$db->prepare("delete from {$tableStatic} where {$column} = :value");
            
            $stmt->bindValue(":value", $value);

            $stmt->execute();
            

            if ($stmt->rowCount() == 1) {
                return true;
            }

        } catch (PDOException $e) {

            $error = new Error();
            $error->errorMessage("Error: não foi possível deletar o dado. Verifique se os dados estão corretos. ".$e->getMessage());

        }
    }

    /**
     * Atualiza determinado valor na tabela
     * 
     * @param  string $set      coluna da tabela que deseja atualizar
     * @param  string $newValue dado que será inserido na tabela
     * @param  string $column   coluna da tabela onde o dado a ser substutído está.
     * @param  string $oldValue dado a ser substituído
     * @return boolean           retorna true se atualizou o dado
     */
    public static function update($set, $newValue, $column, $oldValue)
    {
        try {

            self::instance();

            $tableStatic = static::$table;
            
            $stmt        = self::$db->prepare("update {$tableStatic} set {$set} = ? where {$column} = {$oldValue}");

            $stmt->bindValue(1, $newValue);

            $stmt->execute();

            return true;

        } catch (PDOException $e) {

            $error = new Error();
            $error->errorMessage("Error: não foi possível atualizar o dado. Verifique se os dados estão corretos. ".$e->getMessage());
            
        }
    }
}
