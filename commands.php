<?php
if (isset($bot)) {
    if ($update->type === 'callback_query' && isset($callback)) {
        if ($callback->data == 'Hello' && isset($user)) {
            $bot->editMessageText($message->chat->id, $message->message_id, 'Hello!');
            $r = $bot->answerCallbackQuery($callback->id, 'Ok');
        }
    } else {
        if (isset($message->text) && $update->type === 'message') {
            if ($message->text == '/start') {
                $keyboard[] = [['text' => 'hello', 'callback_data' => 'Hello']];
                $r = $bot->sendMessage($user->id, 'Hello', $keyboard);
            } elseif ($message->text == '/photo') {
                $bot->sendPhoto($chat->id,'http://www.silveros.it/img/logo.png');
            }
        }
    }
}