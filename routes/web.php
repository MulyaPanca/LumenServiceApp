<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () {
    return response()->json([
        'service_name' => 'PHP Service App',
        'status'       => 'Running',
    ]);
});

$router->get('/hello-lumen/{name}', function($name) {
    return "<h1>Lumen</h1><p>Hi ". $name .", thanks for using Lumen</p>";
});

$router->get('/scores', ['middleware' => 'login', function() {
    return "<h1>Selamat</h1><p>Nilai Anda 100</p>";
}]);

$router->get('/users', 'UsersController@index');
$router->get('/users/{userId}', 'UsersController@show');

// Posts
$router->get('posts', 'PostsController@index');
$router->get('posts-stats', 'PostsController@stats');
$router->get('posts/{id}', 'PostsController@show');
$router->post('posts', 'PostsController@store');
$router->put('posts/{id}', 'PostsController@update');
$router->delete('posts/{id}', 'PostsController@destroy'); 


// Categories
$router->get('categories', 'CategoriesController@index');
$router->get('categories/{id}', 'CategoriesController@show');
$router->post('categories', 'CategoriesController@store');
$router->put('categories/{id}', 'CategoriesController@update');
$router->delete('categories/{id}', 'CategoriesController@destroy');

// Students
$router->get('/students', 'StudentsController@index');
$router->get('/students/{id}', 'StudentsController@show');
$router->post('/students', 'StudentsController@store');
$router->put('/students/{id}', 'StudentsController@update');
$router->delete('/students/{id}', 'StudentsController@destroy');