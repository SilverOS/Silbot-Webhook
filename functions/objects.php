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
        if (isset($config) && $config['database']['active']) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if(!isset($this->username)) $this->username = '';
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['universal_table'] . ' (chat_id,username,lang,type) VALUES (?,?,?,?)')->execute([$this->id,$this->username,$this->language_code,'user']);
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
                if ($this->db->state == 'group' && $state == '') {
                    $db->prepare('UPDATE '. $config['database']['bot_table'] . ' SET state = ? WHERE chat_id = ?')->execute(['',$this->id]);
                }
                return false;
            }
        }
    }
    function getChatMember($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->getChatMember($chat_id,$this->id);
    }
    function isAdmin($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        $admins = json_decode($botObject->getChatAdministrators($chat_id,'raw'),true)['result'];
        foreach ($admins as $admin) {
            if ($admin['user']['id'] == $this->id) {
                return true;
            }
        }
        return false;
    }
    function isMember($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        $result = $botObject->getChatMember($chat_id,$this->id,'object');
        if (!$result->ok || !isset($result->status)) {
            return false;
        } else {
            if ($result->status == 'left') {
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
            $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ?');
            $q->execute([$id]);
            if (!$q->rowCount()) {
                return false;
            } else {
                $dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                foreach ($dbinfo as $column => $value) {
                    $this->$column = $value;
                }
                return false;
            }
        }
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
        if (isset($config) && $config['database']['active']) {
            $q = $db->prepare('SELECT * FROM ' . $config['database']['universal_table'] . ' WHERE chat_id = ?');
            $q->execute([$this->id]);
            if(!isset($this->username)) $this->username = '';
            if (!$q->rowCount()) {
                $db->prepare('INSERT INTO ' . $config['database']['universal_table'] . ' (chat_id,username,lang,type) VALUES (?,?,?,?)')->execute([$this->id,$this->username,'',$this->type]);
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
    function getChatMember($user_id,$botObject = false) {
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
            } elseif ($key === 'poll') {
                $this->poll = new poll($value);
            } else {
                $this->$key = $value;
            }
        }
    }
    function forwardMessage ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
    }
    function deleteMessage ($botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->deleteMessage($this->chat_id,$this->message_id);
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
            } elseif ($key === 'message') {
                $this->message = new message($value);
            } else {
                $this->$key = $value;
            }
        }
    }
}
class response {
    var $array;
    function __construct($array)
    {
        if ($array['ok']) {
            $this->ok = true;
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
// Media

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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
    }

}
class audio
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function download ($bot,$path = false) {
        $file = new file($this->file_id,$bot);
        if ($file) {
            return $file->download($path);
        }
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
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
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
    }

}

class poll
{
    var $array;
    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'options') {
                foreach ($value as $option) {
                    $this->$key->$option = new pollOption ($value);
                }
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
    function forward ($chat_id,$botObject = false) {
        global $bot;
        if (!$botObject && isset($bot)) {
            $botObject = $bot;
        } else {
            return false;
        }
        return $botObject->forwardMessage($chat_id,$this->chat_id,$this->message_id);
    }

}

class pollOption
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
class file
{
    var $array;
    var $bot;
    function __construct($array,$bot = false)
    {
        if (!is_array($array) && $bot) {
            $array = json_decode($bot->getFile($array),true);
            $this->bot = $bot;
        } elseif (!$bot) {
            return 0;
        }
        foreach ($array['result'] as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    function download ($path = false,$bot = false) {
        if (!$bot && isset($this->bot)) {
            $bot = $this->bot;
        } elseif (!$bot) {
            return 0;
        }
        $content = file_get_contents('https://api.telegram.org/file/'. $bot->token .'/' . $this->file_path);
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
