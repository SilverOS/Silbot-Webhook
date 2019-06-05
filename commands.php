<?php
if (isset($bot)) {
    if (isset($callback)) {
        if ($callback->data == 'ciao' && isset($user)) {
            $bot->sendMessage($user->id,'Ciao!');
        }
    } else {
        if (isset($message->text)) {
            if ($message->text == '/start') {
                $keyboard[] = [['text' => 'ciao', 'callback_data' => 'ciao']];
                $r = $bot->sendMessage($user->id, 'Ciao', $keyboard);
            }
        }
    }
}