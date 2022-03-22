<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('books/', 'BooksController@getBooks');
$router->get('books/{id}', 'BooksController@getBook');
$router->post('books/{book}/comments', 'CommentsController@store');

$router->get('characters/', 'CharactersController@getCharacters');
$router->get('characters/{id}', 'CharactersController@getCharacter');

$router->get('houses/', 'HouseController@getHouses');
$router->get('houses/{id}', 'HouseController@getHouses');
