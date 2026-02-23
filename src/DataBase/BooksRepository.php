<?php

namespace App\DataBase;

use App\DataBase\BaseRepository;

class BooksRepository extends BaseRepository
{
    function __construct(
        $host = 'localhost',
        $user = 'root',
        $password = null,
        $database = 'km_book_library'
    )
    {
        parent::__construct($host, $user, $password, $database);
        $this->tableName = 'books';
    }

    public function getAll():array{
        $query = $this->select() . "ORDER BY title";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getBooksByAuthorID($author_id){
        $query = $this->select() . "WHERE author_id = $author_id ORDER BY title";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getBooksByPublisherID($publisher_id){
        $query = $this->select() . "WHERE publisher_id = $publisher_id ORDER BY title";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getBooksByCategoryID($category_id){
        $query = $this->select() . "WHERE category_id = $category_id ORDER BY title";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    /*public function getBooksByID($type, $){

    }*/

}
