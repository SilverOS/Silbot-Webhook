<?php
// Standard Silbot PHP plugin to manage inline query
// This is loaded before commands.php
if ($bot) {
    if ($update->type == 'inline_query' && isset($inline)) {
        $results = [
            [
                'type' => 'article',
                'id' => 1,
                'title' => 'Send HTML formatted text',
                'description' => "Write an html text and I will parse it",
                'message_text' => $inline->query,
                'parse_mode' => 'html'
            ],

        ];
        $bot->answerInlineQuery($inline->id,$results);
    }
}
