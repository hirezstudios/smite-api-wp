SMITE API WP Plugin
===

Simply, a plugin for integrating SMITE API into a Wordpress site or theme. Abstracts transactions for retrieving SMITE API information through methods available on a SmiteAPI class object.

Note: some of the information is stored locally at regular intervals, to reduce the number of direct API calls.

For more information about the SMITE API, check out the [official API documentation](https://docs.google.com/document/d/1OFS-3ocSx-1Rvg4afAnEHlT3917MAK_6eJTR6rzr-BM/).

### DevID and AuthKey Setup

This plugin requires a DevID and AuthKey in order to interact with the SMITE API.

To request a Developer ID and Authorization Key from Hi-Rez Studios, submit [this form](https://fs12.formsite.com/HiRez/form48/secure_index.html). 

Enter your DevID and AuthKey into the SMITE API WP Settings page.

### General Usage

It is useful to define a global variable in your theme's ```functions.php``` for storing an instance of the plugin's class:

```php
  // create a global reference to the SMITE API class
  if ( class_exists( 'SmiteAPI' ) ){
    global $smiteAPI;
    $smiteAPI = new SmiteAPI();
  }
```

This instance, then, can be used for calling methods that return data analogous to the expected response from the SMITE API. The only difference is that in some cases, the returned object data may be from a locally cached version instead of a real-time response.

```php
  // call a method
  global $smiteAPI;
  $gods = $smiteAPI->getGods();
  // do whatever with the god data...
```

### Class API methods

#### God methods

- ``` get_gods($lang) ``` Returns all Gods and their various attributes. Requires a language id parameter (see the language key below).
- ``` get_god_recommended_items($god_id, $lang) ``` Returns the Recommended Items for a particular God. Requires the god's ID and a language id parameter (see the language key below).

#### Item methods

- ``` get_items($lang = 1); ``` Returns all Items and their various attributes. Requires a language id parameter (see the language key below).

##### Language key

| Language                | ID |
| ----------------------- |:--:|
| English                 | 1  |
| German                  | 2  |
| French                  | 3  |
| Spanish                 | 7  |
| Spanish (Latin America) | 9  |
| Portuguese              | 10 |
| Russian                 | 11 |
| Polish                  | 12 |
| Turkish                 | 13 |

#### Player methods

- ``` get_player($player_id=false) ``` Returns league and other high level data for a particular player. Requires a player id or the player's in-game name.
- ``` get_player_status($player_id=false) ``` Returns player status. Requires a player id or the player's in-game name.
- ``` get_match_history($player_id=false) ``` Gets recent matches and high level match statistics for a particular player. Requires a player id or the player's in-game name.
- ``` get_god_ranks($player_id=false) ``` Returns the Rank and Worshippers value for each God a player has played. Requires a player id or the player's in-game name.
- ``` get_friends($player_id=false) ``` Returns the Smite User names of each of the player’s friends. Requires a player id or the player's in-game name.

#### Team methods

- ``` search_teams($search_string) ``` Returns high level information for Team names containing the string searched. This method is useful for finding clan_ids when not known. Requires a search string.
- ``` get_team_details($clan_id) ``` Lists the number of players and other high level details for a particular clan. Requires a clan_id for the team.
- ``` get_team_match_history($clan_id) ``` Gets recent matches and high level match statistics for a particular clan/team. Requires a clan_id for the team.
- ``` get_team_players($clan_id) ``` Lists the players for a particular clan. Requires a clan_id for the team.

#### Queue Methods

- ``` get_match_ids_by_queue($queue_id=423, $dateArg=null, $hour=-1) ``` Lists all Match IDs for a particular Match Queue; useful for API developers interested in constructing data by Queue.  To limit the data returned, an {hour} parameter was added (valid values: 0 - 23). This hour value is based on GMT timezone. An {hour} parameter of -1 represents the entire day, but be warned that this may be more data than we can return for certain queues.  Also, a returned "active_flag" means that there is no match information/stats for the corresponding match. Usually due to a match being in-progress, though there could be other reasons. Requires a queue_id (defaults to 423), a date in question (defaults to the current date), and an hour specifier (defaults to all 24 hours). See the queue/tier key below.
- ``` get_league_leaderboard($queue_id=451, $tier=26, $seasonArg=null) ``` Returns the top players for a particular league (as indicated by the queue/tier/season parameters). 440, 451 are the only eligible queue. Requires a queue_id (defaults to 451), a tier_id (defaults to 26), and a season which represents the number of the calendar month being searched (starting at 1 for January).
- ``` get_league_seasons($queue_id=451) ``` Provides a list of seasons (including the single active season) for a match queue. Requires a queue_id ( defaults to 451).
- ``` get_queue_stats($player_id=false,$queue_id=423) ``` Returns match summary statistics for a (player, queue) combination grouped by gods played. Requires a queue_id (defaults to 423) and player_id. 

##### Queue ID key

| Queue Name           | ID   |
| -------------------- |:----:|
| Conquest5v5          | 423  |
| NoviceQueue          | 424  |
| Conquest             | 426  |
| Practice             | 427  |
| ConquestChallenge    | 429  |
| ConquestRanked       | 430  |
| Domination           | 433  |
| MOTD                 | 434  |
| Arena                | 435  |
| ArenaChallenge       | 438  |
| DominationChallenge  | 439  |
| JoustLeague          | 440  |
| JoustChallenge       | 441  |
| Assault              | 445  |
| AssaultChallenge     | 446  |
| Joust3v3             | 448  |
| ConquestLeague       | 451  |
| ArenaLeague          | 452  |

##### Tier ID key

| Tier Name               | ID |
| ----------------------- |:--:|
| Bronze V                | 1  |
| Bronze IV               | 2  |
| Bronze III              | 3  |
| Bronze II               | 4  |
| Bronze I                | 5  |
| Silver V                | 6  |
| Silver IV               | 7  |
| Silver III              | 8  |
| Silver II               | 9  |
| Silver I                | 10 |
| Gold V                  | 11 |
| Gold IV                 | 12 |
| Gold III                | 13 |
| Gold II                 | 14 |
| Gold I                  | 15 |
| Platinum V              | 16 |
| Platinum IV             | 17 |
| Platinum III            | 18 |
| Platinum II             | 19 |
| Platinum I              | 20 |
| Diamond V               | 21 |
| Diamond IV              | 22 |
| Diamond III             | 23 |
| Diamond II              | 24 |
| Diamond I               | 25 |
| Masters I               | 26 |

#### Match Methods

- ``` get_demo_details($matchid=false) ``` Returns information regarding a particular match.  Rarely used in lieu of getmatchdetails(). Requires a match_id.
- ``` get_match_details($matchid=false) ``` Returns the statistics for a particular completed match. Requires a match_id.
- ``` get_match_player_details($matchid=false) ``` Returns player information for a live match. Requires a match_id.

#### Misc methods

- ``` get_data_used() ``` Returns API Developer daily usage limits and the current status against those limits.

### Managing Temporary Storage

On the plugin's settings page, you can adjust how long responses are cached locally before Wordpress will make another request. This caching mechanism uses the [WP Transient API](https://codex.wordpress.org/Transients_API).

