<?php
echo "<br />Variabili";
$isedited = $update["edited_message"];
$isforwarded = $update["message"]["forward_from"];
$ischannel = $update["message"]["channel_post"];
if (!$config["funziona_inoltrati"] && $isforwarded) {
    return;
}
if (!$config["funziona_modificati"] && $isedited) {
    return;
}

if (!$config["funziona_canali"] && $ischannel) {
    return;
}
if ($isforwarded && $config["funziona_inoltrati"]) {
    if (isset($update["message"]["forward_from"]["first_name"])) $fromnome = $update["message"]["forward_from"]["first_name"];
    if (isset($update["message"]["forward_from"]["last_name"])) $fromcognome = $update["message"]["forward_from"]["last_name"];
    if (isset($update["message"]["forward_from"]["username"])) $fromusername = $update["message"]["forward_from"]["username"];
    if (isset($update["message"]["forward_from"]["id"])) $fromID = $update["message"]["forward_from"]["id"];
}
if ($isedited && $config["funziona_modificati"]) {
    $update['message'] = $update['edited_message'];
}
if (isset($update["message"]["chat"]["id"])) $chatID = $update["message"]["chat"]["id"];
if (isset($update["message"]["from"]["id"])) $userID = $update["message"]["from"]["id"];
if (isset($update["message"]["text"])) $msg = $update["message"]["text"];
if (isset($update["message"]["message_id"])) $msgid = $update["message"]["message_id"];
if (isset($update["message"]["from"]["is_bot"])) $isbot = $update["message"]["from"]["is_bot"];
if (isset($update["message"]["from"]["first_name"])) $nome = $update["message"]["from"]["first_name"];
if (isset($update["message"]["from"]["last_name"])) $cognome = $update["message"]["from"]["last_name"];
$fullname = $nome . " " . $cognome;
if (isset($update["message"]["from"]["username"])) $username = $update["message"]["from"]["username"];
if (isset($update["message"]["from"]["language_code"])) $lingua = $update["message"]["from"]["language_code"];
if (isset($update["message"]["chat"]["type"])) $chat_type = $update["message"]["chat"]["type"];
if ($chatID < 0) {
    if (isset($update["message"]["chat"]["title"])) $titolo = $update["message"]["chat"]["title"];
    if (isset($update["message"]["chat"]["username"])) $usernamechat = $update["message"]["chat"]["username"];
}
//media
if (isset($update["message"]["audio"]["file_id"])) $audio = $update["message"]["audio"]["file_id"];
if (isset($update["message"]["sticker"]["file_id"])) $sticker = $update["message"]["sticker"]["file_id"];
if (isset($update["message"]["animation"]["file_id"])) $animation = $update["message"]["animation"]["file_id"];
if (isset($update["message"]["location"])) $location = $update["message"]["location"];
if (isset($update["message"]["location"]["longitude"])) $longitudine = $update["message"]["location"]["longitude"];
if (isset($update["message"]["location"]["latitude"])) $latitudine = $update["message"]["location"]["latitude"];
if (isset($update["message"]["video"]["file_id"])) $video = $update["message"]["video"]["file_id"];
if (isset($update["message"]["photo"][0]["file_id"])) $photo = $update["message"]["photo"][0]["file_id"];
if (isset($update["message"]["message"]["caption"])) $didascalia = $update["message"]["caption"];
//callback
if ($update["callback_query"]) {
    if (isset($update["callback_query"]["id"])) $cbid = $update["callback_query"]["id"];
    if (isset($update["callback_query"]["data"])) $cbdata = $update["callback_query"]["data"];
    $msg = $cbdata;
    if (isset($update["callback_query"]["message"]["message_id"])) $cbmid = $update["callback_query"]["message"]["message_id"];
    if (isset($update["callback_query"]["message"]["chat"]["id"])) $chatID = $update["callback_query"]["message"]["chat"]["id"];
    if (isset($update["callback_query"]["from"]["id"])) $userID = $update["callback_query"]["from"]["id"];
    if (isset($update["callback_query"]["from"]["first_name"])) $nome = $update["callback_query"]["from"]["first_name"];
    if (isset($update["callback_query"]["from"]["last_name"])) $cognome = $update["callback_query"]["from"]["last_name"];
    if (isset($update["callback_query"]["from"]["username"])) $username = $update["callback_query"]["from"]["username"];
    $fullname = $nome . " " . $cognome;
    if (isset($update["callback_query"]["from"]["language_code"])) $lingua = $update["callback_query"]["from"]["language_code"];
}
if ($update["message"]["reply_to_message"]) {
    if (isset($update["message"]["reply_to_message"]["text"])) $replymsg = $update["message"]["reply_to_message"]["text"];
    if (isset($update["message"]["reply_to_message"]["message_id"])) $replyid = $update["message"]["reply_to_message"]["message_id"];
    if (isset($update["message"]["reply_to_message"]["from"]["id"])) $replyuserid = $update["message"]["reply_to_message"]["from"]["id"];
    if (isset($update["message"]["reply_to_message"]["from"]["first_name"])) $replynome = $update["message"]["reply_to_message"]["from"]["first_name"];
    if (isset($update["message"]["reply_to_message"]["from"]["last_name"])) $replycognome = $update["message"]["reply_to_message"]["from"]["last_name"];
    if (isset($update["message"]["reply_to_message"]["from"]["username"])) $replyusername = $update["message"]["reply_to_message"]["from"]["username"];
    if (isset($update["message"]["reply_to_message"]["forward_from"]["first_name"])) $replyfromnome = $update["message"]["reply_to_message"]["forward_from"]["first_name"];
    if (isset($update["message"]["reply_to_message"]["forward_from"]["last_name"])) $replyfromcognome = $update["message"]["reply_to_message"]["forward_from"]["last_name"];
    if (isset($update["message"]["reply_to_message"]["forward_from"]["username"])) $replyfromusername = $update["message"]["reply_to_message"]["forward_from"]["username"];
    if (isset($update["message"]["reply_to_message"]["forward_from"]["id"])) $replyfromID = $update["message"]["reply_to_message"]["forward_from"]["id"];
}
