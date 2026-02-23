<?php

namespace App\DataBase;

use App\DataBase\BaseRepository;

class CategoriesRepository extends BaseRepository
{
    function __construct(
        $host = 'localhost',
        $user = 'root',
        $password = null,
        $database = 'km_book_library'
    )
    {
        parent::__construct($host, $user, $password, $database);
        $this->tableName = 'categories';
    }

}