<?php

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

$pdo->exec(
	"CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    phone_number TEXT NOT NULL,
    email TEXT UNIQUE,
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
"
);

