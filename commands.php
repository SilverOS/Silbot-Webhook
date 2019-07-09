<?php
if (isset($bot)) {
    if ($update->type === 'callback_query' && isset($callback)) {
        if ($callback->data == 'ciao' && isset($user)) {
            $bot->editMessageText($message->chat->id, $message->message_id, 'Ciao!');
            $r = $bot->answerCallbackQuery($callback->id, 'Ok');
        }
    } else {
        if (isset($message->text) && $update->type === 'message') {
            if ($message->text == '/start') {
                $keyboard[] = [['text' => 'ciao', 'callback_data' => 'ciao']];
                $r = $bot->sendMessage($user->id, 'Ciao', $keyboard);
            } elseif ($message->text == '/photo') {
                $bot->sendPhoto($chat->id,'http://www.silveros.it/img/logo.png');
            }
        }
    }
}