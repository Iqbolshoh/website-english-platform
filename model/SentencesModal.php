<?php

require_once 'config.php';

class SentencesModel extends Database
{
    private $table = 'sentences';

    public function createSentence($user_id, $word_id, $sentence, $translation)
    {
        $data = [
            'user_id' => $user_id,
            'word_id' => $word_id,
            'sentence' => $sentence,
            'translation' => $translation
        ];
        return $this->insert($this->table, $data);
    }

    public function updateSentence($id, $data)
    {
        $condition = "id = $id";
        return $this->update($this->table, $data, $condition);
    }

    public function deleteSentence($id)
    {
        $condition = "id = $id";
        return $this->delete($this->table, $condition);
    }

    public function getSentenceById($id)
    {
        $condition = "id = $id";
        $result = $this->select($this->table, '*', $condition);
        return !empty($result) ? $result[0] : null;
    }

    public function getSentencesByWordId($word_id)
    {
        $condition = "word_id = $word_id";
        return $this->select($this->table, '*', $condition);
    }

    public function getSentencesByUserId($user_id)
    {
        $condition = "user_id = $user_id";
        return $this->select($this->table, '*', $condition);
    }
}
?>