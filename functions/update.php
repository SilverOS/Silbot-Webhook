<?php

class update
{
    var $update;
    var $config;
    var $token;
    function __construct($update,$token,$config) {
        $this->update = json_decode($update,true);
        $bot = new botApi($token,$config);
        if (isset($this->update['message'])) {
            $this->type = 'message';
            $this->message = new message($this->update['message']);
            if (isset($this->message->chat)) $this->chat = $this->message->chat;
            if (isset($this->message->user)) $this->user = $this->message->user;
        } elseif (isset($this->update['edited_message'])) {
            $this->type = 'edited_message';
            $this->message = new message($this->update['message']);
            if (isset($this->message->chat)) $this->chat = $this->message->chat;
            if (isset($this->message->user)) $this->user = $this->message->user;
        } elseif (isset($this->update['channel_post'])) {
            $this->type = 'channel_post';
            $this->message = new message($this->update['channel_post']);
            if (isset($this->message->chat)) $this->chat = $this->message->chat;
        } elseif (isset($this->update['edited_channel_post'])) {
            //todo
        } elseif (isset($this->update['inline_query'])) {
            //todo
        } elseif (isset($this->update['chosen_inline_result'])) {
            //todo
        } elseif (isset($this->update['callback_query'])) {
            $this->type = 'callback_query';
            $this->callback = new callback_query($this->update['callback_query']);
            if (isset($this->callback->user)) $this->user = $this->callback->user;
            if (isset($this->callback->message)) $this->message = $this->callback->message;
        }
        return $this;
    }
}