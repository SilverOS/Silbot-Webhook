<?php

/*
PLUGIN GRUPPI
Versione 3.5
*/
echo "<br>Plugin Gruppi: 3.5";

if ($chatID < 0)
$replyID = $update["message"]["reply_to_message"]["from"]["id"];
$replyNome = $update["message"]["reply_to_message"]["from"]["first_name"];
$rmsgID = $update["message"]["reply_to_message"][message_id];

//lasciare questo codice immutato
$args = array(
'chat_id' => $chatID
);
$add = sr("getChatAdministrators", $args);
$admins = json_decode($add, true);
foreach($admins['result'] as $adminsa)
{
if($adminsa['user']['id'] == $userID)
$isadmin = true;

if($adminsa["user"]["id"] == $userID and $adminsa["status"]=="creator")
$isfounder=true;
}


/*
Nelle condizioni if sarà possibile mettere 
$isadmin per verificare che solo gli Admin
possano usare tale comando.

esempio

if(strpos(....) and $isadmin)
{
sm();
//altri comandi
}
*/
//lista Admin
if(strpos(" ".$msg, "/admins"))
{
$shish = "Admin:";
foreach($admins[result] as $ala)
{
if($ala[status] == "creator")
{
$shish .= "
@".$ala[user][username]." [FONDATORE]";
}else{
$shish .= "
@".$ala[user][username];
}
}
sm($chatID, $shish);
}




if($update["message"]["new_chat_member"])
{
$nome = $update["message"]["new_chat_member"]["first_name"];
$username = $update["message"]["new_chat_member"]["username"];
$id = $update["message"]["new_chat_member"]["id"];

$text = "Ciao $nome @$username $id, benvenuto nel gruppo. Per vedere le regole premi /regole";
sm($chatID, $text);
}


if($update["message"]["left_chat_member"])
{
$nome = $update["message"]["left_chat_member"]["first_name"];
$username = $update["message"]["left_chat_member"]["username"];
$id = $update["message"]["left_chat_member"]["id"];

$text = "Arrivederci $nome @$username $id.";
sm($chatID, $text);
}

if($update["message"]["new_chat_title"])
{
$nuovo_nome = $update["message"]["new_chat_title"];

$text = "Nuovo nome gruppo: $nuovo_nome";
sm($chatID, $text);
}

if ($msg == "/membri") {
	sm($chatID, membri($chatID));
	}
if ($msg == "/link") {
sm($chatID, getlink($chatID));
}
//Il tempo va espresso in format YYYY-MM-DD HH:mm:ss esempio: 2018-01-01 21:14:00
if(strpos($msg, "/ban")===0 and $isadmin)
{
	$arg = explode(" ", $msg, 2);
$unix = strtotime("$arg[1]");
if($replyID)
{
if ($arg[1]) {
	sm($chatID, "Ho bannato $replyNome fino al $arg[1]");
	ban($chatID, $replyID, $unix);
} else {
sm($chatID, "Ho bannato $replyNome.");
ban($chatID, $replyID);
}
} else {
$id = id($arg[1]);
$unix = strtotime("$arg[2]");
sm($chatID, "Ho bannato $arg[1] $id per $arg[2]");
ban($chatID, $id, $unix);
}
}
//Il tempo va espresso in format YYYY-MM-DD HH:mm:ss esempio: 2018-01-01 21:14:00
if(strpos($msg, "/muta")===0 and $isadmin)
{
	$arg = explode(" ", $msg, 2);
$unix = strtotime("$arg[1]");
if($replyID)
{
if ($arg[1]) {
	sm($chatID, "Ho bannato $replyNome fino al $arg[1]");
 limita($chatID, $replyID, $unix, false,false,false,false);
} else {
sm($chatID, "Ho bannato $replyNome.");
limita($chatID, $replyID,0,false,false,false,false);
}
} else {
$id = id($arg[1]);
$unix = strtotime("$arg[2]");
sm($chatID, "Ho bannato $arg[1] $id per $arg[2]");
limita($chatID, $id, $unix,false,false,false,false);
}
}
if ($msg == "/unmuta") {
if($replyID)
{
sm($chatID, "Ho unmuta $replyNome.");
limita($chatID, $replyID);
}
}

if(strpos($msg, "/kick")===0 and $isadmin)
{
if($replyID)
{
sm($chatID, "Ho kickato $replyNome.");
ban($chatID, $replyID);
unban($chatID, $replyID);
}
}

if(strpos($msg, "/unban")===0 and $isadmin)
{
if($replyID)
{
sm($chatID, "Ho sbannato $replyNome.");
unban($chatID, $replyID);
}
}
if (stripos($msg, "/fissa")===0) {
fissa($chatID,$rmsgID);
}




?>