<?php

class user
{
    var $array;

    function __construct($array)
    {
        global $config;
        global $db;
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        isset($this->last_name) ? $this->name = $this->first_name . ' ' . $this->last_name : $this->name = $this->first_name;
        $this->htmlmention = '<a href="tg://user?id='.$this->id.'">'.$this->name.'</a>';
        $this->markdownmention = '[' . $this->name .'](tg://user?id='.$this->id.')';
        if (isset($config) && $config['database']['active'] && (!(isset($this->unsave_db) && $this->unsave_db))) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if(!isset($this->username)) $this->username = '';
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['universal_table'] . ' (chat_id,username,name,lang,type) VALUES (?,?,?,?,?)')->execute([$this->id,$this->username,$this->name,$this->language_code,'user']);
            } else {
                $this->dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                if ($this->dbinfo['username'] != $this->username) {
                    $db->prepare('UPDATE '. $config['database']['universal_table'] . ' SET username = ? WHERE chat_id = ?')->execute([$this->username,$this->id]);
                }
                if ($this->dbinfo['name'] != $this->name) {
                    $db->prepare('UPDATE '. $config['database']['universal_table'] . ' SET name = ? WHERE chat_id = ?')->execute([$this->name,$this->id]);
                }
            }
        }
    }
    function db_save($state = '') {
        global $config;
        global $db;
        if (isset($config) && $config['database']['active']) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['bot_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['bot_table'] . ' (chat_id,state) VALUES (?,?)')->execute([$this->id,$state]);
                return true;
            } else {
                $this->db = new DBUser($this->id);
                if ($this->db->state == 'group' && $state == '') {
                    $db->prepare('UPDATE '. $config['database']['bot_table'] . ' SET state = ? WHERE chat_id = ?')->execute(['',$this->id]);
                }
                return false;
            }
        }
    }
    function getChatMember($chat,$botObject = false) {
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChatMember($chat_id,$this->id);
    }
    function isAdmin($chat,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $admins = json_decode($botObject->getChatAdministrators($chat_id,'raw'),true)['result'];
        foreach ($admins as $admin) {
            if ($admin['user']['id'] == $this->id) {
                return true;
            }
        }
        return false;
    }
    function isMember($chat,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        if (is_a($chat, 'chat')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        $result = $botObject->getChatMember($chat_id,$this->id,'object');
        if (!$result->ok || !isset($result->status)) {
            return false;
        } else {
            if ($result->status == 'left' || $result->status == 'kicked') {
                return false;
            } else {
                return true;
            }
        }
    }
}
class DBUser {
    function __construct($id)
    {
        global $config;
        global $db;
        if (isset($config) && $config['database']['active']) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['bot_table'] . ' WHERE chat_id = ?');
            $q->execute([$id]);
            if (!$q->rowCount()) {
                return false;
            } else {
                $dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                foreach ($dbinfo as $column => $value) {
                    $this->$column = $value;
                }
                $this->tgdb = new TGDBUser($id);
                return false;
            }
        }
    }
    function setColumn ($column,$value) {
        global $db;
        global $config;
        $q = $db->prepare('UPDATE ' . $config['database']['bot_table'] . ' SET ' . $column .' = ? WHERE chat_id = ?');
        $q->execute([$value,$this->chat_id]);
    }
}
class TGDBUser {
    function __construct($id)
    {
        global $config;
        global $db;
        if (isset($config) && $config['database']['active']) {
            if (is_numeric($id)) {
                $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ? LIMIT 1');
            } elseif (is_string($id)) {
                $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE username LIKE ? LIMIT 1');
            }
            $q->execute([$id]);
            $this->htmlmention = '<a href="tg://user?id='.$this->chat_id.'">'.$this->name.'</a>';
            $this->markdownmention = '[' . $this->name .'](tg://user?id='.$this->chat_id.')';
            if (!$q->rowCount()) {
                return false;
            } else {
                $dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                foreach ($dbinfo as $column => $value) {
                    $this->$column = $value;
                }
            }
        }
    }
    function getDBUserObject() {
        return new DBUser($this->chat_id);
    }
    function getUserObject () {
        if ($this->type == 'user') {
            return new user(['id' => $this->id,'username' => $this->username,'first_name' => $this->name,'language_code' => $this->lang,'type' => $this->type,'unsave_db' => true]);
        }
    }
    function getChatObject() {
        if ($this->type == 'user') {
            return new chat(['id' => $this->id,'username' => $this->username,'first_name' => $this->name,'language_code' => $this->lang,'type' => $this->type,'unsave_db' => true]);
        } else {
            return new chat(['id' => $this->id,'username' => $this->username,'title' => $this->name,'language_code' => $this->lang,'type' => $this->type,'unsave_db' => true]);
        }
    }
    function setColumn ($column,$value) {
        global $db;
        global $config;
        $q = $db->prepare('UPDATE ' . $config['database']['universal_table'] . ' SET ' . $column .' = ? WHERE chat_id = ? LIMIT 1');
        $q->execute([$value,$this->chat_id]);
    }
}
class chat
{
    var $array;

