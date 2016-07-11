<?php
namespace task1;

require_once "../vendor/autoload.php";

use PDO;

$dbname = getenv('dbname');
$dsn = getenv('driver') . ":dbname=" . $dbname . ";host=" . getenv('host');
$dbusername = getenv('username');
$dbpass = getenv('pass');

$pdo = new PDO($dsn, $dbusername, $dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS first_task (id INT PRIMARY KEY AUTO_INCREMENT,
        parent INT NOT NULL, lft INT NOT NULL, rgt INT NOT NULL) ENGINE = InnoDB;");
    echo "DB prepared";
} catch (Exception $e) {
    echo $e->getMessage();
}
