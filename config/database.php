<?php
class Database {
    public static function connect() {
        $conn = new mysqli("localhost", "root", "", "classincm");
        if ($conn->connect_error) {
            die("Erro de conexão");
        }
        return $conn;
    }
}
