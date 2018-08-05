<?php

echo "<br />Database";


if($_GET['install'] and $_GET['userbot'])
{
if ($config['tipo_db'] == "json") {
	touch("database.json");
	
	
} elseif ($config['tipo_db'] == "mysql") {
if ($config['altervista']) {
$url = explode(".",$_SERVER["HTTP_HOST"]);
$dir = dirname($_SERVER["PHP_SELF"]);
$dir = substr($dir,1);
$dbh = new PDO("mysql:host=localhost;dbname=my_".$url[0], $url[0], ""); 
$dbh->query("CREATE TABLE IF NOT EXISTS ".$_GET['userbot']." (
id int(0) AUTO_INCREMENT,
chat_id bigint(0),
username varchar(200),
page varchar(200),
PRIMARY KEY (id))");
echo "<br>HO INSTALLATO IL DATABASE";
} else {
$dbh = new PDO("mysql:host=" . $config["ip"] . ";dbname=".$config['database'], $config['user'], $config['password']); 
$dbh->query("CREATE TABLE IF NOT EXISTS ".$_GET['userbot']." (
id int(0) AUTO_INCREMENT,
chat_id bigint(0),
username varchar(200),
page varchar(200),
PRIMARY KEY (id))");
echo "<br>HO INSTALLATO IL DATABASE";
}

}
}
if ($config['tipo_db'] == "json"){
	$dbcontent = json_decode(file_get_contents("database.json"), true);
	if (!in_array($chatID, $dbcontent)) {
		if ($chatID == $userID) {
		$dbcontent[$chatID] = array(
		"chat_id" => $chatID,
		"username" => "$username",
		"page" => "",
		);
		} else {
		$dbcontent[$chatID] = array(
		"chat_id" => $chatID,
		"username" => "$usernamechat",
		"page" => "",
		);
		if (!in_array($userID, $dbcontent)) {
		$dbcontent[$userID] = array(
		"chat_id" => $userID,
		"username" => "$username",
		"page" => "group",
		);
		}
		}
	} else {
		if ($dbcontent[$chatID]["page"] == "ban") {
			sm($chatID, "Sei bannato dall'utilizzo del Bot.");
exit;
		}
	}
file_put_contents("database.json", json_encode($dbcontent));
} elseif ($config['tipo_db'] == "mysql") {
if ($config['altervista']){
	$url = explode(".",$_SERVER["HTTP_HOST"]);
$dir = dirname($_SERVER["PHP_SELF"]);
$dir = substr($dir,1);
$db  = new PDO("mysql:host=localhost;dbname=my_".$url[0], $url[0], ""); 
} else {
$db = new PDO("mysql:host=" . $config["ip"] . ";dbname=".$config['database'], $config['user'], $config['password']); 
}

$tabella = $userbot;
if ($chatID < 0) {
$q = $db->query("select * from $tabella where chat_id = $chatID");
if(!$q->rowCount())
{
$db->query("insert into `$tabella` (chat_id, page, username) values ($chatID, ''," . '"'. $usernamechat.'"'.")");
}
}
if($userID)
{
$q = $db->query("select * from $tabella where chat_id = $userID");
if(!$q->rowCount())
{
if ($userID == $chatID) {
$db->query("insert into `$tabella` (chat_id, page, username) values ($chatID, ''," . '"'. $username.'"'.")");
} else {
$db->query("insert into `$tabella` (chat_id, page, username) values ($userID, 'group'," . '"'. $username.'"'.")");
}
}else{
$u = $q->fetch(PDO::FETCH_ASSOC);

if($u['page'] == "disable")
{
$db->query("update $tabella set page = '' where chat_id = $chatID");
}
if($u['page'] == "ban")
{
sm($chatID, "Sei bannato dall'utilizzo del Bot.");
exit;
}
}
}
}
