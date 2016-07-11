<?php
namespace task2;

use task2\Utils;
use task2\DB;

class User
{
    static private $instance = null;
    private $storage;
    private $id;
    private $db;

    private function  __construct($id)
    {
        $this->db = DB::getInstance();
        $this->storage = Utils::unserialize($this->db->getById($id)['storage']);
        $this->id = $id;
    }

    /** @param int $id @return User */
    public static function getInstance($id) {
        if ( is_null(self::$instance) ) {
            self::$instance = new User($id);
        }
        return self::$instance;
    }

    /** @param string $field @return mixed */
    public function get($field = '')
    {
        if ($field == '') {
            return $this->storage;
        }
        $field = trim($field,'/');
        if (strpos($field, '/') === false) {
            return $this->storage[$field];
        } else {
            $fields = explode('/', $field);
            $acc = $this->storage;
            array_map(function ($field) use (&$acc){
                $acc = $acc[$field];
            }, $fields);
            return $acc;
        }

    }

    /** @param string $field @param mixed $value @return void */
    public function set($field, $value)
    {

        $field = trim($field,'/');
        if (strpos($field, '/') === false) {
            $this->storage = array_replace_recursive($this->storage, [$field => $value]);
        } else {
            $fields = explode('/', $field);
            $acc = $value;
            $fields = array_reverse($fields);
            array_map(function ($field) use (&$acc){
                $acc = [$field => $acc];
            }, $fields);
            $this->storage = array_replace_recursive($this->storage, $acc);
        }

        $res = Utils::serialize($this->storage, $this->db);
        $this->db->updateStorage($this->id, $res);
        $this->storage = Utils::unserialize($this->db->getById($this->id)['storage']);
    }
}
