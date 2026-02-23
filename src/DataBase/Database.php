<?php
namespace App\DataBase;

class Database
{
    protected $mysqli;
    function __construct(
        $host = 'localhost',
        $user = 'root',
        $password = null,
        $database = 'km_book_library' //db neve
    )
    {
        $this->mysqli = mysqli_connect(
            $host,
            $user,
            $password,
            $database
        );

        if (!$this->mysqli){
            die("Connection failed: ". mysqli_connect_error());
        }
        $this->mysqli->set_charset("utf8mb4");
    }

    function __destruct()
    {
        $this->mysqli->close();
    }
}