<?php
// config.php
// session_start();

class Query
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "english";

        // $username = "milliyto_shop";
        // $password = "X?t&e#iF3Fc*";
        // $dbname = "milliyto_english";
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

    // validate(): HTML kod va SQL injectiondan himoya qiladi
    function validate($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    // executeQuery(): SQL so'rovini bajaradi
    public function executeQuery($sql, $params = [], $types = "")
    {
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Xatolik: " . $stmt->error);
        }

        return $stmt;
    }

    // select(): Ma'lumotlarni tanlash
    public function select($table, $columns = "*", $condition = "", $params = [], $types = "")
    {
        $sql = "SELECT $columns FROM $table $condition";
        $stmt = $this->executeQuery($sql, $params, $types);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // insert(): Ma'lumot qo'shish
    public function insert($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
        $types = str_repeat('s', count($data)); // Assume all values are strings
        $this->executeQuery($sql, array_values($data), $types);
        return $this->conn->insert_id;
    }

    // update(): Ma'lumotni yangilash
    public function update($table, $data, $condition = "", $params = [], $types = "")
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = ?, ";
        }
        $set = rtrim($set, ', '); // Remove trailing comma and space

        if ($condition) {
            $condition = "WHERE " . $condition;
        } else {
            $condition = ""; // Ensure condition is empty if not provided
        }

        $sql = "UPDATE $table SET $set $condition";
        $types = str_repeat('s', count($data)) . $types; // Append types for condition
        $this->executeQuery($sql, array_merge(array_values($data), $params), $types);
    }

    // delete(): Ma'lumotni o'chirish

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

    // hashPassword(): Parolni sha256 yordamida xesh qilish
    function hashPassword($password)
    {
        $key = "AccountPassword";
        return hash_hmac('sha256', $password, $key);
    }

    // authenticate(): Foydalanuvchini autentifikatsiya qilish
    public function authenticate($username, $password)
    {
        $username = $this->validate($username);
        $hashedPassword = $this->hashPassword($password);
        $condition = "WHERE username = ? AND password = ?";
        $params = [$username, $hashedPassword];
        return $this->select('users', "*", $condition, $params, 'ss');
    }

    // find(): Biror yozuvni topish
    public function find($table, $id)
    {
        $id = $this->validate($id);
        $condition = "WHERE id = ?";
        $params = [$id];
        return $this->select($table, "*", $condition, $params, 'i');
    }

    // search(): Ma'lum bir shart asosida qidirish
    public function search($table, $columns = "*", $condition = "", $params = [], $types = "")
    {
        return $this->select($table, $columns, $condition, $params, $types);
    }

    // fetchAll(): Barcha yozuvlarni olish
    public function fetchAll($table, $columns = "*")
    {
        $sql = "SELECT $columns FROM $table";
        $stmt = $this->executeQuery($sql);
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // getLastInsertId(): Oxirgi qo'shilgan yozuv ID sini olish
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
        $stmt->bind_param("ii", $userId, $wordId); // Bind parameters as integers
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); // Fetch the result as an associative array
        return $row['count'] > 0; // Check the count
    }
}
