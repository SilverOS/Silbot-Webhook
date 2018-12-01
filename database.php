<?php

echo "<br />Database";
if (!isset($config)) {
    exit;
}
$tabella = $userbot;
$db = new PDO("mysql:host=" . $config["ip"] . ";dbname=" . $config['database'], $config['user'], $config['password']);
if ($_GET["install"]) {
    $install = $db->prepare('CREATE TABLE IF NOT EXISTS ' . $tabella . ' (
chat_id bigint(0),
username varchar(200),
state varchar(200),
PRIMARY KEY (chat_id))');
    $install->execute();
    echo "Database installato";
}
    if ($chatID < 0) {
        $q = $db->prepare("select * from ? where chat_id = ? LIMIT 1");
        $q->execute([$tabella,$chatID]);
        if (!$q->rowCount()) {
            $db->prepare("insert into ? (chat_id, page, username) values (?, '','?')")->execute([$tabella,$chatID,$username]);
        }
    }
    if ($userID) {
        $q = $db->prepare("select * from ? where chat_id = ? LIMIT 1");
        $q->execute([$tabella,$chatID]);
        if (!$q->rowCount()) {
            if ($userID == $chatID) {
                $db->prepare("insert into ? (chat_id, state, username) values (?, '','?')")->execute([$tabella,$chatID,$username]);
            } else {
                $db->prepare("insert into ? (chat_id, page, username) values (?, 'group', '?')")->execute([$tabella,$chatID,$username]);
            }
        } else {
            $u = $q->fetch(PDO::FETCH_ASSOC);
            
            if ($u['state'] == "group" && $chatID > 0) {
                $db->prepare("update ? set page = '' where chat_id = ? LIMIT 1")->execute([$tabella,$chatID]);
            }
        }
    }
