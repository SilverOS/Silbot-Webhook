<?php
if (isset($bot) && isset($chat) && isset($message) && isset($user)) {
    if ($message->text == '/start') {
        $keyboard = [
            [['text' => 'Callback Test','callback_data' => '/test']],
            [['text' => 'URL Example','url' => 'https://github.com/SilverOS/Silbot-Webhook'],['text' => 'Switch Inline','switch_inline_query' => 'Example Text']],
        ];
        $bot->sendMessage($chat,'Hello ' . $user->htmlmention . '!',$keyboard,'inline','html');
    } elseif (isset($callback->data) && $callback->data == '/test') {
        $bot->editMessageText($chat,$message,'Message edited!');
        $callback->answer('Callback Answered',true);
    } elseif ($message->text == '/delete') {
        $message->deleteMessage();
        /*
         * You can also do
         * $bot->deleteMessage($chat,$message);
         * or
         * $bot->deleteMessage($chat->id,$message->id);
         */
    } elseif ($message->text == '/photo') {
        $bot->sendPhoto($chat,'https://www.silveros.it/img/silbot.png');
    } elseif (isset($photo)) {
        $photo->send($chat,'Here is your photo!');
    } elseif (isset($sticker)) {
        $r = $sticker->send($chat);
    }
}