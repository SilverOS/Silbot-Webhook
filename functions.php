<?php
echo "<br />Funzioni";


function sr($method, $args)
{
    global $token;
    $args    = http_build_query($args);
    $request = curl_init("https://api.telegram.org/$token/$method");
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
function action($chatID, $action)
{
    $args = array(
        "chat_id" => $chatID,
        "action" => $action
    );
    return sr("sendChatAction", $args);
}
function sm($chatID, $msg, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false, $disablewebpreview = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    if (!$disablewebpreview) {
        $disablewebpreview = $config['disabilitapreview'];
    }
    $args = array(
        "chat_id" => $chatID,
        "text" => $msg,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "disable_web_page_preview" => false
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "typing");
    }
    sr("sendMessage", $args);
}
function sendMessage()
{
    return call_user_func_array("sm", func_get_args());
}
function em($chatID, $msg, $msgid, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false, $disablewebpreview = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    if (!$disablewebpreview) {
        $disablewebpreview = $config['disabilitapreview'];
    }
    $args = array(
        "chat_id" => $chatID,
        "text" => $msg,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "disable_web_page_preview" => $disablewebpreview,
        "message_id" => $msgid
    );
    return sr("editMessageText", $args);
}
function cb_reply($id, $text, $alert = false, $cbmid = false, $ntext = false, $nmenu = false, $npm = "pred")
{
    global $api;
    global $chatID;
    global $config;
    if ($npm == 'pred')
        $npm = $config['parse_mode'];
    $args = array(
        'callback_query_id' => $id,
        'text' => $text,
        'show_alert' => $alert
    );
    $r    = sr("answerCallbackQuery", $args);
    if ($cbmid) {
        if ($nmenu) {
            $rm = array(
                'inline_keyboard' => $nmenu
            );
            $rm = json_encode($rm);
        }
        $args = array(
            'chat_id' => $chatID,
            'message_id' => $cbmid,
            'text' => $ntext,
            'parse_mode' => $npm
        );
        if ($nmenu)
            $args["reply_markup"] = $rm;
        $r = sr("editMessageText", $args);
    }
    return $r;
}
function editMessageText()
{
    return call_user_func_array("em", func_get_args());
}
//ForwardMessage
function fw($chatID, $from, $msgid)
{
    $args = array(
        "chat_id" => $chatID,
        "from_chat_id" => $from,
        "message_id" => $msgid
    );
    return sr("forwardMessage", $args);
}
function forwardMessage()
{
    return call_user_func_array("fw", func_get_args());
}
//sendPhoto
function si($chatID, $image, $caption = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "photo" => $image,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "caption" => $caption
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_photo");
    }
    return sr("sendPhoto", $args);
}
function sendPhoto()
{
    return call_user_func_array("si", func_get_args());
}
//sendAudio
function sa($chatID, $audio, $caption = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false, $autore = "false", $titolo = "false")
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "audio" => $audio,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "caption" => $caption,
        "title" => $titolo,
        "performer" => $autore
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_audio");
    }
    return sr("sendAudio", $args);
}
function sendAudio()
{
    return call_user_func_array("sa", func_get_args());
}
function sd($chatID, $document, $caption = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "document" => $document,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "caption" => $caption
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_document");
    }
    return sr("sendDocument", $args);
}
function sendDocument()
{
    return call_user_func_array("sd", func_get_args());
}
//sendVideo
function sv($chatID, $video, $caption = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "video" => $video,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "caption" => $caption
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_video");
    }
    return sr("sendVideo", $args);
}
function sendVideo()
{
    return call_user_func_array("sm", func_get_args());
}
function sendAnimation($chatID, $animation, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "animation" => $animation,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_video");
    }
    return sr("sendAnimation", $args);
}
function editMessageCaption($chatID, $caption, $msgid, $parse_mode)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    if (!$disablewebpreview) {
        $disablewebpreview = $config['disabilitapreview'];
    }
    $args = array(
        "chat_id" => $chatID,
        "caption" => $caption,
        "parse_mode" => $parse_mode,
        "message_id" => $msgid
    );
    
    
}
//sendVoice
function svc($chatID, $voice, $caption = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "voice" => $voice,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "caption" => $caption
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "record_audio");
    }
    return sr("sendVoice", $args);
}
function sendVoice()
{
    return call_user_func_array("svc", func_get_args());
}
//sendSticker
function ss($chatID, $sticker, $menu = false, $keyboardtype = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    $args = array(
        "chat_id" => $chatID,
        "sticker" => $sticker,
        "reply_to_message_id" => $reply_to_message
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    return sr("sendSticker", $args);
}
function sendSticker()
{
    return call_user_func_array("ss", func_get_args());
}
//sendVideoNote
function svn($chatID, $video_note, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "video_note" => $video_note,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "upload_video_note");
    }
    return sr("sendVideoNote", $args);
}
function sendVideoNote()
{
    return call_user_func_array("svn", func_get_args());
}
//deleteMessage
function dm($chatID, $msgid)
{
    global $token;
    $args = array(
        "chat_id" => $chatID,
        "message_id" => $msgid
    );
    return sr("deleteMessage", $args);
}
function deleteMessage()
{
    return call_user_func_array("dm", func_get_args());
}
//sendLocation
function sl($chatID, $latitude, $longitude, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false, $live_period = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "latitude" => $latitude,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message,
        "longitude" => $lungitude,
        "live_period" => $live_period
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "find_location");
    }
    return sr("sendLocation", $args);
}
function sendLocation()
{
    return call_user_func_array("sl", func_get_args());
}
function sc($chatID, $phone_number, $first_name, $last_name = false, $menu = false, $keyboardtype = false, $parse_mode = false, $reply_to_message = false)
{
    global $token;
    global $config;
    if (!$keyboardtype && $menu) {
        $keyboardtype = $config['tastiera'];
    }
    if ($keyboardtype == "reply") {
        $rm = array(
            'keyboard' => $menu,
            'resize_keyboard' => true
        );
    } elseif ($keyboardtype == "inline") {
        $rm = array(
            'inline_keyboard' => $menu
        );
    } elseif ($keyboardtype == "nascondi") {
        
        $rm = array(
            'hide_keyboard' => true
        );
    }
    $rm = json_encode($rm);
    
    if (!$parse_mode) {
        $parse_mode = $config['parse_mode'];
    }
    $args = array(
        "chat_id" => $chatID,
        "phone_number" => $phone_number,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "parse_mode" => $parse_mode,
        "reply_to_message_id" => $reply_to_message
    );
    if ($menu)
        $args['reply_markup'] = $rm;
    if ($config['action']) {
        action($chatID, "typing");
    }
    return sr("sendContact", $args);
}
function sendContact()
{
    return call_user_func_array("sc", func_get_args());
}
function sendMediaGroup($chatID, $media, $reply = false)
{
    $media = json_encode($media);
    $args  = array(
        "chat_id" => $chatID,
        "media" => $media,
        "reply_to_message_id" => $reply
    );
    return sr("sendMediaGroup", $args);
}

