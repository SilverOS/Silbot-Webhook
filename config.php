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
    'ip_check' => [ // Allows requests only from botapi, you should do this with your server's firewall, but also this could be useful
        'active' => true,
        'whitelist' => [], //Whitelist of ips that can send requests to che code
    ],
    'database' => [
        'active' => true,
        'ip' => 'localhost',
        'user' => '',
        'password' => '',
        'db_name' => '',
        'universal_table' => 'users', // Where are stored Telegram User Information
        'bot_table' => '', // Where are stored Bot - Related User information
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
if ($config['ip_check']['active']) {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // Cloudflare compatibility
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    function ip_in_range( $ip, $range ) { // Function from https://gist.github.com/tott/7684443
        if ( strpos( $range, '/' ) == false ) {
            $range .= '/32';
        }
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }

    if (!in_array($_SERVER['REMOTE_ADDR'],$config['ip_check']['whitelist']) && !ip_in_range($_SERVER['REMOTE_ADDR'],'149.154.160.0/20')&& !ip_in_range($_SERVER['REMOTE_ADDR'],'149.154.160.0/20')) {
        exit;
    }
}

if ($config['database']['active']) {
    require 'functions/database.php';
}

require 'functions/botapi.php';
require 'functions/objects.php';