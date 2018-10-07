<?php
echo "<br />Variabili";
$isedited    = $update["edited_message"];
$isforwarded = $update["message"]["forward_from"];
$ischannel   = $update["message"]["channel_post"];
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
    $fromnome     = $update["message"]["forward_from"]["first_name"];
    $fromcognome  = $update["message"]["forward_from"]["last_name"];
    $fromusername = $update["message"]["forward_from"]["username"];
    $fromID       = $update["message"]["forward_from"]["id"];
}
if ($isedited && $config["funziona_modificati"]) {
    $update['message'] = $update['edited_message'];
}
$chatID    = $update["message"]["chat"]["id"];
$userID    = $update["message"]["from"]["id"];
$msg       = $update["message"]["text"];
$msgid     = $update["message"]["message_id"];
$isbot     = $update["message"]["from"]["is_bot"];
$nome      = $update["message"]["from"]["first_name"];
$cognome   = $update["message"]["from"]["last_name"];
$fullname  = $nome . " " . $cognome;
$username  = $update["message"]["from"]["username"];
$lingua    = $update["message"]["from"]["language_code"];
$chat_type = $update["message"]["chat"]["type"];
if ($chatID < 0) {
    $titolo       = $update["message"]["chat"]["title"];
    $usernamechat = $update["message"]["chat"]["username"];
}
//media
$audio       = $update["message"]["audio"]["file_id"];
$sticker     = $update["message"]["sticker"]["file_id"];
$animation   = $update["message"]["animation"]["file_id"];
$location    = $update["message"]["location"];
$longitudine = $update["message"]["location"]["longitude"];
$latitudine  = $update["message"]["location"]["latitude"];
$video       = $update["message"]["video"]["file_id"];
$photo       = $update["message"]["photo"][0]["file_id"];
$didascalia  = $update["message"]["caption"];
//callback
if ($update["callback_query"]) {
    $cbid     = $update["callback_query"]["id"];
    $cbdata   = $update["callback_query"]["data"];
    $msg      = $cbdata;
    $cbmid    = $update["callback_query"]["message"]["message_id"];
    $chatID   = $update["callback_query"]["message"]["chat"]["id"];
    $userID   = $update["callback_query"]["from"]["id"];
    $nome     = $update["callback_query"]["from"]["first_name"];
    $cognome  = $update["callback_query"]["from"]["last_name"];
    $username = $update["callback_query"]["from"]["username"];
    $fullname = $nome . " " . $cognome;
    $lingua   = $update["callback_query"]["from"]["language_code"];
}
if ($update["message"]["reply_to_message"]) {
    $replymsg          = $update["message"]["reply_to_message"]["text"];
    $replyid           = $update["message"]["reply_to_message"]["message_id"];
    $replyuserid       = $update["message"]["reply_to_message"]["from"]["id"];
    $replynome         = $update["message"]["reply_to_message"]["from"]["first_name"];
    $replycognome      = $update["message"]["reply_to_message"]["from"]["last_name"];
    $replyusername     = $update["message"]["reply_to_message"]["from"]["username"];
    $replyfromnome     = $update["message"]["reply_to_message"]["forward_from"]["first_name"];
    $replyfromcognome  = $update["message"]["reply_to_message"]["forward_from"]["last_name"];
    $replyfromusername = $update["message"]["reply_to_message"]["forward_from"]["username"];
    $replyfromID       = $update["message"]["reply_to_message"]["forward_from"]["id"];
}
$isadmin = in_array($chatID, $admin); //Restituisce true se la chat è admin
