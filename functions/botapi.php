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
    function forwardMessage ($chat_id,$from_chat_id,$message_id,$disable_notification) {
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
        $args = [
            'chat_id' => $chat_id,
            'from_char_id' => $from_chat_id,
            'message_id' => $message_id,
            'disabla_norification' => $disable_notification,
        ];
        return $this->sendRequest('forwardMessage',$args);
    }
    function sendPhoto ($chat_id,$photo,$caption = '',$keyboard = false,$keyboard_type = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendAudio ($chat_id,$audio,$caption = '',$keyboard = false,$keyboard_type = false,$duration=false,$performer=false,$title = false, $thumb = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendDocument ($chat_id,$document,$caption = '',$keyboard = false,$keyboard_type = false,$thumb = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendVideo ($chat_id,$video,$caption = '',$keyboard = false,$keyboard_type = false,$thumb = false,$height=false,$width=false,$duration = false,$supports_streaming = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendVoice ($chat_id,$voice,$caption = '',$keyboard = false,$keyboard_type = false,$duration=false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendVideoNote ($chat_id,$video_note,$caption = '',$keyboard = false,$keyboard_type = false,$thumb = false,$length = false,$duration = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendSticker ($chat_id,$sticker,$keyboard = false,$keyboard_type = false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
        return $this->sendRequest('sendSticker',$args);
    }
    function sendLocation ($chat_id,$longitude,$latitude,$keyboard = false,$live_period = false,$keyboard_type = false,$parse_mode=false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$parse_mode) $parse_mode = $this->config['parse_mode'];
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendContact ($chat_id,$phone_number,$first_name,$last_name=false,$vcard= false,$keyboard = false,$keyboard_type = false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendPoll ($chat_id,$question,$options,$keyboard = false,$keyboard_type = false,$reply_to_message_id = false,$disable_notification = false) {
        if (!$disable_notification) $disable_notification = $this->config['disable_notification'];
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
    function sendChatAction ($chat_id,$action) {
        $args = [
            'chat_id' => $chat_id,
            'action' => $action,
        ];
        return $this->sendRequest('sendAction',$args);
    }
    function getFile ($file_id) {
        $args = [
            'file_id' => $file_id,
        ];
        return $this->sendRequest('getFile',$args);
    }
    function answerInlineQuery ($inline_query_id,$results,$switch_pm_text=false,$switch_pm_parameter=false,$cache_time=300,$is_personal=true,$next_offset=false) {
        $args = [
            'inline_query_id' => $inline_query_id,
            'results' => json_encode($results),
            'cache_time' => $cache_time,
            'is_personal' => $is_personal,
        ];
        if ($switch_pm_text) $args['switch_pm_text'] = $switch_pm_text;
        if ($switch_pm_parameter) $args['switch_pm_parameter'] = $switch_pm_parameter;
        if ($next_offset) $args['next_offset'] = $next_offset;
        return $this->sendRequest('answerInlineQuery',$args);
    }
}
