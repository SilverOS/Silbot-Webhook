<?php

class botApi
{
    var $token;
    var $config;

    function __construct($token, $config)
    {
        $this->token = $token;
        $this->config = $config;
        return $this;
    }

    function sendRequest($method, $args = [])
    {
        $args = http_build_query($args);
        $request = curl_init('https://api.telegram.org/' . $this->token . '/' . $method);
        curl_setopt_array($request, array(
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'cURL request',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $args
        ));
        $result = curl_exec($request);
        curl_close($request);
        return $result;
    }

    function sendMessage($chat_id, $text, $keyboard = false, $keyboard_type = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = false, $disable_web_page_preview = false)
    {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
        if (!$disable_web_page_preview) $disable_web_page_preview = $this->config['disable_web_page_preview'];
        if ($keyboard) {
            if (!$keyboard_type) $keyboard_type = $this->config['keyboard_type'];
            if ($keyboard_type === 'inline') {
                $reply_markup = ['inline_keyboard' => $keyboard];
            } elseif ($keyboard_type === 'reply') {
                $reply_markup = ['keyboard' => $keyboard, 'resize_keyboard' => true];
            } elseif ($keyboard_type === 'hide') {
                $reply_markup = ['hide_keyboard' => true];
            }
        }
        $args = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
            'disable_web_page_preview' => $disable_web_page_preview,];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendMessage', $args);
    }
    function editMessageText ($chat_id,$message_id,$text,$keyboard=false,$keyboard_type=false,$parse_mode=false,$disable_web_page_preview=false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_web_page_preview) $disable_web_page_preview = $this->config['disable_web_page_preview'];
        if ($keyboard) {
            if (!$keyboard_type) $keyboard_type = $this->config['keyboard_type'];
            if ($keyboard_type === 'inline') {
                $reply_markup = ['inline_keyboard' => $keyboard];
            } elseif ($keyboard_type === 'reply') {
                $reply_markup = ['keyboard' => $keyboard, 'resize_keyboard' => true];
            } elseif ($keyboard_type === 'hide') {
                $reply_markup = ['hide_keyboard' => true];
            }
        }
        $args = [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => $disable_web_page_preview,];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('editMessageText', $args);

    }
    function answerCallbackQuery ($callback_query_id,$text=false,$show_alert = false,$url = false,$cache_time = false) {
        $args = [
            'callback_query_id' => $callback_query_id,
            'text' => $text,
            'show_alert' => $show_alert,
            'cache_time' => $cache_time
        ];
        if ($url) $args['url'] = $url;
        return $this->sendRequest('answerCallbackQuery',$args);
    }
    function getFile ($file_id) {
        $args = [
            'file_id' => $file_id,
        ];
        return $this->sendRequest('getFile',$args);
    }
}