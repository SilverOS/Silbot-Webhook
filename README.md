# SilBot Webhook
Base per bot telegram che utilizza webhook. Base testata su PHP5.6 e PHP7,supporta mysql ed è integrata anche la compatibilità con altervista.

# Requisiti
- - -
1) Un webserver dove hostare il bot che deve essere raggiungibile tramite *https*
2) Si deve avere installato PHP 5.6+

# Set Webhook
- - -
Per impostare il webhook *semplicemente* si può fare uso del bot telegram @DevToolsForBot , oppute potete farlo *manualmente*, ma ricorda di inserire nel link del webhook i seguenti parametri:
- api : Token del bot
- admin : Admin del bot
- userbot : Username del bot (Necessario se si utilizza mysql)

Inoltre per installare il database dovrete fare prima una richiesta al bot con i parametri *install* (Dandogli il valore di true) e userbot.
# Configurazione
- - -
Prima di installare il database se non si utilizza altervista si devono compilare i campi del config riguardanti il database.
