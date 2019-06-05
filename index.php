<?php
isset($_GET['token']) ? $token = 'bot'.$_GET['token'] : $token = false;

require 'config.php';
new update(file_get_contents('php://input'),$token,$config);
