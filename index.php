<?php

require_once 'controllers/ControllerCategory.php';
require_once 'controllers/ControllerUser.php';
require_once 'utilities/Route.php';

Route::add('/categories', function() { ControllerCategory::create(); }, 'post');
Route::add('/categories', function() { ControllerCategory::read(); }, 'get');
Route::add('/categories/([0-9]*)', function($id) { ControllerCategory::readById($id); }, 'get');
Route::add('/categories', function() { ControllerCategory::update(); }, 'put');
Route::add('/categories/([0-9]*)', function($id) { ControllerCategory::delete($id); }, 'delete');

Route::add('/users', function() { ControllerUser::create(); }, 'post');
Route::add('/users', function() { ControllerUser::read(); }, 'get');
Route::add('/users/([0-9]*)', function($id) { ControllerUser::readById($id); }, 'get');
Route::add('/users', function() { ControllerUser::update(); }, 'put');
Route::add('/users/([0-9]*)', function($id) { ControllerUser::delete($id); }, 'delete');
Route::add('/users/login', function() { ControllerUser::login(); }, 'post');

Route::run('/api');