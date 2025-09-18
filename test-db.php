<?php
$conn = pg_connect("
    host=" . getenv('DB_HOST') . " 
    port=" . getenv('DB_PORT') . " 
    dbname=" . getenv('DB_NAME') . " 
    user=" . getenv('DB_USER') . " 
    password=" . getenv('DB_PASSWORD') . "
    sslmode=require
");
echo pg_last_error() ?: "Conexão OK!";