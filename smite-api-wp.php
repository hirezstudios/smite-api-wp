<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: SMITE API WP Plugin
 * Plugin URI: http://www.github.com/hirezstudios/smite-api-wp
 * Description: Create a globally available SmiteAPI class object for transacting with the SMITE API. DevID and AuthKey, provided by Hi-Rez Studios, should be entered on the settings page.
 * Version: 1.0.0
 * Author: Coran Spicer
 * License: Copyright 2015 Hi-Rez Studios
 */
 
// set up admin options page
if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'smiteapi_create_menu' );
	add_action( 'admin_init', 'register_smiteapi_settings' );
  add_action( 'admin_init', 'add_smiteapi_dev_cap');
} else {
  // non-admin enqueues, actions, and filters
}

// add the custom capability to the administrator role
// custom capability bifurcates it from the admin role directly
// this allows for separate capability assignment at your discretion
function add_smiteapi_dev_cap() {
  
  $role = get_role( 'administrator' );
  $role->add_cap( 'smiteapi_dev' );
  
}

// create the dashboard menu item for plugin settings page
function smiteapi_create_menu() {
  
	//create new top-level menu
	add_menu_page('SMITE API WP Plugin Settings', 'SMITE API WP Settings', 'smiteapi_dev', 'smiteapi-settings', 'smiteapi_settings_page',plugins_url('/images/icon.png', __FILE__));
	
}


function register_smiteapi_settings() {
	//register our settings
	register_setting( 'smiteapi-settings-group', 'smiteapi_dev_id' );
	register_setting( 'smiteapi-settings-group', 'smiteapi_auth_key' );
	register_setting( 'smiteapi-settings-group', 'smiteapi_sessiontoken_expiry' );
	// transient expiry settings fields
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getdataused_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getdemodetails_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getesportsproleaguedetails_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getfriends_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getgodranks_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getgods_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getgodrecommendeditems_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getitems_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getmatchdetails_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getmatchplayerdetails_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getmatchidsbyqueue_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getleagueleaderboard_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getleagueseasons_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getmatchhistory_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getplayer_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getplayerstatus_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getqueuestats_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getteamdetails_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getteammatchhistory_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_getteamplayers_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_gettopmatches_exp' );
	register_setting( 'smiteapi-settings-group', 'sapi_tran_searchteams_exp' );
}

