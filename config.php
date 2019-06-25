<?php


$config = [
    'token_whitelist' => [ //Avoid receiving fake requests or not allowed cloned bots
        'tokens' => ['botTOKEN'],
        'enabled' => false
    ],
    'database' => [
        'active' => true,
        'ip' => 'localhost',
        'user' => '',
        'password' => '',
        'db_name' => '',
        'universal_table' => 'users', // Where are stored Telegram User Information
        'bot_table' => 'test', // Where are stored Bot - Related User information
    ],
    'parse_mode' => 'HTML',
    'keyboard_type' => 'inline', // Can be 'inline' or 'reply' or 'hide'
    'disable_notification' => false,
    'disable_web_page_preview' => false,
];
if ($config['token_whitelist']['enables']) {
    if (!in_array($token,$config['token_whitelist']['tokens'])) {
        exit;
    }
}
if ($config['database']['active']) {
    require 'functions/database.php';
}
require 'functions/botapi.php';
require 'functions/objects.php';
require 'functions/update.php';