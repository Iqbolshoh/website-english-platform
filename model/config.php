<?php

class Database
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "English";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function select($table, $columns = "*", $condition = "1")
    {
        $sql = "SELECT $columns FROM $table WHERE $condition";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

        $types = str_repeat('s', count($data));
        $values = array();
        foreach ($data as $key => $value) {
            $values[] = &$data[$key];
        }

        $stmt->bind_param($types, ...$values);

        return $stmt->execute() ? $this->conn->insert_id : die("Error: " . $stmt->error);
    }

    public function update($table, $data, $condition)
    {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $stmt = $this->conn->prepare("UPDATE $table SET " . implode(", ", $set) . " WHERE $condition");

        $types = str_repeat('s', count($data));
        $values = array();
        foreach ($data as $key => $value) {
            $values[] = &$data[$key];
        }

        $stmt->bind_param($types, ...$values);

        return $stmt->execute() ? $stmt->affected_rows : die("Error: " . $stmt->error);
    }

    public function delete($table, $condition)
    {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE $condition");

        return $stmt->execute() ? $stmt->affected_rows : die("Error: " . $stmt->error);
    }

    function validate($value)
    {
        $value = str_replace(['‘', '’', '“', '”', '"', '„', '‟', '‹', '›', '«', '»', '`', '´', '❛', '❜', '❝', '❞', '〝', '〞'], "'", $value);
        $value = trim($value);
        $value = stripslashes($value);
        return $value;
    }
}