    function __construct($array)
    {
        global $config;
        global $db;
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        if (isset($this->title)) {
            $this->name = $this->title;
        } else {
            isset($this->last_name) ? $this->name = $this->first_name . ' ' . $this->last_name : $this->name = $this->first_name;
        }
        if (isset($config) && $config['database']['active'] && (!(isset($this->unsave_db) && $this->unsave_db))) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if(!isset($this->username)) $this->username = '';
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['universal_table'] . ' (chat_id,username,name,lang,type) VALUES (?,?,?,?,?)')->execute([$this->id,$this->username,$this->name,'',$this->type]);
            } else {
                $this->dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                if ($this->dbinfo['username'] != $this->username) {
                    $db->prepare('UPDATE '. $config['database']['universal_table'] . ' SET username = ? WHERE chat_id = ?')->execute([$this->username,$this->id]);
                }
            }
        }
    }
    function db_save($state = '') {
        global $config;
        global $db;
        if (isset($config) && $config['database']['active']) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['bot_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['bot_table'] . ' (chat_id,state) VALUES (?,?)')->execute([$this->id,$state]);
                return true;
            } else {
                $this->db = new DBUser($this->id);
                return false;
            }
        }
    }
    function getChat($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChat($this->id);
    }
    function getChatMembersCount($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChatMembersCount($this->id);
    }
    function getChatMember($user,$botObject = false) {
        if (is_a($user, 'user')) {
            $user_id = $user->id;
        } else {
            $user_id = $user;
        }
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChatMember($this->id,$user_id);
    }
    function getChatAdministrators($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChatAdministrators($this->id);
    }
    function exportChatInviteLink($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->exportChatInviteLink($this->id);
    }
}

class message
{
    var $array;

    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
                $this->from = $this->user;
            } elseif ($key === 'chat') {
                $this->chat = new chat($value);
            } elseif ($key === 'forward_from_chat') {
                $this->forward_from_chat = new chat($value);
                $this->fwchat = $this->forward_from_chat;
            } elseif ($key === 'forward_from') {
                $this->forward_from = new user($value);
                $this->fwuser = $this->forward_from;
            } elseif ($key === 'reply_to_message') {
                $this->reply_to_message = new message($value);
                $this->reply = $this->reply_to_message;
            } elseif ($key === 'photo') {
                $this->photo = new photo($value[count($value) - 1]);
            } elseif ($key === 'audio') {
                $this->audio = new audio($value);
            } elseif ($key === 'voice') {
                $this->voice = new voice($value);
            } elseif ($key === 'animation') {
                $this->animation = new animation($value);
            } elseif ($key === 'document' && !isset($this->animation)) {
                $this->document = new document($value);
            } elseif ($key === 'video') {
                $this->video = new video($value);
            } elseif ($key === 'video_note') {
                $this->video_note = new video_note($value);
            } elseif ($key === 'contact') {
                $this->contact = new contact($value);
            } elseif ($key === 'location') {
                $this->location = new location($value);
            } elseif ($key === 'venue') {
                $this->venue = new venue($value);
            } elseif ($key === 'sticker') {
                $this->sticker = new sticker($value);
            } elseif ($key === 'dice') {
                $this->sticker = new dice($value);
            } elseif ($key === 'poll') {
                $this->poll = new poll($value);
            } else {
                $this->$key = $value;
            }
        }
    }
    function forwardMessage ($chat,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        if (is_a($chat, 'chat') || is_a($chat, 'user')) {
            $chat_id = $chat->id;
        } else {
            $chat_id = $chat;
        }
        return $botObject->forwardMessage($chat_id,$this->chat->id,$this->message_id);
    }
    function deleteMessage ($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->deleteMessage($this->chat->id,$this->message_id);
    }
    function toHTML () {
        if (isset($this->text)) {
            $text = $this->text;
        } elseif (isset($this->caption)) {
            $text = $this->caption;
        } else {
            return false;
        }
        if (!isset($this->entities)) {
            return $text;
        }
        $entityParser = new entityParser();
        return $entityParser->entitiesToHtml($text,$this->entities);
    }
}
class callback_query
{
    var $array;

    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
                $this->from = $this->user;
            } elseif ($key === 'message') {
                $this->message = new message($value);
            } else {
                $this->$key = $value;
            }
        }
    }
    function answer ($text='',$show_alert = false,$url = false,$cache_time = false,$botObject=false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->answerCallbackQuery($this->id,$text,$show_alert,$url,$cache_time);
    }
}
class response {
    var $array;
    function __construct($array)
    {
        if (!is_array($array) && is_string($array)) {
            $this->raw = $array;
            $array = json_decode($array,true);
        }
        if ($array['ok']) {
            $this->ok = true;
            $this->raw = json_encode($array);
            $array = $array['result'];
            foreach ($array as $key => $value) {
                if ($key === 'from') {
                    $this->user = new user($value);
                } elseif ($key === 'message') {
                    $this->message = new message($value);
                } else {
                    $this->$key = $value;
                }
            }
        } else {
            $this->ok = false;
            foreach ($array as $key => $value) {
                if ($key === 'from') {
                    $this->$key = $value;
                }
            }
            return false;
        }
    }
}
class inline_query
{
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
            } else {
                $this->$key = $value;
            }
        }
    }
}

