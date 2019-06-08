<?php
isset($_GET['token']) ? $token = 'bot' . $_GET['token'] : $token = false;

require 'config.php';
$bot = new botApi($token,$config);
$update = new update(file_get_contents('php://input'), $token, $config);
//$bot->sendMessage(141691961,json_encode($update->update,JSON_PRETTY_PRINT));
if (isset($update->message)) {
    $message = $update->message;
    if (isset($message->photo)) $photo = $message->photo;
    if (isset($message->audio)) $audio = $message->audio;
    if (isset($message->voice)) $voice = $message->voice;
    if (isset($message->animation)) $animation = $message->animation;
    if (isset($message->document)) $document = $message->document;
    if (isset($message->video)) $video = $message->video;
    if (isset($message->video_note)) $video_note = $message->video_note;
    if (isset($message->contact)) $contact = $message->contact;
    if (isset($message->location)) $location = $message->location;
    if (isset($message->venue)) $venue = $message->venue;
    if (isset($message->poll)) $poll = $message->poll;
}
if (isset($update->chat)) $chat = $update->chat;
if (isset($update->user)) $user = $update->user;
if (isset($update->callback)) $callback = $update->callback;
include 'commands.php';
