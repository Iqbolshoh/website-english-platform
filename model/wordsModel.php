<?php

require_once 'config.php';

class WordsModel extends Database
{
    private $table = 'words';

    public function createWord($user_id, $word, $translation, $definition)
    {
        $data = [
            'user_id' => $user_id,
            'word' => $word,
            'translation' => $translation,
            'definition' => $definition
        ];
        return $this->insert($this->table, $data);
    }

    public function updateWord($id, $data)
    {
        $condition = "id = $id";
        return $this->update($this->table, $data, $condition);
    }
    public function selectWord($columns = "*", $condition = "1")
    {
        return $this->select($this->table, $columns, $condition);
    }

    public function deleteWord($id)
    {
        $condition = "id = $id";
        return $this->delete($this->table, $condition);
    }

    public function getWordById($id)
    {
        $condition = "id = $id";
        $result = $this->select($this->table, '*', $condition);
        return !empty($result) ? $result[0] : null;
    }

    public function getWordByName($word)
    {
        $condition = "word = $word";
        $result = $this->select($this->table, '*', $condition);
        return !empty($result) ? $result[0] : null;
    }
}
