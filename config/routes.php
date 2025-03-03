<?php

use Slim\App;
use Agenda\Controllers\ContactController;


return function(App $app) 
{
	// GET
	$app->get('/contacts', [ContactController::class, 'read']);
	$app->get('/contacts/{id}', [ContactController::class, 'readOne']);
	
	// POST
	$app->post('/contacts', [ContactController::class, 'create']);
	
	// PUT
	$app->put('/contacts/{id}', [ContactController::class, 'update']);
	
	// DELETE
	
};