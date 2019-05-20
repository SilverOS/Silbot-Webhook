<?php
echo '<h1 align="center">SilBot X v 1.0 </h1>';
if (isset($_GET['api'])) $token   = $_GET['api'];
$api     = $token;
if (isset($_GET['userbot']))$userbot = $_GET['userbot'];

$content = file_get_contents("php://input");
$update  = json_decode($content, true);
$config  = array(
    "db" => true, // true per usare un database mysql, false per non usarlo
    // Sicurezza
    "automatic_username_check" => true, //Controlla automaticamente se l'username del bot è corretto,per migliori prestazioni è consigliato disabiltarlo e abilitare bot_username.
    "bot_username" => array( //Per maggiore sicurezza inserisci gli username dei tuoi bot che sono collegati al webhook,ciò eviterà cloni
        "allowed_usernames" => array("Silbot","Sil2bot"), //username dei vari bot,SONO CASE-SENSITIVE
        "active" => false, //Se lo abiliti i cloni non saranno possibili se non presenti nell'array sopra,
    ),
    "ip_check" => array( //NON FUNZIONA CON CLOUDFLARE Evita delle richieste indesiderate da esterni
      "whitelist" => array(), //Inserisci gli ip che potranno fare richieste oltre a quelli di Telegram
      "enabled" => false, //Consiglio: Disabilita se sei in testing o stai impostando il webhook con @devtoolsforbot
    ),
    //MYSQL
    "ip" => "localhost", // se non usi altervista metti l'indirizzo del database, di norma localhost se è hostato sullo stesso server
    "user" => "root", //se non usi altervista inserisci il nome utente del DB
    "password" => "psw", //se non usi altervista inserisci la password di mysql
    "database" => "db", //se non usi altervista inserisci il nome del database
    //TELEGRAM
    "action" => false, //true per mandare azioni come typing... e false per non mandare nulla
    "parse_mode" => "HTML", //Formattazione presefinita messaggio, HTML, Markdown o none
    "disabilitapreview" => false, //False per permettere il web preview, true per disabilitarla
    "tastiera" => "inline", //Tastiera preferita, inline per quella inline e reply per la replykeyboard
    "funziona_modificati" => true, //Scegli se far eseguire i messaggi modificati
    "funziona_inoltrati" => false, //Scegli se far eseguire i messaggi inoltrati
    "funziona_canali" => true //Scegli se far eseguire i messaggi inviati dai canali
);

if ($config["automatic_username_check"] && isset($userbot)) {
    $info = json_decode(file_get_contents("http://api.telegram.org/$api/getMe"), true);
    if ($info["result"]["username"] != $userbot) {
        exit;
    }
}
if ($config["bot_username"]["active"] && isset($userbot)) {
    if (!in_array($userbot,$config["bot_username"]["allowed_usernames"])) {
        exit;
    }
}
if ($config["ip_check"]["active"] && isset($token)) {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    if (stripos($_SERVER['REMOTE_ADDR'],'149.154.167')!==false) {
    } else {
        if (!in_array($_SERVER['REMOTE_ADDR'],$config["ip_check"]["whitelist"])) {
            exit;
        }
    }
}
if (isset($_GET["api"])) {
    include("vars.php");
    include("functions.php");
    if ($config['db']) {
        include("database.php");
    }
    include("comandi.php");
}
