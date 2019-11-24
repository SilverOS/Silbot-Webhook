<?php


$config = [
    'token_whitelist' => [ //Avoid receiving fake requests or not allowed cloned bots
        'tokens' => ['botTOKEN'],
        'enabled' => false
    ],
    'plugins' => [
        'active' => true, // Choose if you want plugins loaded
        'start_disabled' => [''], // Array of files to not load in plugins/start folder
        'end_disabled' => [''], // Array of files to not load in plugins/end folder
    ],
    'database' => [
        'active' => true,
        'ip' => 'localhost',
        'user' => '',
        'password' => '',
        'db_name' => '',
        'universal_table' => 'users', // Where are stored Telegram User Information
        'bot_table' => 'silbot', // Where are stored Bot - Related User information
    ],
    'parse_mode' => 'HTML',
    'keyboard_type' => 'inline', // Can be 'inline' or 'reply' or 'hide'
    'disable_notification' => false,
    'object_response' => false,
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