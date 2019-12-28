<?php
if (!isset($config)) {
    exit;
}

$db = new PDO("mysql:host=" . $config['database']['ip'] . ";dbname=" . $config['database']['db_name'], $config['database']['user'], $config['database']['password']);
if (isset($_GET['install'])) {
    $install = $db->prepare('CREATE TABLE IF NOT EXISTS ' . $config['database']['universal_table'] . ' (
chat_id bigint(0),
name varchar(200) CHARACTER SET utf8,
username varchar(50),
lang varchar(10),
type varchar(10),
PRIMARY KEY (chat_id))');
    $install->execute();
    $install = $db->prepare('CREATE TABLE IF NOT EXISTS ' . $config['database']['bot_table'] . ' (
chat_id bigint(0),
state varchar(50),
PRIMARY KEY (chat_id))');
    $install->execute();
}