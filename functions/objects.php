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
            } elseif ($key === 'forward_from_chat'){
                $this->forward_from_chat = new chat($value);
                $this->fwchat = $this->forward_from_chat;
            } elseif ($key === 'forward_from'){
                $this->forward_from = new user($value);
                $this->fwuser = $this->forward_from;
            } elseif ($key === 'reply_to_message'){
                $this->reply_to_message = new message($value);
                $this->reply = $this->reply_to_message;
            } else {
                $this->$key = $value;
            }
        }
    }
    function getHtmlText ($text = false,$entities = false) {
        if (!$text) $text = $this->text;
        if (!$entities) $entities = $this->entities;
        if (!isset($text) || !isset($this->entities) || !isset($entities)) {
            return false;
        }
        $msg = htmlspecialchars($text);
        $added = 0;
        foreach($entities as $entity) {
            if ($entity['type'] == "bold") {
                $msg = substr_replace($msg, '<b>', $added + $entity['offset'], 0);
                $msg = substr_replace($msg, '</b>', $added + $entity['offset'] + 3 + $entity['length'], 0);
                $added += 7;
            }
            if ($entity['type'] == "italic") {
                $msg = substr_replace($msg, '<i>', $added + $entity['offset'], 0);
                $msg = substr_replace($msg, '</i>', $added + $entity['offset'] + 3 + $entity['length'], 0);
                $added += 7;
            }
            if ($entity['type'] == "code" || $entity['type'] == "pre") {
                $msg = substr_replace($msg, '<code>', $added + $entity['offset'], 0);
                $msg = substr_replace($msg, '</code>', $added + $entity['offset'] + 6 + $entity['length'], 0);
                $added += 13;
            }
            if ($entity['type'] == "text_link") {
                $ins = "<a href='$entity[url]'>";
                $msg = substr_replace($msg, $ins, $added + $entity['offset'], 0);
                $msg = substr_replace($msg, '</a>', $added + $entity['offset'] + strlen($ins) + $entity['length'], 0);
                $added += strlen($ins) + 4;
            }
        }
        return $msg;
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