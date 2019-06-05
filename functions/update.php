<?php

class update
{
    var $update;
    var $config;
    var $token;
    function __construct($update,$token,$config) {
        $this->update = json_decode($update,true);
        $bot = new botApi($token,$config);
        //$bot->sendMessage(141691961,$update);
        if (isset($this->update['message'])) {
            $message = new message($this->update['message']);
            if (isset($message->chat)) $chat = $message->chat;
            if (isset($message->user)) $user = $message->user;
            include 'commands.php';
        } elseif (isset($this->update['edited_message'])) {
            //todo
        } elseif (isset($this->update['channel_post'])) {
            //todo
        } elseif (isset($this->update['edited_channel_post'])) {
            //todo
        } elseif (isset($this->update['inline_query'])) {
            //todo
        } elseif (isset($this->update['chosen_inline_result'])) {
            //todo
        } elseif (isset($this->update['callback_query'])) {
            $callback = new callback_query($this->update['callback_query']);
            if (isset($callback->user)) $user = $callback->user;
            if (isset($callback->message)) $message = $callback->message;
            include 'commands.php';
        }
    }
}