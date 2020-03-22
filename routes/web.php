<?php

$router->get('/', ['as' => 'list','uses' => 'Controller@list']);
$router->post('/', ['as' => 'insert','uses' => 'Controller@insert']);

$router->get('/detail', ['as' => 'detail','uses' => 'Controller@detail']);