//GRUPPI
function deleteChatPhoto($chatID)
{
    global $token;
    $args = array(
        "chat_id" => $chatID
    );
    return sr("deleteChatPhoto", $args);
}
function setChatPhoto($chatID, $photo)
{
    global $token;
    $args = array(
        "chat_id" => $chatID,
        "photo" => $photo
    );
    return sr("setChatPhoto", $args);
}
function ban($chatID, $userID, $time = 0)
{
    global $api;
    $args = array(
        'chat_id' => $chatID,
        'user_id' => $userID,
        'until_date' => $time
    );
    return sr("kickChatMember", $args);
}
function kickChatMember()
{
    return call_user_func_array("ban", func_get_args());
}
function unban($chatID, $userID)
{
    global $api;
    $args = array(
        'chat_id' => $chatID,
        'user_id' => $userID
    );
    return sr("unbanChatMember", $args);
}
function unbanChatMember()
{
    return call_user_func_array("unban", func_get_args());
}
//fissa
function fissa($chatID, $msgid)
{
    global $api;
    $args = array(
        'chat_id' => $chatID,
        'message_id' => $msgid
    );
    return sr("pinChatMessage", $args);
}
function pinChatMessage()
{
    return call_user_func_array("fissa", func_get_args());
}
function unpinChatMessage($chatID)
{
    global $token;
    $args = array(
        "chat_id" => $chatID
    );
    return sr("unpinChatMessage", $args);
}
function limita($chatID, $userID, $dateRelase, $sendMsg, $sendMedia, $sendOther, $WPPreview)
{
    global $token;
    $args = array(
        "chat_id" => $chatID,
        "user_id" => $userID,
        "until_date" => $dateRelase,
        "can_send_messages" => $sendMsg,
        "can_send_media_messages" => $sendMedia,
        "can_send_other_messages" => $sendOther,
        "can_add_web_page_previews" => $WPPreview
    );
    return sr("restrictChatMember", $args);
}
function restrictChatMember()
{
    return call_user_func_array("limita", func_get_args());
}
function promoteChatMember($chatID, $userID, $changeInfo, $postMsg, $modifyMsg, $deleteMsg, $inviteUsers, $restrictUsers, $pinMsg, $promoteUsers)
{
    global $token;
    $args = array(
        "chat_id" => $chatID,
        "user_id" => $userID,
        "can_change_info" => $changeInfo,
        "can_post_messages" => $postMsg,
        "can_edit_messages" => $modifyMsg,
        "can_delete_messages" => $deleteMsg,
        "can_invite_users" => $inviteUsers,
        "can_restrict_members" => $restrictUsers,
        "can_pin_messages" => $pinMsg,
        "can_promote_members" => $promoteUsers
    );
    return sr("promoteChatMember", $args);
}
function getlink($chatID)
{
    global $token;
    $args = array(
        "chat_id" => $chatID
    );
    $j    = json_decode(sr("exportChatInviteLink", $args), true);
    return $j["result"];
}
function exportChatInviteLink()
{
    return call_user_func_array("getlink", func_get_args());
}
function getChatMembersCount($chatID)
{
    global $token;
    $args = array(
        "chat_id" => $chatID
    );
    $j    = json_decode(sr("getChatMembersCount", $args), true);
    return $j["result"];
}
function setChatTitle($chatID, $title)
{
    $args = array(
        "chat_id" => $chatID,
        "title" => $title
    );
    return sr("setChatTitle", $args);
}
function setChatDescription($chatID, $description)
{
    $args = array(
        "chat_id" => $chatID,
        "title" => $description
    );
    return sr("setChatDescription", $args);
}
function leaveChat($chatID)
{
    $args = array(
        "chat_id" => $chatID
    );
    return sr("leaveChat", $args);
}
function getChatMember($chatID, $userID)
{
    $args = array(
        "chat_id" => $chatID,
        "user_id" => $userID
    );
    return sr("getChatMember", $args);
}
    if ($config['db']) {
        function id($username)
        {
            global $userbot;
            global $db;
            $username = str_replace("@", "", $username);
            $q        = $db->query("select * from `$userbot` where username = '" . $username . "'");
            $u        = $q->fetch(PDO::FETCH_ASSOC);
            return $u['chat_id'];
        }
        function username($id)
        {
            global $userbot;
            global $db;
            $q = $db->query("select * from `$userbot` where chat_id = $id");
            $u = $q->fetch(PDO::FETCH_ASSOC);
            return $u['username'];
        }
    }
}
