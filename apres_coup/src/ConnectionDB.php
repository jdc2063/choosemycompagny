<?php

class ConnectionDB
{
    private PDO $db;

    public function __construct(string $host, string $username, string $password, string $databaseName)
    {
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }


    public function getPDO(): PDO
    {
        return $this->db;
    }

}
