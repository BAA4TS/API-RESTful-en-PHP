<?php

class db
{
    public $db;

    public function __construct()
    {
        try {
            // Datos principales
            $dsn = "mysql:host=localhost;dbname=testapi";

            // Crear el PDO
            $this->db = new PDO($dsn, "root", "");

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Asignar null si falla la conexión
            $this->db = null;
        }
    }
}
