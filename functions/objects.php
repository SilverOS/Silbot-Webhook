<?php
class user
{
    var $array;
    function __construct($array) {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }
}

class chat
{
    var $array;
    function __construct($array) {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }
}
class message
{
    var $array;
    function __construct($array) {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
            } elseif ($key === 'chat') {
                $this->chat = new chat($value);
            } else {
                $this->$key = $value;
            }
        }
    }
}
class callback_query
{
    var $array;
    function __construct($array) {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
            } elseif ($key === 'message') {
                $this->message = new message($value);
            } else {
                $this->$key = $value;
            }
        }
    }
}