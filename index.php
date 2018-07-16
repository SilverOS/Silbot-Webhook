<?php
echo '<h1 align="center">SilverOSBase v 0.9 </h1>';
$token = $_GET['api'];
$api = $token;
$idadmin = $_GET['admin'];
$adminID = $idadmin;
$userbot = $_GET['userbot'];
$admin = array( //lista admin
$adminID
);
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$config = array(
"db" => true, // true per usare un database mysql, false per non usarlo
"tipo_db" => "json", //"mysql" per un database mysql, "json" per un database attraverso file json
//MYSQL
"altervista" => false, //true se usi altervista, false se non lo usi
"ip" => "localhost", // se non usi altervista metti l'indirizzo del database, di norma localhost se è hostato sullo stesso server
"user" => "root", //se non usi altervista inserisci il nome utente del DB
"password" => "psw", //se non usi altervista inserisci la password di mysql
"database" => "sdb", //se non usi altervista inserisci il nome del database
//TELEGRAM
"action" => true, //true per mandare azioni come typing... e false per non mandare nulla
"parse_mode"=> "HTML" ,//Formattazione presefinita messaggio, HTML, Markdown o none
"disabilitapreview" => false, //False per permettere il web preview, true per disabilitarla
"tastiera" => "inline" ,//Tastiera preferita, inline per quella inline e reply per la replykeyboard
"funziona_modificati" => true, //Scegli se far eseguire i messaggi modificati
"funziona_inoltrati" => false, //Scegli se far eseguire i messaggi inoltrati
);
include ("vars.php");
include("functions.php");
if ($config['db']){
include("database.php");
}
include("comandi.php");
$plugins = scandir("plugins"); unset($plugins[0]); unset($plugins[1]);
$disabled = array("pluginno.php"); //Qui và la lista dei plugin disabilitati
foreach($plugins as $plug) {
	if (!in_array($plug, $disabled)) {
		include("plugins/$plug");
		}
}
