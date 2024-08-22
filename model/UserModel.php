<?php

require_once 'config.php';

class UserModel extends Database
{
    private $table = 'users';

    public function createUser($fullname, $email, $username, $password, $profile_image = 'default.png')
    {
        $hashedPassword = hash_hmac('sha256', $password, 'iqbolshoh-ilhomjonov');
        $data = [
            'fullname' => $fullname,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword,
            'profile_image' => $profile_image
        ];
        return $this->insert($this->table, $data);
    }

    public function updateUser($id, $data)
    {
        $condition = "id = $id";
        return $this->update($this->table, $data, $condition);
    }

    public function deleteUser($id)
    {
        $condition = "id = $id";
        return $this->delete($this->table, $condition);
    }

    public function getUserById($id)
    {
        $condition = "id = $id";
        $result = $this->select($this->table, '*', $condition);
        return !empty($result) ? $result[0] : null;
    }

    public function getIdByUsername($username)
    {
        $condition = "username = '$username'";
        $result = $this->select($this->table, 'id', $condition);
        return !empty($result) ? $result[0]['id'] : null;
    }

    public function login($username, $password)
    {
        $condition = "username = '$username'";
        $result = $this->select($this->table, 'password', $condition);

        if (!empty($result) && hash_hmac('sha256', $password, 'iqbolshoh-ilhomjonov') === $result[0]['password']) {
            return $result[0];
        }

        return null;
    }

    public function getAllUsers()
    {
        return $this->select($this->table);
    }

    public function emailExists($email)
    {
        $result = $this->select($this->table, 'id', "email = '$email'");

        if ($result)
            return true;
        return false;
    }

    public function usernameExists($username)
    {
        $result = $this->select($this->table, 'id', "username = '$username'");

        if ($result)
            return true;
        return false;
    }

}

?>