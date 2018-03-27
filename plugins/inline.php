<?php

echo "<br>Plugin Inline: 2.0";

if($update["inline_query"])
{
$inline = $update["inline_query"]["id"];
$msg = $update["inline_query"]["query"];
$userID = $update["inline_query"]["from"]["id"];
$username = $update["inline_query"]["from"]["username"];
$name = $update["inline_query"]["from"]["first_name"];




$json = array(
//prima riga risultati
array(
'type' => 'article',
'id' => 'kakfieokakfieofo',
'title' => 'Invia messaggio...',
'description' => "Premi qui 1",
'message_text' => "Questo appare: testo 1",
'parse_mode' => 'Markdown'
),
//seconda riga risultati
array(
'type' => 'article',
'id' => 'alalalalalalala',
'title' => 'Invia messaggio...',
'description' => "Premi qui 2",
'message_text' => "Questo appare: testo 2",
'parse_mode' => 'Markdown'
),
//altre righe eventuali
//InlineQueryResultPhoto
array(
  'type' => 'article',
  'id' => 'abababababababab',
  'photo_file_id' => 'file_id',
  'title' => 'Fotoz',
  'description' => 'Sono una foto',
  'caption' => 'Foto con una fottuta descrizione, sus',
  'input_message_content' => 'Ah, errore, opz',
),
//InlineQueryResultGif
array(
  'type' => 'gif',
  'id' => 'lolololololololo',
  'gif_url' => 'https://media.giphy.com/media/m1c0Faa7ZHrTG/giphy.gif',
  'gif_width' => 200,
  'gif_height' => 150,
  'gif_duration' => 5,
  'thumb_url' => 'https://image.prntscr.com/image/XB82-PmhQlur2L1y4-0PYQ.png',
  'title' => 'Kirito :3',
  'caption' => '(ᵔᴥᵔ)',
  'input_message_content' => 'Ah, errore, opz',
),
//InlineQueryResultVideo
array(
  'type' => 'video',
  'id' => 'vivivivivivivivi',
  'video_url' => 'http://justbotm8.altervista.org/Pollsciemo_esplode.mp4',
  'mime_type' => 'text/html',
  'thumb_url' => 'https://image.prntscr.com/image/peGY_39dQu2zeqi1g4iltA.png',
  'title' => 'Legends never die',
  'caption' => 'When the world is calling you',
  'video_width' => 200,
  'video_height' => 150,
  'video_duration' => 202,
  'description' => 'omg',
  'input_message_content' => 'lul',
),
//InlineQueryResultAudio
array(
  'type' => 'audio',
  'id' => 'auauauauauauauau',
  'audio_url' => 'http://justbotm8.altervista.org/Logic_-_My_Chain__Prod._6ix_.mp3',
  'title' => 'Logic o.o',
  'caption' => 'Uau',
  'audio_duration' => 289,
  'input_message_content' => 'lul',
),
);




$json = json_encode($json);
$args = array(
'inline_query_id' => $inline,
'results' => $json,
'cache_time' => 5
);
$r = sr("answerInlineQuery", $args);

}
