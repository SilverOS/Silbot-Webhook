<?php
if ($msg == "/start") {
sm($chatID, "Silbot v1.0
Usa:
/reply per un esempio di tastiera reply
/inline per un esempio di tastiera inline " . id($username));
}
if ($msg == "/reply") {
$menu[] = array("voce 1");
$menu[] = array("voce 2", "voce 3");
$menu[] = array("nascondimi");
sm($chatID , "Testiera reply", $menu, "reply");
}
if ($msg == "nascondimi") {
sm($chatID , "Testiera nascosta", true, "nascondi");
}
if ($msg == "/inline") {
$menu[] = array(
array("text" => "Ciao",
"callback_data" => "test"),
);
sm($chatID, "Tastiera Inline", $menu, "inline");
}
if ($cbdata == "test") {
cb_reply($cbid, "NOTIFICA TIPO 1", false, $cbmid, "Messaggio Modificato");
}
