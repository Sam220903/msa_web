<?php

namespace Repository;

class Connection
{
    private $host;
    private $user;
    private $password;
    private $database;

    public function __construct()
    {
        $this->host = '151.106.100.27';
        $this->user = 'proydweb_p2024';
        $this->password = 'bd_p2@24';
        $this->database = 'proydweb_p2024';
    }

    public function connect()
    {
        $conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
        $conn->set_charset('utf8');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
;