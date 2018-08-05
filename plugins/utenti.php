<?php
echo "<br>Plugin Utenti 3.0";
//iscritti
if ($config["db"]) {
	if ($config['tipo_db'] == "json"){
if(strpos($msg, "/iscritti")===0 and $isadmin ){
$private = 0;
$gruppi = 0;
$usr= 0;
$tot = count($dbcontent);
foreach ($dbcontent as $us) {
if ($us["chat_id"] > 0 && $us["page"] !== "group") {
	$private++;
} elseif ($us["page"] == "group") {
	$usr++;
} elseif ($us["chat_id"] < 0) {
	$gruppi++;
}
	
}

$iscritti = "*👤ISCRITTI AL BOT*";
$iscritti .= "\n   👤Chat Private: $private";
$iscritti .= "\n   👥Chat Gruppi: $gruppi";

$iscritti.= "\n\n*👥UTENTI SUI GRUPPI*";
$iscritti .= "\n 👤Utenti: $usr";
$iscritti .= "\n 👤Utenti totali: $tot";
sm($chatID, $iscritti,false,false,"markdown");
}
if(strpos($msg, "/post")===0 and $isadmin)
{
$t = array(array(array(
"text" => "👤 Utenti",
"callback_data" => "/2post 1"
),
array(
"text" => "Gruppi 👥",
"callback_data" => "/2post 2"
)),
array(array(
"text" => "👤 Utenti e Gruppi 👥",
"callback_data" => "/2post 3"
)));

sm($chatID, "Ok, dove vuoi inviare il messaggio globale?

_Se selezioni gruppi, invia anche nei canali conosciuti._", $t, "inline", 'Markdown');
}

if(strpos($msg, "/2post")===0 and $isadmin)
{
$campo = explode(" ", $msg);
$dbcontent[$chatID]["page"] = "post $campo[1]";
jsonsave();
$t = array(array(array(
"text" => " Annulla",
"callback_data" => "/apostannulla"
)));

cb_reply($cbid, "Ok!", false, $cbmid, "Ok $nome, invia ora il post globale che vuoi inviare.
Formattazione: ".$config['parse_mode'], $t);

}

if(strpos($msg, "/apostannulla")===0 and $isadmin)
{
cb_reply($cbid, "Ok!", false, $cbmid, "Invio Post annullato");
$dbcontent[$chatID]["page"] = "";
jsonsave();
exit;
}

if(strpos($dbcontent[$chatID]['page'], "post")===0)
{
if($msg)
{
//eseguo
$s = explode(" ",$dbcontent[$chatID]['page']);
$achi = $s[1];
sm($chatID, "Post in viaggio verso gli utenti.");

//salvo post in file
$file = "lastpost.json";
$f2 = fopen($file, 'w');
fwrite($f2, $msg);
fclose($f2);


//invio
foreach ($dbcontent as $usr) {
if($achi == 1) if($usr["chat_id"] > 0) sm($usr["chat_id"], $msg, false, false,$config['parse_mode']) ;
if($achi == 2) if($usr["chat_id"] < 0) sm($usr["chat_id"], $msg, false, false,$config['parse_mode']) ;
if($achi == 3) sm($usr["chat_id"], $msg, false, false,$config['parse_mode']) ;
}



}else{
sm($chatID, "Solo messaggi testuali.");
}
}



//ban unban dal bot

if(strpos($msg, "/ban ")===0 and $isadmin)
{
$campo = explode(" ", $msg);
if (stripos($campo[1], "@")===0) {
$id = id($campo[1]);
} else {
$id = $campo[1];
}
$dbcontent[$id]["page"] = "ban";
jsonsave();
sm($chatID, "Ho bannato $campo[1] dal bot");
}
if(strpos($msg, "/unban ")===0 and $isadmin)
{
if (stripos($campo[1], "@")===0) {
$id = id($campo[1]);
} else {
$id = $campo[1];
}
$dbcontent["$id"]["page"] = "";
jsonsave();
sm($chatID, "Ho bannato $campo[1] dal bot");
}

	
} elseif ($config['tipo_db'] == "mysql") {
if(strpos($msg, "/iscritti")===0 and $isadmin )
{

$qcp = $db->query("select * from $tabella where not page = 'disable' and not page='group' and chat_id>0");
$qcg = $db->query("select * from $tabella where not page = 'disable' and chat_id<0");
$cp = $qcp->rowCount();
$cg = $qcg->rowCount();

//morti
$mqcp = $db->query("select * from $tabella where page = 'disable' and chat_id>0");
$mqcg = $db->query("select * from $tabella where page = 'disable' and chat_id<0");
$mcp = $mqcp->rowCount();
$mcg = $mqcg->rowCount();

//utenti

$gr = $db->query("select * from $tabella where page = 'group' and chat_id>0");
$gru = $gr -> rowCount();

$iscritti = "*👤ISCRITTI AL BOT*";
$iscritti .= "\n   👤Chat Private: $cp";
$iscritti .= "\n   👥Chat Gruppi: $cg";

$iscritti .= "\n\n*🔇MORTI*";
$iscritti .= "\n   👤Chat Private: $mcp";
$iscritti .= "\n   👥Chat Gruppi: $mcg";

$iscritti.= "\n\n*👥UTENTI SUI GRUPPI*";
$iscritti .= "\n 👤Utenti: $gru
";
sm($chatID, $iscritti,false,false,"markdown");
}
//post globali


if(strpos($msg, "/post")===0 and $isadmin)
{
$t = array(array(array(
"text" => "👤 Utenti",
"callback_data" => "/2post 1"
),
array(
"text" => "Gruppi 👥",
"callback_data" => "/2post 2"
)),
array(array(
"text" => "👤 Utenti e Gruppi 👥",
"callback_data" => "/2post 3"
)));

sm($chatID, "Ok, dove vuoi inviare il messaggio globale?

_Se selezioni gruppi, invia anche nei canali conosciuti._", $t, "inline", 'Markdown');
}

if(strpos($msg, "/2post")===0 and $isadmin)
{
$campo = explode(" ", $msg);
$db->query("update $tabella set page = 'post $campo[1]' where chat_id = $chatID");

$t = array(array(array(
"text" => " Annulla",
"callback_data" => "/apostannulla"
)));

cb_reply($cbid, "Ok!", false, $cbmid, "Ok $nome, invia ora il post globale che vuoi inviare.
Formattazione: ".$config['parse_mode'], $t);

}

if(strpos($msg, "/apostannulla")===0 and $isadmin)
{
cb_reply($cbid, "Ok!", false, $cbmid, "Invio Post annullato");
$db->query("update $tabella set page = '' where chat_id = $chatID");
exit;
}
if(strpos($u['page'], "post")===0)
{
if($msg)
{
//eseguo
$s = explode(" ",$u['page']);
$achi = $s[1];
$db->query("update $tabella set page = '' where chat_id = $chatID");
if($achi == 1) $q = "where chat_id>0 and not page='group'";
if($achi == 2) $q = "where chat_id<0 and not page='group'";
if($achi == 3) $q = " where 1 and not page='group'";
$query = $db->query("SELECT * FROM $tabella $q");
$c = $query->rowCount();
sm($chatID, "Post in viaggio verso $c utenti.");
$tot = 0;
foreach ($query as $user) {
	$tot++;
	$id = $user["chat_id"];
	sm($id, $msg);
	if ($tot > $c) { //Misura di sicurezza, se vi dà problemi togliete questo if.
		sm($chatID, "Invio completato, post inviato a $tot utenti.");
		exit;
	}
}
sm($chatID, "Invio completato, post inviato a $tot utenti.");

}else{
sm($chatID, "Solo messaggi testuali.");
}
}

//ban unban dal bot

if(strpos($msg, "/ban ")===0 and $isadmin)
{
$campo = explode(" ", $msg);
if (stripos($campo[1], "@")===0) {
$id = id($campo[1]);
} else {
$id = $campo[1];
}
$db->query("update $tabella set page = 'ban' where chat_id = $id");
sm($chatID, "Ho bannato $campo[1] dal bot");
}
if(strpos($msg, "/unban ")===0 and $isadmin)
{
if (stripos($campo[1], "@")===0) {
$id = id($campo[1]);
} else {
$id = $campo[1];
}
$db->query("update $tabella set page = '' where chat_id = $id");
}
}
}