/*
 *  MEDIA
 */

class photo
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat,$caption='',$keyboard=false,$keyboard_type=false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendPhoto($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$parse_mode,$reply_to_message_id,$disable_notification);
    }

}
class sticker
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat,$keyboard=false,$keyboard_type=false, $reply_to_message_id = false, $disable_notification = 0,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendSticker($chat, $this->file_id, $keyboard, $keyboard_type, $reply_to_message_id, $disable_notification);
    }

}
class audio
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false,$thumb=false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendAudio($chat, $this->file_id,$caption,$keyboard,$keyboard_type,$this->duration,$this->performer,$this->title,$thumb,$parse_mode,$reply_to_message_id,$disable_notification);
    }

}

class voice
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject=false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendVoice($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$this->duration,$parse_mode,$reply_to_message_id,$disable_notification);
    }
}

class document
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false,$thumb=false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendDocument($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$thumb,$parse_mode,$reply_to_message_id,$disable_notification);
    }
}
class video
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $supports_streaming = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject=false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $bot->sendVideo($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$thumb,$this->height,$this->width,$this->duration,$supports_streaming,$parse_mode,$reply_to_message_id,$disable_notification);
    }
}

class animation
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject=false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendAnimation($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$thumb,$this->height,$this->width,$this->duration,$parse_mode,$reply_to_message_id,$disable_notification);
    }
}

class video_note
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'thumb') {
                $this->$key = new photo ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function download ($path = false,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $file = new file($this->file_id,$botObject);
        if ($file) {
            return $file->download($path);
        }
    }
    function send($chat, $caption = '', $keyboard = false, $keyboard_type = false, $thumb = false, $parse_mode = false, $reply_to_message_id = false, $disable_notification = 0,$botObject=false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        return $botObject->sendVideoNote($chat,$this->file_id,$caption,$keyboard,$keyboard_type,$thumb,$this->lenght,$this->duration,$parse_mode,$reply_to_message_id,$disable_notification);
    }
}

class contact
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
}
class dice
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
}

class location
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

}

class venue
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'venue') {
                $this->$key = new location ($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }

}

class poll
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'options') {
                foreach ($value as $option => $val) {
                    $this->$key[$option]= new pollOption ($val);
                }
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function getOptions() {
        $options = [];
        foreach ($this->options as $option) {
            $options[] = $option->text;
        }
        return $options;
    }
}

