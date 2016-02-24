<?php

class Database extends PDO
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
    const ERROR = 2;
    const MAX_ERROR_COUNT = 10;

    protected $transactionCounter = 0;

    public function __construct()
    {
        $data = require dirname(__DIR__) . '/params.php';
        $this->username = $data['database']['username'];
        $this->password = $data['database']['password'];
        $this->host = $data['database']['host'];
        $this->dbname = $data['database']['dbname'];
        $this->port = $data['database']['port'];
        $this->dns = "mysql:dbname={$this->dbname};host={$this->host};port={$this->port}";
        $this->connect();
        parent::__construct($this->dns, $this->username, $this->password);
    }

    public function beginTransaction()
    {
        if(!$this->transactionCounter++)
            return parent::beginTransaction();
        return $this->transactionCounter >= 0;
    }

    function commit()
    {
        if(!--$this->transactionCounter)
            return parent::commit();
        return $this->transactionCounter >= 0;
    }

    function rollback()
    {
        if($this->transactionCounter >= 0)
        {
            $this->transactionCounter = 0;
            return parent::rollback();
        }
        $this->transactionCounter = 0;
        return false;
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

    public function insertClient($address, $phoneNumber, $amount, $jsonData)
    {
        $id = $this->getMaxClientId();
        $st = $this->pdo->prepare("INSERT INTO Client (clientId, address, phoneNumber, status, amount, jsonData) values (:clientId, :address, :phoneNumber, :status, :amount, :jsonData)");
        $st->bindValue(':address', $address, PDO::PARAM_STR);
        $st->bindValue(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $st->bindValue(':clientId', $id, PDO::PARAM_INT);
        $st->bindValue(':status', self::STATUS_UNPAID, PDO::PARAM_INT);
        $st->bindValue(':amount', $amount, PDO::PARAM_STR);
        $st->bindValue(':jsonData', $jsonData, PDO::PARAM_STR);
        return [
            'state' => $st->execute(),
            'id' => $id
        ];
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

    public function setAsError($id)
    {
        $st = $this->pdo->prepare("UPDATE Client SET status = :status WHERE clientId = :clientId");
        $st->bindValue(':status', self::ERROR, PDO::PARAM_INT);
        $st->bindValue(':clientId', $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function updateErrorCount($clientId, $count)
    {
        $st = $this->pdo->prepare("UPDATE Client SET errorCount = :errorCount WHERE clientId = :clientId");
        $st->bindValue(':errorCount', $count, PDO::PARAM_INT);
        $st->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        return $st->execute();
    }

    public function getInactiveOrders()
    {
        $st = $this->pdo->prepare("SELECT * FROM Client WHERE status = 0");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * if return true send error sms
     * @param $clientId
     * @return bool
     */
    public function sendErrorSms($clientId)
    {
        $client = $this->findClientById($clientId);
        if ($client) {
            if ($client['errorCount'] < self::MAX_ERROR_COUNT) {
                $client['errorCount']++;
                $this->updateErrorCount($clientId, $client['errorCount']);
                return false;
            } else {
                return true;
            }
        }

        return false;
    }
}