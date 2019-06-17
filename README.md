# SilBot Beta

A new version based on OOP programming in PHP 7 with MySQL integration.
- - -
# Webhook

To set the bot's webhook you have to do a setWebhook request to the botAPI using this format:
<code>https://api.telegram.org/botTOKEN/setWebhook?url=https://your.server.com/path/index.php?token=TOKEN</code>

You can also use <a href='http://t.me/devtoolsforbot'>@DevToolsForBot</a> using Imposta Webhook-> Webhook Standard and writing first the token and next the url to your server in format <code>https://your.server.com/path/index.php?token=TOKEN</code>
- - -
# How it works

In the file <code>functions/botapi.php</code> there is the botApi class, where there are the functions to make requests to the botapi. In index.php is defined in <code>$bot</code>, so to send a message you can use <code>$bot->sendMessage('Chat_ID','Text');</code>.

Then, there is the file <code>functions/update.php</code> whose purpose is to set variables, sort the update and, if set, save users in the database. The variables are set dinamically, the objects which do this are in <code>functions/objects.php</code>.

In conclusion, index.php make same important variables easier tu write and execute commands.php, where the bot's code should be written.
- - -
# Config

In the <code>config.php</code> file you will find an array, $config, where you can set pre-defined values for some botapi variables if not included in the function, such as <code>disable_notification</code>. You can also set if you want a MySQL database.
- - -
# MySQL

To use this you have to set database->active on true and insert your credentials in the config. After that are asked the names of two tables:
- universal_table => Created to work with more than one bot, there will be saved all the users and chats the bot see with Telegram information, even with forwarded messages or reply. The table contains 4 coloumns: <code>chat_id,username,lang,type</code>
- bot_table => Where the bot's users are saved, by default it only contains two coloumns: <code>chat_id, state</code>. This is made to do stuff with the code.

# TO-DO List

- [ ] Plugins compatibility
- [ ] Finish adding all the botapi methods
- [ ] Inline Query support
- [ ] More security otpions
- [ ] Add user-loaded from database class
- [ ] Add more functions to objects









