<?php
namespace task1;

use PDO;

class DbTreeActions
{
    private $pdo;

    public function __construct()
    {
        $dbname = getenv('dbname');
        $dsn = getenv('driver') . ":dbname=" . $dbname . ";host=" . getenv('host');
        $dbusername = getenv('username');
        $dbpass = getenv('pass');

        $this->pdo = new PDO($dsn, $dbusername, $dbpass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** @param int $id @return array */
    public function getChildrenNodes($id)
    {
        try {
            $this->pdo->beginTransaction();
            $stp = $this->pdo->prepare('select * from first_task where id =:id');
            $stp->bindParam(':id', $id, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while get childre nodes", 1);

            $row = $stp->fetch(PDO::FETCH_ASSOC);
            $parentLeft = $row['lft'];
            $parentRight = $row['rgt'];

            $stp = $this->pdo->prepare('select * from first_task where lft >= :parentLeft and lft <= :parentRight');
            $stp->bindParam(':parentLeft', $parentLeft, PDO::PARAM_INT);
            $stp->bindParam(':parentRight', $parentRight, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while get childre nodes", 1);
            $this->pdo->commit();
            return $stp->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [];
        }


    }

    /** @param int $parentId @return void */
    public function insertNode($parentId)
    {
        try {
            $this->pdo->beginTransaction();
            $stp = $this->pdo->prepare('select * from first_task where id =:id');
            $stp->bindParam(':id', $parentId, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while inserting node", 1);

            $row = $stp->fetch(PDO::FETCH_ASSOC);
            $parentLeft = $row['lft'];

            $stp = $this->pdo->prepare('update first_task set rgt = rgt + 2 where rgt > :parentLeft');
            $stp->bindParam(':parentLeft', $parentLeft, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while inserting node", 1);

            $stp = $this->pdo->prepare('update first_task set lft = lft + 2 where lft > :parentLeft');
            $stp->bindParam(':parentLeft', $parentLeft, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while inserting node", 1);

            $stp = $this->pdo->prepare('insert into first_task (parent, lft, rgt) values (:parentId, :lft, :rgt)');
            $stp->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            $stp->bindParam(':lft', ++$parentLeft, PDO::PARAM_INT);
            $stp->bindParam(':rgt', ++$parentLeft, PDO::PARAM_INT);
            $stp->execute();
            if (!$stp) throw new Exception("Error while inserting node", 1);
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
        }


    }
}
