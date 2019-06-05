<?php


$config = [
    'parse_mode' => 'HTML',
    'keyboard_type' => 'inline', // Can be 'inline' or 'reply' or 'hide'
    'disable_notification' => false,
    'disable_web_page_preview' => false,
];

require 'functions/botapi.php';
require 'functions/objects.php';
require 'functions/update.php';