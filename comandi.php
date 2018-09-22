<?php
if ($msg == "/start") {
if ($cbdata) {
cb_reply($cbid, "Ok", false, $cbmid, "Silbot v1.1
Usa:
/reply per un esempio di tastiera reply
/inline per un esempio di tastiera inline");
} else {
sm($chatID, "Silbot v1.1
Usa:
/reply per un esempio di tastiera reply
/inline per un esempio di tastiera inline");
}
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
$menu[] = array(
array("text" => "Toena indietro",
"callback_data" => "/start"),
);
cb_reply($cbid, "Ok", false, $cbmid, "Messaggio Modificato",$menu);
}
