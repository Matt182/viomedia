<?php
namespace task2;

use mysqli;

class DB
{
    static private $instance = null;
    private $mysqli;

    private function  __construct()
    {
        $dbname = getenv('dbname');
        $host = getenv('host');
        $dbusername = getenv('username');
        $dbpass = getenv('pass');

        $this->mysqli = new mysqli($host, $dbusername, $dbpass, $dbname);
    }

    /** @param void @return DB */
    public static function getInstance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    /** @param string $string @return string */
    public function sanitize($string)
    {
        return $this->mysqli->escape_string($string);
    }

    /** @param int $id @param string $storage @return void */
    public function updateStorage($id, $storage)
    {
        $this->mysqli->query("update second_task set storage='$storage' where id='$id'");
    }

    /** @param int $id @return array */
    public function getById($id)
    {
        $res = $this->mysqli->query("select storage from second_task where id = $id");
        $row = $res->fetch_assoc();
        return $row;
    }
}
