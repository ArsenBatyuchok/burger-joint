<?php

class Database
{
    private $username;
    private $password;
    private $host;
    private $dns;
    private $dbname;
    private $port;
    private $pdo;

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;

    public function __construct()
    {
        $data = require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/scripts/params.php';
        $this->username = $data['database']['username'];
        $this->password = $data['database']['password'];
        $this->host = $data['database']['host'];
        $this->dbname = $data['database']['dbname'];
        $this->port = $data['database']['port'];
        $this->dns = "mysql:dbname={$this->dbname};host={$this->host};port={$this->port}";
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->pdo = new PDO($this->dns, $this->username, $this->password);
        } catch (PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
    }

    public function findClientById($id)
    {
        $st = $this->pdo->prepare("SELECT * FROM Client WHERE clientId = :clientId and status = :status");
        $st->bindValue(':clientId', $id, PDO::PARAM_INT);
        $st->bindValue(':status', self::STATUS_UNPAID, PDO::PARAM_BOOL);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function insertClient($address, $phoneNumber, $amount)
    {
        $id = $this->getMaxClientId();
        $st = $this->pdo->prepare("INSERT INTO Client (clientId, address, phoneNumber, status, amount) values (:clientId, :address, :phoneNumber, :status, :amount)");
        $st->bindValue(':address', $address, PDO::PARAM_STR);
        $st->bindValue(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $st->bindValue(':clientId', $id, PDO::PARAM_INT);
        $st->bindValue(':status', self::STATUS_UNPAID, PDO::PARAM_INT);
        $st->bindValue(':amount', $amount, PDO::PARAM_STR);
        return ['state' => $st->execute(), 'id' => $id];
    }

    public function getMaxClientId()
    {
        $st = $this->pdo->prepare("SELECT max(clientId) as clientId FROM Client");
        $st->execute();
        $orderId = $st->fetch();
        if ($orderId['clientId']) {
            return ($orderId['clientId'] + 1);
        }
        return 1;
    }

    public function setAsPaid($id)
    {
        $st = $this->pdo->prepare("UPDATE Client SET status = :status WHERE clientId = :clientId");
        $st->bindValue(':status', self::STATUS_PAID, PDO::PARAM_INT);
        $st->bindValue(':clientId', $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function insertServer($data, $signature)
    {
        $st = $this->pdo->prepare("INSERT INTO server (`data`, `signature`) values (:data, :signature)");
        $st->bindValue(':data', $data, PDO::PARAM_STR);
        $st->bindValue(':signature', $signature, PDO::PARAM_STR);
        return $st->execute();
    }

}