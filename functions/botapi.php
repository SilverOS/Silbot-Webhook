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

    function sendRequest($method, $args = [], $response_type = false)
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
        curl_setopt($request, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($request);
        curl_close($request);
        if (($this->config['object_response'] && !$response_type) || $response_type == "object") {
            return new response(json_decode($result, true));
        } else {
            return $result;
        }
    }

    function sendMessage($chat, $text, $keyboard = false, $keyboard_type = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0, $disable_web_page_preview = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
        if ($disable_web_page_preview === 0) $disable_web_page_preview = $this->config['disable_web_page_preview'];
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

    function editMessageText($chat, $message, $text, $keyboard = false, $keyboard_type = false, $parse_mode = false, $disable_web_page_preview = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_web_page_preview === 0) $disable_web_page_preview = $this->config['disable_web_page_preview'];
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

    function editMessageCaption($chat, $message, $caption, $keyboard = false, $keyboard_type = false, $parse_mode = false)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
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
            'caption' => $caption,
            'message_id' => $message_id,
            'parse_mode' => $parse_mode,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('editMessageCaption', $args);
    }

    function editMessageMedia($chat, $message, $media, $keyboard = false, $keyboard_type = false)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
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
            'media' => $media,
            'message_id' => $message_id,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('editMessageMedia', $args);
    }

    function editMessageReplyMarkup($chat, $message, $keyboard = false, $keyboard_type = false)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
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
            'message_id' => $message_id,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('editMessageReplyMarkup', $args);
    }

    function answerCallbackQuery($callback, $text = '', $show_alert = false, $url = false, $cache_time = false)
    {
        if (is_a($callback, 'callback')) {
            $callback_query_id = $callback->id;
        } else {
            $callback_query_id = $callback;
        }
        $args = [
            'callback_query_id' => $callback_query_id,
            'text' => $text,
            'show_alert' => $show_alert,
            'cache_time' => $cache_time
        ];
        if ($url) $args['url'] = $url;
        return $this->sendRequest('answerCallbackQuery', $args);
    }

    function forwardMessage($chat, $from, $message, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($from, 'chat') || is_a($from, 'user')) {
            $from_chat_id = $from->id;
        } else {
            $from_chat_id = $from;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
        $args = [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat_id,
            'message_id' => $message_id,
            'disable_notification' => $disable_notification,
        ];
        return $this->sendRequest('forwardMessage', $args);
    }

    function sendPhoto($chat, $photo, $caption = '', $keyboard = false, $keyboard_type = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'photo' => $photo,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendPhoto', $args);
    }

    function sendAudio($chat, $audio, $caption = '', $keyboard = false, $keyboard_type = false, $duration = false, $performer = false, $title = false, $thumb = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'audio' => $audio,
            'duration' => $duration,
            'performer' => $performer,
            'title' => $title,
            'thumb' => $thumb,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendAudio', $args);
    }

    function sendDocument($chat, $document, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'document' => $document,
            'thumb' => $thumb,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendDocument', $args);
    }

    function sendVideo($chat, $video, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $height = false, $width = false, $duration = false, $supports_streaming = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'video' => $video,
            'thumb' => $thumb,
            'height' => $height,
            'width' => $width,
            'duration' => $duration,
            'supports_streaming' => $supports_streaming,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendVideo', $args);
    }

    function sendAnimation($chat, $animation, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $height = false, $width = false, $duration = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'animation' => $animation,
            'thumb' => $thumb,
            'height' => $height,
            'width' => $width,
            'duration' => $duration,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendAnimation', $args);
    }

    function sendVoice($chat, $voice, $caption = '', $keyboard = false, $keyboard_type = false, $duration = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'voice' => $voice,
            'duration' => $duration,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendVoice', $args);
    }

    function sendVideoNote($chat, $video_note, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $length = false, $duration = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'caption' => $caption,
            'video_note' => $video_note,
            'thumb' => $thumb,
            'lenght' => $length,
            'duration' => $duration,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendVideoNote', $args);
    }

    function sendSticker($chat, $sticker, $keyboard = false, $keyboard_type = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'sticker' => $sticker,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendSticker', $args);
    }

    function sendLocation($chat, $longitude, $latitude, $keyboard = false, $live_period = false, $keyboard_type = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'longitude' => $longitude,
            'latitude' => $latitude,
            'live_period' => $live_period,
            'parse_mode' => $parse_mode,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendPhoto', $args);
    }

    function sendContact($chat, $phone_number, $first_name, $last_name = false, $vcard = false, $keyboard = false, $keyboard_type = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'phone_number' => $phone_number,
            'first_name' => $first_name,
            'vcard' => $vcard,
            'last_name' => $last_name,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendContact', $args);
    }

    function sendPoll($chat, $question, $options, $keyboard = false, $keyboard_type = false, $reply_to_message_id = false, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
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
            'question' => $question,
            'options' => $options,
            'reply_to_message_id' => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if (isset($reply_markup)) $args['reply_markup'] = json_encode($reply_markup);
        return $this->sendRequest('sendPoll', $args);
    }

    function sendChatAction($chat, $action)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
            'action' => $action,
        ];
        return $this->sendRequest('sendAction', $args);
    }

    function getFile($file_id, $response_type = false)
    {
        $args = [
            'file_id' => $file_id,
        ];
        return $this->sendRequest('getFile', $args, $response_type);
    }

    function answerInlineQuery($inline, $results, $switch_pm_text = false, $switch_pm_parameter = false, $cache_time = 300, $is_personal = true, $next_offset = false)
    {
        if (is_a($inline, 'inline')) {
            $inline_query_id = $inline->id;
        } else {
            $inline_query_id = $inline;
        }
        $args = [
            'inline_query_id' => $inline_query_id,
            'results' => json_encode($results),
            'cache_time' => $cache_time,
            'is_personal' => $is_personal,
        ];
        if ($switch_pm_text) $args['switch_pm_text'] = $switch_pm_text;
        if ($switch_pm_parameter) $args['switch_pm_parameter'] = $switch_pm_parameter;
        if ($next_offset) $args['next_offset'] = $next_offset;
        return $this->sendRequest('answerInlineQuery', $args);
    }

    function deleteMessage($chat, $message_id)
    {
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ];
        return $this->sendRequest('deleteMessage', $args);
    }

    function getChat($chat, $response_type = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return $this->sendRequest('getChat', $args, $response_type);
    }

    function getChatAdministrators($chat, $response_type = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return $this->sendRequest('getChatAdministrators', $args, $response_type);
    }

    function getChatMembersCount($chat)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return json_decode($this->sendRequest('getChatMembersCount', $args, 'raw'), true)['result'];
    }

    function exportChatInviteLink($chat)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return json_decode($this->sendRequest('exportChatInviteLink', $args, 'raw'), true)['result'];
    }

    function getChatMember($chat, $user, $response_type = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];
        return $this->sendRequest('getChatMember', $args, $response_type);
    }

    function kickChatMember($chat, $user, $until_date = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'until_date' => $until_date,
        ];
        return $this->sendRequest('kickChatMember', $args);
    }

    function unbanChatMember($chat, $user)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];
        return $this->sendRequest('unbanChatMember', $args);
    }

    function restrictChatMember($chat, $user, $permissions, $until_date = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'permissions' => $permissions,
            'until_date' => $until_date,
        ];
        return $this->sendRequest('restrictChatMember', $args);
    }

    function promoteChatMember($chat, $user, $can_change_info = false, $can_post_messages = false, $can_edit_messages = false, $can_delete_messages = false, $can_invite_users = false, $can_restrict_members = false, $can_pin_messages = false, $can_promote_members = false)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'can_change_info' => $can_change_info,
            'can_post_messages' => $can_post_messages,
            'can_edit_messages' => $can_edit_messages,
            'can_delete_messages' => $can_delete_messages,
            'can_invite_users' => $can_invite_users,
            'can_restrict_members' => $can_restrict_members,
            'can_pin_messages' => $can_pin_messages,
            'can_promote_members' => $can_promote_members,
        ];
        return $this->sendRequest('promoteChatMember', $args);
    }

    function setChatPermissions($chat, $permissions)
    {
        if (is_a($chat,'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
            'permissions' => $permissions,
        ];
        return $this->sendRequest('setChatPermissions', $args);
    }

    function setChatPhoto($chat, $photo)
    {
        if (is_a($chat,'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
            'photo' => $photo,
        ];
        return $this->sendRequest('setChatPhoto', $args);
    }

    function setChatTitle($chat, $title)
    {
        if (is_a($chat,'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }

        $args = [
            'chat_id' => $chat_id,
            'title' => $title,
        ];
        return $this->sendRequest('setChatTitle', $args);
    }

    function setChatDescription($chat, $description)
    {
        if (is_a($chat,'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
            'description' => $description,
        ];
        return $this->sendRequest('setChatDescription', $args);
    }

    function deleteChatPhoto($chat)
    {
        if (is_a($chat,'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return $this->sendRequest('deleteChatPhoto', $args);
    }

    function pinChatMessage($chat, $message, $disable_notification = 0)
    {
        if (is_a($chat, 'chat') ) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        if (is_a($message, 'message')) {
            $message_id = $message;
        } else {
            $message_id = $message;
        }
        if ($disable_notification === 0) $disable_notification = $this->config['disable_notification'];
        $args = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'disable_notification' => $disable_notification,
        ];
        return $this->sendRequest('pinCharMessage', $args);
    }

    function unpinChatMessage($chat)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }

        $args = [
            'chat_id' => $chat_id,
        ];
        return $this->sendRequest('unpinCharMessage', $args);
    }

    function leaveChat($chat)
    {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $args = [
            'chat_id' => $chat_id,
        ];
        return $this->sendRequest('leaveChat', $args);
    }
    function getMe() {
        return $this->sendRequest('getMe');
    }
    function getWebhookInfo() {
        return $this->sendRequest('getWebhookInfo');
    }
    function setWebhook ($url,$certificate=false,$max_connections=40,$allowed_updates= false) {
        $args = [
            'url' => $url,
            'certificate' => $certificate,
            'max_connections' => $max_connections,
            'allowed_updates' => $allowed_updates,
        ];
        return $this->sendRequest('setWebhook',$args);
    }
}