function smiteapi_settings_page() {
  ?>
  <div class="wrap">
    <h2>SMITE API WP Plugin</h2>
    <p>Simply, a plugin for integrating SMITE API into a Wordpress site or theme. Abstracts transactions for retrieving SMITE API information through methods available on a SmiteAPI class object.<br /><br />Note: some of the information is stored locally at regular intervals, to reduce the number of direct API calls.<br /><br />For more information about the SMITE API, check out the <a href="https://docs.google.com/document/d/1OFS-3ocSx-1Rvg4afAnEHlT3917MAK_6eJTR6rzr-BM/" target="_blank">official API documentation</a>.</p>
    <p>This plugin requires a DevID and AuthKey in order to interact with the SMITE API.<br /><br />To request a Developer ID and Authorization Key from Hi-Rez Studios, submit <a href="https://fs12.formsite.com/HiRez/form48/secure_index.html" target="_blank" >this form</a>.</p>
    <form method="post" action="options.php">
        <?php settings_fields( 'smiteapi-settings-group' ); ?>
        <?php do_settings_sections( 'smiteapi-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row">Dev ID</th>
            <td><input type="text" name="smiteapi_dev_id" value="<?php echo esc_attr( get_option('smiteapi_dev_id') ); ?>" /></td>
            </tr>
             
            <tr valign="top">
            <th scope="row">Auth Key</th>
            <td><input type="text" name="smiteapi_auth_key" value="<?php echo esc_attr( get_option('smiteapi_auth_key') ); ?>" /></td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Session Token Expiry <br /><small>(in minutes)</small></th>
            <td><input type="text" name="smiteapi_sessiontoken_expiry" value="<?php echo esc_attr( get_option('smiteapi_sessiontoken_expiry', 15) ); ?>" /></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
        
        <h3>API Endpoint Caching Expiration times ( in minutes )</h3>
        
        <table class="form-table">
          <tr valign="top">
            <th scope="row">cache responses from <strong>getdataused/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getdataused_exp" value="<?php echo esc_attr( get_option('sapi_tran_getdataused_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getdemodetails/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getdemodetails_exp" value="<?php echo esc_attr( get_option('sapi_tran_getdemodetails_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getesportsproleaguedetails/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getesportsproleaguedetails_exp" value="<?php echo esc_attr( get_option('sapi_tran_getesportsproleaguedetails_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getfriends/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getfriends_exp" value="<?php echo esc_attr( get_option('sapi_tran_getfriends_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getgodranks/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getgodranks_exp" value="<?php echo esc_attr( get_option('sapi_tran_getgodranks_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getgods/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getgods_exp" value="<?php echo esc_attr( get_option('sapi_tran_getgods_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getgodrecommendeditems/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getgodrecommendeditems_exp" value="<?php echo esc_attr( get_option('sapi_tran_getgodrecommendeditems_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getitems/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getitems_exp" value="<?php echo esc_attr( get_option('sapi_tran_getitems_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getmatchdetails/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getmatchdetails_exp" value="<?php echo esc_attr( get_option('sapi_tran_getmatchdetails_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getmatchplayerdetails/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getmatchplayerdetails_exp" value="<?php echo esc_attr( get_option('sapi_tran_getmatchplayerdetails_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getmatchidsbyqueue/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getmatchidsbyqueue_exp" value="<?php echo esc_attr( get_option('sapi_tran_getmatchidsbyqueue_exp') ); ?>" /></td>
          </tr>
          
          <tr valign="top">
            <th scope="row">cache responses from <strong>getleagueleaderboard/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getleagueleaderboard_exp" value="<?php echo esc_attr( get_option('sapi_tran_getleagueleaderboard_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getleagueseasons/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getleagueseasons_exp" value="<?php echo esc_attr( get_option('sapi_tran_getleagueseasons_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getmatchhistory/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getmatchhistory_exp" value="<?php echo esc_attr( get_option('sapi_tran_getmatchhistory_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getplayer/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getplayer_exp" value="<?php echo esc_attr( get_option('sapi_tran_getplayer_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getplayerstatus/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getplayerstatus_exp" value="<?php echo esc_attr( get_option('sapi_tran_getplayerstatus_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getqueuestats/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getqueuestats_exp" value="<?php echo esc_attr( get_option('sapi_tran_getqueuestats_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getteamdetails/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getteamdetails_exp" value="<?php echo esc_attr( get_option('sapi_tran_getteamdetails_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getteammatchhistory/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getteammatchhistory_exp" value="<?php echo esc_attr( get_option('sapi_tran_getteammatchhistory_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>getteamplayers/</strong> for:</th>
            <td><input type="text" name="sapi_tran_getteamplayers_exp" value="<?php echo esc_attr( get_option('sapi_tran_getteamplayers_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>gettopmatches/</strong> for:</th>
            <td><input type="text" name="sapi_tran_gettopmatches_exp" value="<?php echo esc_attr( get_option('sapi_tran_gettopmatches_exp') ); ?>" /></td>
          </tr>
          <tr valign="top">
            <th scope="row">cache responses from <strong>searchteams/</strong> for:</th>
            <td><input type="text" name="sapi_tran_searchteams_exp" value="<?php echo esc_attr( get_option('sapi_tran_searchteams_exp') ); ?>" /></td>
          </tr>
        </table>
        
        <?php submit_button(); ?>
    
    </form>
  </div>
  <?php 
  }

/**
* Create global SmiteAPI class object
**/
if ( !class_exists( 'SmiteAPI' ) ) {
  class SmiteAPI {
    function __construct() {
      // stub for adding any necessary hooks
      //add_action( 'hook_name', array( &$this, 'my_hook_implementation' ) );
      
      // do init stuff here
      $this->devID   = get_option('smiteapi_dev_id');
      $this->authKey = get_option('smiteapi_auth_key');
    }

    function my_hook_implementation() {
      // sample hook callback placeholder
    }
    
    // private variables
    protected $baseURL = 'http://api.smitegame.com/smiteapi.svc';
    protected $responseType = 'Json'; // '-_-
    protected $devID;
    protected $authKey;
    protected $validSessionToken;
    

    // private function
    // populate schedule information to transient object
    protected function populate_schedule() {
      
      // first check if transient is defined 
      // Get any existing copy of our transient data
      if ( false === ( $weeklyScheduleTransient = get_transient( 'weekly_schedule_data' ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        // using wp_remote_get to retrieve the schedule information from the account.hirezstudios.com endpoints
        $url = 'http://account.hirezstudios.com/esports/response/get-tournament-data-rr.aspx?region=3'; // region 1 is NA, 2 is EU, and 3 is both
        $args = array('method' => 'POST', 'headers' => array('origin'=>'http://esports.smitegame.com/') ); // spoof domain origin
        $requestSchedule = wp_remote_post( $url, $args );
        if ( is_wp_error( $requestSchedule ) ) {
          $this->weeklyGames['error'] = 1;
          return $this->weeklyGames;
        } else {
          $schedule = json_decode( $requestSchedule[body] );
        }
        
        /**
        schedule returns an array of games with the following properties (values are there for example's sake):
          [match_week_number] => 3
          [region] => EU
          [match_week_description] => Mar 18 - Mar 8, 2015
          [match_day] => 2015/03/22
          [match_time] => 13:00
          [match_day_of_week] => 
          [match_day_of_week_short] => Sun
          [match_date] => 3/22/2015 1:00 PM
          [map_intance_id] => 
          [team_1_name] => Titan
          [team_1_shortname] => Titan
          [team_1_score] => 0
          [team_2_name] => Upcoming Stars
          [team_2_shortname] => STARS
          [team_2_score] => 0
          [match_status] => 1
          [vod_match1] => 
          [vod_match2] => 
          [vod_match3] => 
          [vod_match4] => 
          [vod_match5] => 
          [map_instance_1] => 
          [map_instance_2] => 
          [map_instance_3] => 
          [map_instance_4] => 
          [map_instance_5] => 
        **/
        
        // sort schedule into weeks and days
        foreach( $schedule as $game ) {
          // determine week number and day number
          $day = $game->match_date;
          $date = new DateTime($day);
          $weekNumber = $date->format('W');
          $dayNumber = $date->format('w');
          // grab the label for easier reference
          $weekLabel = $this->weekLabelMap[$weekNumber]['simple_title'];
          if ( !$weekLabel || !$dayNumber ) {
            // handle no week found error
            // probably toss a message in to the otherwise empty standings table
          }
          // store game into appropriate week
          $this->weeklyGames['allgames'][]= $game; // general container for games, not really needed
          $this->weeklyGames[$weekLabel]['days'][$dayNumber][]= $game;
        }
        foreach( $this->weeklyGames as $week => $weeksGames ) {
          // Sunday is referenced as zero in PHP
          // so we'll have to move shift-push it to the back of the array
          $weekLabel = $week;
          if ( $week == 'error' ) { continue; }
          array_push( $this->weeklyGames[$weekLabel]['days'], array_shift($this->weeklyGames[$weekLabel]['days']) ); //moves Sunday to end
        }
        set_transient( 'weekly_schedule_data', $this->weeklyGames, 60*3 );
        
      } else {
        $this->weeklyGames = get_transient( 'weekly_schedule_data');
      }
        // return it
        return $this->weeklyGames;
    }
    // create signature
    protected function create_signature( $methodString ) {
      $inputString = $this->devID . $methodString . $this->authKey . date('YmdHis');
      return md5( $inputString );
    }
    // get session token
    protected function get_session_token() {
      // encapsulated variable references
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      if ( get_transient('smiteapi_session_token') ) {
        // valid session token exists in transient cache
        $this->validSessionToken = get_transient('smiteapi_session_token');
      } else {
        // invalid session token
        // go get the session token
        // using wp_remote_get to retrieve the schedule information from the account.hirezstudios.com endpoints
        $apiMethod = 'createsession';
        $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.date('YmdHis');
        $requestToken = wp_remote_get( $url );
        if ( !$requestToken['response']['code'] == 200 ) {
          // handle bad response
          return $this->init_wp_error( $apiMethod.' Failed', $requestToken['response'] );
        } else {
          $responseObj = json_decode( $requestToken['body'] );
          if ( !$responseObj->ret_msg == 'Approved' ) {
            // access is not approved...
            $this->init_wp_error( $apiMethod.' Not Approved', $responseObj->ret_msg );
          } else {
            $sessionToken = $responseObj->session_id;
            $this->validSessionToken = $sessionToken;
            set_transient( 'smiteapi_session_token', $sessionToken, get_option( 'smiteapi_sessiontoken_expiry', 15 ) * 60 );
          }
        }
      }
      return $this->validSessionToken;
    }
    // transact with API
    protected function api_transaction($transientKey, $endpointURL, $transientExpiry) {
      if ( get_transient('sapi_tran_'.$transientKey) ) {
        // API response exists in transient cache
        return get_transient('sapi_tran_'.$transientKey);
      } else {
        // cached version of API response not stored
        $requestTrans = wp_remote_get( $endpointURL );
        if ( !$requestTrans['response']['code'] == 200 ) {
          // handle bad response
          return $this->init_wp_error( 'Method '.$transientKey.' Failed, at endpoint: '.$endpointURL, $requestTrans['response'] );
        } else {
          $responseObj = json_decode( $requestTrans['body'] );
          set_transient( 'sapi_tran_'.$transientKey, $responseObj,  $transientExpiry );
          return $responseObj;
        }
      }
    }
    // error helper
    protected function init_wp_error( $errorMsg, $data ) {
      return new WP_Error( 'smiteapi_error', $errorMsg, $data );
    }
    
    // public methods
    /**
    * Get Data Used
    * /getdataused[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}
    * Returns API Developer daily usage limits and the current status against those limits.
    **/
    public function get_data_used() {
      // method variables
      $apiMethod = 'getdataused';
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis');
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod, $url, $transientExpiry);
    }
    /**
    * Get Demo Details
    * /getdemodetails[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{match_id}
    * Returns information regarding a particular match.  Rarely used in lieu of getmatchdetails().
    **/
    public function get_demo_details($matchid=false) {
      // method variables
      $apiMethod = 'getdemodetails';
      if ( !$matchid ) {
        return $this->init_wp_error( 'Missing Argument', 'match_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$matchid;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$matchid, $url, $transientExpiry);
    }
    /**
    * Get Match Details
    * /getmatchdetails[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{match_id}
    * Returns the statistics for a particular completed match.
    **/
    public function get_match_details($matchid=false) {
      // method variables
      $apiMethod = 'getmatchdetails';
      if ( !$matchid ) {
        return $this->init_wp_error( 'Missing Argument', 'match_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$matchid;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$matchid, $url, $transientExpiry);
    }
    /**
    * Get Match Player Details
    * /getmatchplayerdetails[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{match_id}
    * Returns player information for a live match.
    **/
    public function get_match_player_details($matchid=false) {
      // method variables
      $apiMethod = 'getmatchplayerdetails';
      if ( !$matchid ) {
        return $this->init_wp_error( 'Missing Argument', 'match_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$matchid;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$matchid, $url, $transientExpiry);
    }
    /**
    * Get Gods
    * /getgods[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{languageCode}
    * Returns all Gods and their various attributes.
    **/
    public function get_gods($lang = 1) {
      // method variables
      $apiMethod = 'getgods';
      if ( !$lang ) {
        return $this->init_wp_error( 'Missing Argument', 'language designator is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$lang;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$lang, $url, $transientExpiry);
    }
    /**
    * Get Items
    * /getitems[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{languagecode}
    * Returns all Items and their various attributes.
    **/
    public function get_items($lang = 1) {
      // method variables
      $apiMethod = 'getitems';
      if ( !$lang ) {
        return $this->init_wp_error( 'Missing Argument', 'language designator is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$lang;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$lang, $url, $transientExpiry);
    }
    /**
    * Get God Recommended Items
    * /getgodrecommendeditems[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{godid}/{languageCode}
    * Returns the Recommended Items for a particular God.
    **/
    public function get_god_recommended_items($god_id=null,$lang = 1) {
      // method variables
      $apiMethod = 'getgodrecommendeditems';
      if ( !$god_id ) {
        return $this->init_wp_error( 'Missing Argument', 'God ID is required' );
      }
      if ( !$lang ) {
        return $this->init_wp_error( 'Missing Argument', 'language designator is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$god_id.'/'.$lang;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$god_id.'_'.$lang, $url, $transientExpiry);
    }
    /**
    * Get eSports Pro League Details
    * /getesportsproleaguedetails[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}
    * Returns the matchup information for each matchup for the current eSports Pro League season.  An important return value is “match_status” which represents a match being scheduled (1), in-progress (2), or complete (3).
    **/
    public function get_esports_pro_league_details() {
      // method variables
      $apiMethod = 'getesportsproleaguedetails';
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis');
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod, $url, $transientExpiry);
    }
    /**
    * Get Friends
    * /getfriends[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{player}
    * Returns the Smite User names of each of the player’s friends.
    **/
    public function get_friends($player_id=false) {
      // method variables
      $apiMethod = 'getfriends';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$player_id, $url, $transientExpiry);
    }
    /**
    * Get Player
    * /getplayer[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{playerName}
    * Returns league and other high level data for a particular player.
    **/
    public function get_player($player_id=false) {
      // method variables
      $apiMethod = 'getplayer';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$player_id, $url, $transientExpiry);
    }
    /**
    * Get Player Status
    * /getplayerstatus[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{player}
    * Returns player status as follows: 
    *
    *   0 - Offline
    *   1 - In Lobby  (basically anywhere except god selection or in game)
    *   2 - god Selection (player has accepted match and is selecting god before start of game)
    * 	3 - In Game (match has started)
    * 	4 - Online (player is logged in, but may be blocking broadcast of player state).
    **/
    public function get_player_status($player_id=false) {
      // method variables
      $apiMethod = 'getplayerstatus';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id;
      
      // variables for extending the returned object
      $statusLabels = array(
        'Offline',
        'In Lobby',
        'God Selection',
        'In Game',
        'Online'
      );
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      $playerStatus = $this->api_transaction($apiMethod.'_'.$player_id, $url, $transientExpiry);
      $statusLabel = $statusLabels[$playerStatus[0]->{'status'}];
      $playerStatus[0]->{'status_label'} = $statusLabel;
      return $playerStatus;
    }
    /**
    * Get Match History
    * /getmatchhistory[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{player}
    * Gets recent matches and high level match statistics for a particular player.
    **/
    public function get_match_history($player_id=false) {
      // method variables
      $apiMethod = 'getmatchhistory';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$player_id, $url, $transientExpiry);
    }
    /**
    * Get God Ranks
    * /getgodranks[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{player}
    * Returns the Rank and Worshippers value for each God a player has played.
    **/
    public function get_god_ranks($player_id=false) {
      // method variables
      $apiMethod = 'getgodranks';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$player_id, $url, $transientExpiry);
    }
    /**
    * Get Match IDs by Queue
    * /getmatchidsbyqueue[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{queue}/{date}/{hour}
    * Lists all Match IDs for a particular Match Queue; useful for API developers interested in constructing data by Queue. 
    * To limit the data returned, an {hour} parameter was added (valid values: 0 - 23). This hour value is based on GMT timezone.
    * An {hour} parameter of -1 represents the entire day, but be warned that this may be more data than we can return for
    * certain queues.  Also, a returned “active_flag” means that there is no match information/stats for the corresponding match.
    * Usually due to a match being in-progress, though there could be other reasons.
    **/
    public function get_match_ids_by_queue($queue_id=423, $dateArg=null, $hour=-1) {
      // method variables
      $apiMethod = 'getmatchidsbyqueue';
      $date = $dateArg ? $dateArg : date('Ymd');
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$queue_id.'/'.$date.'/'.$hour;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$queue_id.'_'.$date.'_'.$hour, $url, $transientExpiry);
    }
    /**
    * Get League Leaderboard
    * /getleagueleaderboard[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{queue}/{tier}/{season}
    * Returns the top players for a particular league (as indicated by the queue/tier/season parameters). 440, 451 are the only eligible queue
    **/
    public function get_league_leaderboard($queue_id=451, $tier=26, $seasonArg=null) {
      // method variables
      $apiMethod = 'getleagueleaderboard';
      $season = $seasonArg ? $seasonArg : date('n');
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$queue_id.'/'.$tier.'/'.$season;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$queue_id.'_'.$tier.'_'.$season, $url, $transientExpiry);
    }
    /**
    * Get League Seasons
    * /getleagueseasons[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{queue}
    * Provides a list of seasons (including the single active season) for a match queue.
    **/
    public function get_league_seasons($queue_id=451) {
      // method variables
      $apiMethod = 'getleagueseasons';
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$queue_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$queue_id, $url, $transientExpiry);
    }
    /**
    * Get Queue Stats
    * /getqueuestats[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{player}/{queue}
    * Returns match summary statistics for a (player, queue) combination grouped by gods played.
    **/
    public function get_queue_stats($player_id=false,$queue_id=423) {
      // method variables
      $apiMethod = 'getqueuestats';
      if ( !$player_id ) {
        return $this->init_wp_error( 'Missing Argument', 'player_name or account_id is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$player_id.'/'.$queue_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$player_id.'_'.$queue_id, $url, $transientExpiry);
    }
    /**
    * Search Teams
    * /searchteams[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{searchTeam}
    * Returns high level information for Team names containing the “searchTeam” string.
    **/
    public function search_teams($search_string) {
      // method variables
      $apiMethod = 'searchteams';
      if ( !$search_string ) {
        return $this->init_wp_error( 'Missing Argument', 'a search string is required' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$search_string;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$search_string, $url, $transientExpiry);
    }
    /**
    * Get Team Details
    * /getteamdetails[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{clanid}
    * Lists the number of players and other high level details for a particular clan.
    **/
    public function get_team_details($clan_id) {
      // method variables
      $apiMethod = 'getteamdetails';
      if ( !$clan_id ) {
        return $this->init_wp_error( 'Missing Argument', 'a clan_id is required.' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$clan_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$clan_id, $url, $transientExpiry);
    }
    /**
    * Get Team Match History
    * /getteammatchhistory[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{clanid}
    * Gets recent matches and high level match statistics for a particular clan/team.
    **/
    public function get_team_match_history($clan_id) {
      // method variables
      $apiMethod = 'getteammatchhistory';
      if ( !$clan_id ) {
        return $this->init_wp_error( 'Missing Argument', 'a clan_id is required.' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$clan_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$clan_id, $url, $transientExpiry);
    }
    /**
    * Get Team Players
    * /getteamplayers[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}/{clanid}
    * Lists the players for a particular clan.
    **/
    public function get_team_players($clan_id) {
      // method variables
      $apiMethod = 'getteamplayers';
      if ( !$clan_id ) {
        return $this->init_wp_error( 'Missing Argument', 'a clan_id is required.' );
      }
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis').'/'.$clan_id;
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod.'_'.$clan_id, $url, $transientExpiry);
    }
    /**
    * Get Top Matches
    * /gettopmatches[ResponseFormat]/{developerId}/{signature}/{session}/{timestamp}
    * Lists the 50 most watched / most recent recorded matches.
    **/
    public function get_top_matches() {
      // method variables
      $apiMethod = 'gettopmatches';
      
      // encapsulated variable refs
      $baseURL = $this->baseURL;
      $responseType = $this->responseType;
      $devID = $this->devID;
      $authKey = $this->authKey;
      $url = $baseURL.'/'.$apiMethod.$responseType.'/'.$devID.'/'.$this->create_signature( $apiMethod ).'/'.$this->get_session_token().'/'.date('YmdHis');
      
      $transientExpiry = get_option( 'sapi_tran_'.$apiMethod.'_exp', 60 );
      
      return $this->api_transaction($apiMethod, $url, $transientExpiry);
    }
    
  }
}

?>