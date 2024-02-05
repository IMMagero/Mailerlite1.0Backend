<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class UserModel extends Database
{
    public function getSubscriber($limit)
    {
        return $this->select("SELECT * FROM user ORDER BY id DESC LIMIT ?", ["i", $limit]);
    }

    public function createSubscriber($data)
    {
        
        return $this->insert($data);

    }

    public function findSubscriber($email)
    {
        return $this->findSubscriberbyEmail($email);

    }
}