class pollOption
{
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

}
class file
{
    var $array;
    var $bot;
    function __construct($array,$botObject = false)
    {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        if (!is_array($array) && $botObject) {
            $array = json_decode($botObject->getFile($array),true);
            $this->bot = $botObject;
        } elseif (!$bot) {
            return false;
        }
        foreach ($array['result'] as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    function download ($path = false,$botObject = false)
    {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } elseif (!$botObject) {
            return false;
        }
        $content = file_get_contents('https://api.telegram.org/file/'. $botObject->token .'/' . $this->file_path);
        if (!$content) {
            return false;
        }
        if ($path) {
            return file_put_contents($path,$content);
        } else {
            return $content;
        }
    }
}
class generic_json {
    function __construct($array)
    {
        if (is_string($array)) {
            $json = json_decode($array,true);
        } elseif (is_array($array)) {
            $json = $array;
        }
        foreach ($json as $key => $value) {
            if (is_array($value)) {
                $this->$key = new generic_json($value);
            } else {
                $this->$key = $value;
            }
        }
    }
}

/*
 *
 * Note: entityParser class has been programmed by davtur19
 * Here is original code: https://github.com/davtur19/TelegramEntityParser
 *
 */
class entityParser {
    function mbStringToArray($string, $encoding = 'UTF-8')
    {
        $array = [];
        $strlen = mb_strlen($string, $encoding);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, $encoding);
            $string = mb_substr($string, 1, $strlen, $encoding);
            $strlen = mb_strlen($string, $encoding);
        }
        return $array;
    }

    function parseTagOpen($textToParse, $entity, $oTag)
    {
        $i = 0;
        $textParsed = '';
        $nullControl = false;
        $string = $this->mbStringToArray($textToParse, 'UTF-16LE');
        foreach ($string as $s) {
            if ($s === "\0\0") {
                $nullControl = !$nullControl;
            } elseif (!$nullControl) {
                if ($i == $entity['offset']) {
                    $textParsed = $textParsed . $oTag;
                }
                $i++;
            }
            $textParsed = $textParsed . $s;
        }
        return $textParsed;
    }

    function parseTagClose($textToParse, $entity, $cTag)
    {
        $i = 0;
        $textParsed = '';
        $nullControl = false;
        $string = $this->mbStringToArray($textToParse, 'UTF-16LE');
        foreach ($string as $s) {
            $textParsed = $textParsed . $s;
            if ($s === "\0\0") {
                $nullControl = !$nullControl;
            } elseif (!$nullControl) {
                $i++;
                if ($i == ($entity['offset'] + $entity['length'])) {
                    $textParsed = $textParsed . $cTag;
                }
            }
        }
        return $textParsed;
    }

    function htmlEscape($textToParse)
    {
        $i = 0;
        $textParsed = '';
        $nullControl = false;
        $string = $this->mbStringToArray($textToParse, 'UTF-8');
        foreach ($string as $s) {
            if ($s === "\0") {
                $nullControl = !$nullControl;
            } elseif (!$nullControl) {
                $i++;
                $textParsed = $textParsed . str_replace(['&', '"', '<', '>'], ["&amp;", "&quot;", "&lt;", "&gt;"], $s);
            } else {
                $textParsed = $textParsed . $s;
            }
        }
        return $textParsed;
    }


    function entitiesToHtml($text, $entities)
    {
        $textToParse = mb_convert_encoding($text, 'UTF-16BE', 'UTF-8');

        foreach ($entities as $entity) {
            $href = false;
            switch ($entity['type']) {
                case 'bold':
                    $tag = 'b';
                    break;
                case 'italic':
                    $tag = 'i';
                    break;
                case 'underline':
                    $tag = 'ins';
                    break;
                case 'strikethrough':
                    $tag = 'strike';
                    break;
                case 'code':
                    $tag = 'code';
                    break;
                case 'pre':
                    $tag = 'pre';
                    break;
                case 'text_link':
                    $tag = '<a href="' . $entity['url'] . '">';
                    $href = true;
                    break;
                case 'text_mention':
                    $tag = '<a href="tg://user?id=' . $entity['user']['id'] . '">';
                    $href = true;
                    break;
                default:
                    continue 2;
            }

            if ($href) {
                $oTag = "\0{$tag}\0";
                $cTag = "\0</a>\0";
            } else {
                $oTag = "\0<{$tag}>\0";
                $cTag = "\0</{$tag}>\0";
            }
            $oTag = mb_convert_encoding($oTag, 'UTF-16BE', 'UTF-8');
            $cTag = mb_convert_encoding($cTag, 'UTF-16BE', 'UTF-8');

            $textToParse = $this->parseTagOpen($textToParse, $entity, $oTag);
            $textToParse = $this->parseTagClose($textToParse, $entity, $cTag);
        }

        if (isset($entity)) {
            $textToParse = mb_convert_encoding($textToParse, 'UTF-8', 'UTF-16BE');
            $textToParse = $this->htmlEscape($textToParse);
            return str_replace("\0", '', $textToParse);
        }

        return htmlspecialchars($text);
    }
}
