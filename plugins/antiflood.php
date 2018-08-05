<?php
echo "<br />Plugin Antiflood mysql 1.0";

if($update && !$cbdata && $config["antiflood"]){
	$tabellafl = $tabella. "antiflood"; //Nome tabella antiflood
	$maxtime = 2; // Numero massimo di secondi
	$maxmsg = 5; //Numero massimo di messaggi inviabili entro $maxtime
	$unix = time();
	$db->query("CREATE TABLE IF NOT EXISTS $tabellafl (
	id int(0) AUTO_INCREMENT,
	chat_id int(32),
	time int(20),
    number int (20),
	PRIMARY KEY (id))");  
	$q = $db->query("select * from $tabellafl where chat_id = $chatID"); 
	if(!$q->rowCount())
	{
		$db->query("insert into $tabellafl (chat_id, time, number) values ($chatID, $unix, 0)");
	}
    $f = $q->fetch(PDO::FETCH_ASSOC);
    $lasttime = $f['time'];
    $number = $f['number'];
	$tempotrascorso = $unix - $f["time"];
	
	if ($tempotrascorso <= $maxtime) {
		$number++;
		if ($number >= $maxmsg) {
			$db->query("update $tabella set page = 'ban' where chat_id=$chatID");
						$db->query("update $tabellafl set number = 0 where chat_id=$chatID");
			sm($chatID, "Sei stato bannato per flood");
		} else {
			$db->query("update $tabellafl set number = $number where chat_id=$chatID");
			
		}
		
	} else {
	     $db->query("update $tabellafl set time = $unix where chat_id=$chatID");
	}

}





















