<?php
class Database
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "english";

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

    function validate($value)
    {
        $value = str_replace(['‘', '’', '“', '”', '"', '„', '‟', '‹', '›', '«', '»', '`', '´', '❛', '❜', '❝', '❞', '〝', '〞'], "'", $value);
        $value = trim($value);
        $value = stripslashes($value);
        return $value;
    }


    public function executeQuery($sql, $params = [], $types = "")
    {
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Error: " . $stmt->error);
        }

        return $stmt;
    }

    public function select($table, $columns = "*", $condition = "", $params = [], $types = "")
    {
        $sql = "SELECT $columns FROM $table $condition";
        $stmt = $this->executeQuery($sql, $params, $types);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
        $types = str_repeat('s', count($data));
        $this->executeQuery($sql, array_values($data), $types);
        return $this->conn->insert_id;
    }

    public function update($table, $data, $condition = "", $params = [], $types = "")
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = ?, ";
        }
        $set = rtrim($set, ', ');

        if ($condition) {
            $condition = "WHERE " . $condition;
        } else {
            $condition = "";
        }

        $sql = "UPDATE $table SET $set $condition";
        $types = str_repeat('s', count($data)) . $types;
        $this->executeQuery($sql, array_merge(array_values($data), $params), $types);
    }

    public function delete($table, $condition = "", $params = [], $types = "")
    {
        if ($condition) {
            $condition = "WHERE " . $condition;
        } else {
            $condition = "";
        }

        $sql = "DELETE FROM $table $condition";
        $this->executeQuery($sql, $params, $types);
    }

    function hashPassword($password)
    {
        $key = "iqbolshoh-ilhomjonov";
        return hash_hmac('sha256', $password, 'iqbolshoh-ilhomjonov');
    }

    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    public function usernameExists($username)
    {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    public function registerUser($fullname, $email, $username, $password)
    {
        $sql = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $username, $password);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    public function getUserIdByUsername($username)
    {
        $result = $this->select('users', 'id', 'WHERE username = ?', [$username], 's');
        return $result[0]['id'];
    }

    public function find($table, $id)
    {
        $id = $this->validate($id);
        $condition = "WHERE id = ?";
        $params = [$id];
        return $this->select($table, "*", $condition, $params, 'i');
    }

    public function search($table, $columns = "*", $condition = "", $params = [], $types = "")
    {
        return $this->select($table, $columns, $condition, $params, $types);
    }

    public function fetchAll($table, $columns = "*")
    {
        $sql = "SELECT $columns FROM $table";
        $stmt = $this->executeQuery($sql);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    public function removeLike($userId, $wordId)
    {
        $stmt = $this->conn->prepare("DELETE FROM liked_words WHERE user_id = ? AND word_id = ?");
        $stmt->execute([$userId, $wordId]);
    }

    public function addLike($userId, $wordId)
    {
        $stmt = $this->conn->prepare("INSERT INTO liked_words (user_id, word_id) VALUES (?, ?)");
        $stmt->execute([$userId, $wordId]);
    }

    public function checkLiked($userId, $wordId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM liked_words WHERE user_id = ? AND word_id = ?");
        $stmt->bind_param("ii", $userId, $wordId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function count($table, $condition = '', $params = [], $types = '')
    {
        $sql = "SELECT COUNT(*) AS count FROM $table";
        if (!empty($condition)) {
            $sql .= " $condition";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $this->bindParams($stmt, $params, $types);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['count'];
    }

    private function bindParams($stmt, $params, $types)
    {
        $typeArray = str_split($types);
        foreach ($params as $key => $param) {
            $stmt->bind_param($typeArray[$key], $param);
        }
    }
    public function addText($userId, $textTitle, $textContent, $translation)
    {
        $textTitle = $this->validate($textTitle);
        $textContent = $this->validate($textContent);
        $translation = $this->validate($translation);

        $sql = "INSERT INTO texts (user_id, title, content, translation) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("SQL prepare error: " . $this->conn->error);
        }

        $stmt->bind_param("isss", $userId, $textTitle, $textContent, $translation);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            $stmt->close();
            return $insertedId;
        } else {
            die("SQL execute error: " . $stmt->error);
        }
    }
}
