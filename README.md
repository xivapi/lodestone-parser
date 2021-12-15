# lodestone-parser
Parse lodestone for those juicy details

- Run `php cli <func> <arguments>` for debugging
- Run `php tests` to validate tests

|CLI Command|Arguments|Description|
|-|-|-|
|`character`|`<id>`|Prints a character parse.
|`freecompany`|`<id>`|Prints a freecompanies parse.|
|`pvpteam`|`<id>`|Prints a pvpteam parse.|
|`linkshell`|`<id>`|Prints a linkshell parse.|
|`achievements`|`<id>`|Prints a characters achievement parse.|
|`banners`|none|Prints the currently displayed banners on the lodestone homepage.|
|`leaderboards`|`feast`,`potd`,`hoh`|Prints the current leaderboard parse for The Feast, Palace of The Dead, or Heaven on High.|

All commands accept a flag to print the returned blob to a json file.
Example
```
// prints returned object to file myCharacter.json
php cli character <lodestoneid> -file myCharacter
```