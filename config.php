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
    'connection_close' => false, //This option will close the connection with the bot API, not making it wait for the update
    'parse_mode' => 'HTML',
    'keyboard_type' => 'inline', // Can be 'inline' or 'reply' or 'hide'
    'disable_notification' => false,
    'object_response' => false, // If enabled it will automatically return a response object after botApi requests
    'disable_web_page_preview' => false,
];
if ($config['connection_close']) {
    ignore_user_abort(true);
    header('Connection: close');
    flush();
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
}
if ($config['token_whitelist']['enabled']) {
    if (!in_array($token,$config['token_whitelist']['tokens'])) {
        exit;
    }
}

if ($config['database']['active']) {
    require 'functions/database.php';
}

require 'functions/botapi.php';
require 'functions/objects.php';