<?php
echo "<br />Funzioni";

function sr($method, $args){
global $token;
$query = http_build_query($args);
	file_get_contents("http://api.telegram.org/$token/$method?".$query);
}
function action($chatID, $action) {
$args = array(
"chat_id" => $chatID,
"action" => $action,
);
sr("sendChatAction", $args);
}
function sm($chatID, $msg, $menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false, $disablewebpreview = false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	if (!$disablewebpreview) {
	$disablewebpreview = $config['disabilitapreview'];
		}
	$args = array(
	"chat_id" => $chatID,
	"text" => $msg,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"disable_web_page_preview" => false,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendMessage", $args);
	}
function em($chatID, $msg, $msgid, $menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false, $disablewebpreview = false) {
global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	if (!$disablewebpreview) {
	$disablewebpreview = $config['disabilitapreview'];
		}
	$args = array(
	"chat_id" => $chatID,
	"text" => $msg,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"disable_web_page_preview" => $disablewebpreview,
   "message_id" => $msgid,
	);
	sr("sendMessage", $args);
}
function cb_reply($id, $text, $alert = false, $cbmid = false, $ntext = false, $nmenu = false, $npm = "pred")
{
global $api;
global $chatID;
global $config;
if($npm == 'pred') $npm = $config['parse_mode'];
$args = array(
'callback_query_id' => $id,
'text' => $text,
'show_alert' => $alert
);
$r = sr("answerCallbackQuery", $args);
if($cbmid)
{
if($nmenu)
{
$rm = array('inline_keyboard' => $nmenu
);
$rm = json_encode($rm);
}
$args = array(
'chat_id' => $chatID,
'message_id' => $cbmid,
'text' => $ntext,
'parse_mode' => $npm,
);
if($nmenu) $args["reply_markup"] = $rm;
$r = sr("editMessageText", $args);
}
}
//sendPhoto
function si($chatID, $image, $caption = false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"photo" => $image,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"caption" => $caption,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendPhoto", $args);
	}
function sa($chatID, $audio, $caption = false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false, $autore = "false", $titolo = "false") {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"audio" => $audio,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"caption" => $caption,
	"title" => $titolo,
	"performer" => $autore,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendAudio", $args);
	}
function sd($chatID, $document, $caption = false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"document" => $document,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"caption" => $caption,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendDocument", $args);
	}
	//sendVideo
function sv($chatID, $video, $caption = false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"video" => $video,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"caption" => $caption,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendVideo", $args);
	}
//sendVoice
function svc($chatID, $voice, $caption = false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"voice" => $voice,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"caption" => $caption,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendVoice", $args);
	}
//sendSticker
function ss($chatID, $sticker,$menu= false, $keyboardtype = false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
	$args = array(
	"chat_id" => $chatID,
	"sticker" => $sticker,
	"reply_to_message_id" => $reply_to_message,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendSticker", $args);
	}
//sendVideoNote
	function svn($chatID, $video_note ,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"video_note" => $video_note,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendVideoNote", $args);
	}
//sendLocation
function sl($chatID, $latitude,$longitude,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false, $live_period = false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"latitude" => $latitude,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	"longitude" => $lungitude,
	"live_period" => $live_period,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendLocation", $args);
	}
function sc($chatID, $phone_number, $first_name, $last_name=false,$menu= false, $keyboardtype = false, $parse_mode=false, $reply_to_message=false) {
	global $token;
	global $config;
if (!$keyboardtype && $menu) {
$keyboardtype = $config['tastiera'];
}
if ($keyboardtype == "reply") {
	$rm = array('keyboard' => $menu,
'resize_keyboard' => true
);
} elseif ($keyboardtype == "inline") {
$rm = array('inline_keyboard' => $menu,
);
} elseif ($keyboardtype == "nascondi") {

$rm = array('hide_keyboard' => true
);
}
$rm = json_encode($rm);
	
   if (!$parse_mode) {
	$parse_mode = $config['parse_mode'];
	}
	$args = array(
	"chat_id" => $chatID,
	"phone_number" => $phone_number,
	"first_name" => $first_name,
	"last_name" => $last_name,
	"parse_mode" => $parse_mode,
	"reply_to_message_id" => $reply_to_message,
	);
if($menu) $args['reply_markup'] = $rm;
	if ($config['action']) {
		action($chatID, "typing");
		}
	sr("sendContact", $args);
	}

//GRUPPI
function deleteChatPhoto($chatID){
global $token;
$args = array(
"chat_id" => $chatID,
);
sr("deleteChatPhoto", $args);
}
function ban($chatID, $userID, $time=0)
{
global $api;
$args = array(
'chat_id' => $chatID,
'user_id' => $userID,
'until_date' => $time,
);
sr("kickChatMember", $args);
}
function unban($chatID, $userID)
{
global $api;
$args = array(
'chat_id' => $chatID,
'user_id' => $userID
);
sr("unbanChatMember", $args);
}
//fissa
function fissa($chatID, $msgid)
{
global $api;
$args = array(
'chat_id' => $chatID,
'message_id' => $msgid,
);
sr("pinChatMessage", $args);
}
function limita($chatID, $userID, $dateRelase, $sendMsg, $sendMedia, $sendOther, $WPPreview){
global $token;
$args = array(
"chat_id" => $chatID,
"user_id" => $userID,
"until_date" => $dateRelase,
"can_send_messages" => $sendMsg,
"can_send_media_messages" => $sendMedia,
"can_send_other_messages" => $sendOther,
"can_add_web_page_previews" => $WPPreview
);
sr("restrictChatMembers", $args);
}
function promoteChatMembers($chatID, $userID, $changeInfo, $postMsg, $modifyMsg, $deleteMsg, $inviteUsers, $restrictUsers, $pinMsg, $promoteUsers ){
global $token;
$args = array(
"chat_id" => $chatID,
"user_id" => $userID,
"can_change_info" => $changeInfo,
"can_post_messages" => $postMsg,
"can_edit_messages" => $modifyMsg,
"can_delete_messages" => $deleteMsg,
"can_invite_users" => $inviteUsers,
"can_restrict_members" => $restrictUsers,
"can_pin_messages" => $pinMsg,
"can_promote_members" => $promoteUsers
);
sr("promoteChatMembers", $args);
}
function getlink($chatID){
global $token;
$args = array(
"chat_id" => $chatID,
);
sr("exportChatInviteLink", $args);
}
if ($config['db']){
function id($username) {
	global $userbot;
   global $db;
$username = str_replace("@", "", $username);
$q = $db->query("select * from `$userbot` where username = '" . $username ."'");
$u = $q->fetch(PDO::FETCH_ASSOC);
return $u['chat_id'];
}
function username($id) {
	global $userbot;
   global $db;
$q = $db->query("select * from `$userbot` where chat_id = $id");
$u = $q->fetch(PDO::FETCH_ASSOC);
return $u['username'];
}
}