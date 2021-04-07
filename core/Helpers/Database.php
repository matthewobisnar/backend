<?php
namespace Core\Helpers;

class Database
{
    public static $COLUMNS = [];

    public function processQuery ($query, array $args = array())
    {
        $output = [];

        try {
            $pdo = new \PDO (
                'mysql:host=localhost;port=3306;dbname=chat_db',
                'chat_admin',
                'root',
                array (
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))"
                )
            );

            $pdo_statement = $pdo->prepare($query);

            foreach ($args as $index => $arg) {
                if (is_int($arg)) {
                    $type = \PDO::PARAM_INT;
                    $arg = filter_var(trim(urlencode($arg)), FILTER_SANITIZE_NUMBER_INT);
                } elseif (is_bool($arg)) {
                    $type = \PDO::PARAM_BOOL;
                    $arg = filter_var(trim(urlencode($arg)), FILTER_SANITIZE_NUMBER_INT);
                } elseif (is_null($arg)) {
                    $type = \PDO::PARAM_NULL;
                    $arg = NULL;
                } else {
                    $type = \PDO::PARAM_STR;
                    // $arg = filter_var(trim(urldecode($arg)), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
                }
    
                try {
                    $pdo_statement->bindValue($index + 1, $arg, $type);
                } catch (\Exception $e) {
                    $pdo = NULL;
                   new \Exception($e->getMessage());
                }
            }

            try {
                $pdo_statement->execute();
    
                if ($pdo_statement->rowCount()) {
                    $output = (preg_match('/\b(update|insert|delete)\b/', strtolower($query)) === 1) ? ["status" => true, "last_inserted_id" => !is_null($pdo) ? $pdo->lastInsertId() : null] : $pdo_statement->fetchAll(\PDO::FETCH_ASSOC);
                }
            } catch (\PDOException $e) {
                $pdo = NULL;
                return new \PDOException($e->getMessage());
            }
    
            $pdo = NULL;
            flush();
            return empty($output) ? [] : $output;
            
        } catch (\PDOException $e) {
            $pdo = NULL;
            return new \PDOException($e->getMessage());
        }
    }

}