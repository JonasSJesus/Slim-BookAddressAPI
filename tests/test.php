<?php

use PDO;
use Agenda\Controllers\Contacts\ContactController;
use Agenda\Repositories\ContactRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dbPath = __DIR__ . '/../banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$cttRepo = new ContactRepository($pdo);
$cttCtrl = new ContactController($cttRepo);

var_dump ($cttCtrl->read());