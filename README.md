# LoU Rankings

This is online score tracker for EA online strategy game "Lord of Ultima".
It connects to EA LoU servers, gathers all players data and saves it to database.
For examples please go to http://a.i3.lt/lou_rankings/ or look into ./examples/ directory for screenshots.

## Requirements
Server must have:
-PHP 5 with CURL enabled
-MySQL

All configuration settings are saved in ./config/ folder.

## How to get correct SessionID?
Open Chrome browser's development console.
Open "Network" tab and select one of the Poll requests on the left.
There you will find something like  {"session":"12c2a9c7-ea51-4a52-84cb-2628b19ebf80","requestid":...  under Request Payload.
Another way to get SessionID is to use Wireshark to capture packets and get the same SessionID from there. But the first way is a lot more easier.

## Collecting data
Data collection is not performed automatically, so you must open ./collector.php via your web browser and paste there your SessionID. Only public data is collected.

## Screenshots
[Alliances Score](https://github.com/hitex/LoU-Rankings/blob/master/examples/alliances_score.png)  
[Players Score](https://github.com/hitex/LoU-Rankings/blob/master/examples/players_score.png)  
