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
                $dbinfo = $q->fetch(PDO::FETCH_ASSOC);
                if ($dbinfo['state'] == 'group' && $state == '') {
                    $db->prepare('UPDATE '. $config['database']['bot_table'] . ' SET state = ? WHERE chat_id = ?')->execute(['',$this->id]);
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
}

class message
{
    var $array;

    function __construct($array)
    {
        foreach ($array as $key => $value) {
            if ($key === 'from') {
                $this->user = new user($value);
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
            } elseif ($key === 'poll') {
                $this->poll = new poll($value);
            } else {
                $this->$key = $value;
            }
        }
    }

    function getHtmlText($text = false, $entities = false)
    {
        if (!$text) $text = $this->text;
        if (!$entities) $entities = $this->entities;
        if (!isset($text) || !isset($this->entities) || !isset($entities)) {
            return $text;
        }
        $msg = htmlspecialchars($text);
        $added = 0;
        foreach ($entities as $entity) {
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
                foreach ($value as $option) {
                    $this->$key->$option = new pollOption ($value);
                }
            } else {
                $this->$key = $value;
            }
        }
        return $this;
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
