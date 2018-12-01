# SilBot X
Versione sperimentale e molto diversa da Silbot, basata più sulla velocità,leggerezza e sicurezza.

# Sicurezza
Questa versione,essendo basata sulla sicurezza, oltre ad aver reso più sicure e veloci le query ha aggiunto delle impostazioni al config che la rendono più sicura:

1) Filtro per Username del bot, che permette solo ad alcuni bot di fare richieste, bloccando eventuali cloni indesiderati
2) Blocco per IP, permette di bloccare le richieste non proveienti da ip di Telegram, *NON FUNZIONA CON CLOUDFLARE*



# Requisiti
- - -
1) Un webserver dove hostare il bot che deve essere raggiungibile tramite *https*
2) Si deve avere installato PHP 5.6+

# Set Webhook
- - -
Per impostare il webhook *semplicemente* si può fare uso del bot telegram @DevToolsForBot , oppute potete farlo *manualmente*, ma ricorda di inserire nel link del webhook i seguenti parametri:
- api : Token del bot
- userbot : Username del bot (Necessario se si utilizza mysql)

Inoltre per installare il database dovrete fare prima una richiesta al bot con i parametri *install* (Dandogli il valore di true) e userbot.
# Configurazione
- - -
Prima di installare il database se non si utilizza altervista si devono compilare i campi del config riguardanti il database.
