# SilBot Webhook

Base for bot telegram that uses webhook. Base tested on PHP5.6 and PHP7, supports mysql and is also integrated with altervista compatibility.

# Requirements
- - -
1) A webserver to host the bot that must be reachable through *https*
2) PHP 5.6+ must be installed

# Set Webhook

To set up the webhook you can simply use the @DevToolsForBot telegram bot, you can do it manually, but remember to insert the following parameters in the webhook link:
- api: Bot token
- admin: bot admin
- userbot: Bot username (Required if using mysql)

In addition, to install the database you must first make a request to the bot with the install parameters (giving it the value of true) and userbot.

# Configuration

Before installing the database if you do not use altervista you must fill in the fields of the config concerning the database.
