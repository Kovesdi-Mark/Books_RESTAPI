<?php

namespace App\Html;

use App\DataBase\AuthorsRepository;
use App\DataBase\BooksRepository;
use App\DataBase\CategoriesRepository;
use App\DataBase\PublishersRepository;

class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    private static function getDatabaseConnection($resourceName){
        switch($resourceName){
            case 'books':
                return new BooksRepository();

            case 'authors':
                return new AuthorsRepository();

            case 'publishers':
                return new PublishersRepository();

            case 'categories':
                return new CategoriesRepository(); 
        }
    }

    private static function getRequest(){
        $uri = $_SERVER['REQUEST_URI'];
        $arrUri = explode('/', $uri);
        $resourceName = self::getResourceName($arrUri);
        $resourceId = self::getResourceId($arrUri);
        $childResourceName = self::getChildResourceName($arrUri);

        if ($childResourceName){
            $db = new BooksRepository();

            switch ($resourceName){
                case "authors":
                    $entities = $db -> getBooksByAuthorID($resourceId);
                    $code = 200;
                    if (empty($entities)){
                        $code = 404;
                    }
                    Response::response($entities, $code);
                    return;

                case "publishers":
                    $entities = $db -> getBooksByPublisherID($resourceId);
                    $code = 200;
                    if (empty($entities)){
                        $code = 404;
                    }
                    Response::response($entities, $code);
                    return;

                case "categories":
                    $entities = $db -> getBooksByCategoryID($resourceId);
                    $code = 200;
                    if (empty($entities)){
                        $code = 404;
                    }
                    Response::response($entities, $code);
                    return;
            }
            
        }

        if ($resourceId){
            $db = self::getDatabaseConnection($resourceName);
            $entities = $db->get($resourceId);
            $code = 200;
            if (empty($entites)){
                $code = 404;
            }
            Response::response($entities, $code);
            return;
        }

        if ($resourceName){
            $db = self::getDatabaseConnection($resourceName);
            $entities = $db->getAll();
            $code = 200;
            if(empty($entites)){
                $code = 404;
            }
            Response::response($entities, $code);
            return;
        }

    }

    private static function postRequest(){
        $uri = $_SERVER['REQUEST_URI'];
        $arrUri = explode('/', $uri);

        $resourceName = self::getResourceName($arrUri);

        if ($resourceName){
            $db = self::getDatabaseConnection($resourceName);
            $data = json_decode(file_get_contents("php://input"), true);

            switch ($resourceName){
                case "publishers":
                    $id = $db->create(['name' => $data['name']]);
                    break;

                case "categories":
                    $id = $db->create(['name' => $data['name']]);
            }
        }
    }

    private static function deleteRequest(){
        $uri = $_SERVER['REQUEST_URI'];
        $arrUri = explode('/', $uri);
        $request = $arrUri[1];
        $data = json_decode(file_get_contents("php://input"), true);

        $resourceName = self::getResourceName($arrUri);
        $resourceId = self::getResourceId($arrUri);

        //könyv törlés: books/book_id
        //kategória törlés: categories/category_id
        //kiadó törlés: publishers/publisher_id
        //író törlés: authors/author_id

        if($resourceId){
            $db = self::getDatabaseConnection($resourceName);
            $result = $db->delete($resourceId);
            $code = 204;
            if(!$result){
                $code = 404;
            }
            Response::response([], $code);
            return;
        }
    }

    private static function putRequest(){

    }

    private static function getResourceName($arrUri){
        if(isset($arrUri[1])) return $arrUri[1];
        return "";
    }

    private static function getResourceId($arrUri){
        if(isset($arrUri[2])) return $arrUri[2];
        return "";
    }

    private static function getChildResourceName($arrUri){
        if(isset($arrUri[3])) return $arrUri[3];
        return "";
    }
}