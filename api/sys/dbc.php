<?php
namespace sys;
use PDO;

class DBC
{
    public $dbc = null;

    public function __construct()
    {
        $opt  = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => FALSE,
        );
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR;
        $this->dbc = new PDO($dsn, DB_USER, DB_PASS, $opt);
    }

}