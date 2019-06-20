<?php
 /*
Plugin Name: Pete Events Collector
Description: A plugin to manage daily collection and caching of multiple events APIs, and provide a uniform interface to interact with them. Pete 2020!
Version: 0.2
Author: http://github.com/users/jagp, https://github.com/dcsturman
License: MIT
*/ 

// @todo Daily CRON job to refresh APIs

// Constants

define( "EVENTS_COLLECTOR_SETTING_NAME",                "events_collector_options" );
define( "ACTION_NETWORK_EVENTS_ENDPOINT",               "https://actionnetwork.org/api/v2/events/" );
define( "ACTION_NETWORK_EVENTS_CAMPAIGN_ENDPOINT",      "https://actionnetwork.org/api/v2/event_campaigns" );
define( "AN_EVENTS_NAME",                               "Action Network" );
define( "AN_EVENTS_CAMPAIGN_NAME",                      "Action Network - Events Campaign" );
define( "MAP_SHORTCODE_FILENAME",                       "map-shortcode.php");
define( "SETTINGS_PAGE_MENU_SLUG",                      "events-collector");
define( "SETTINGS_PAGE_FILENAME",                       "settings-page.php");
define( "SECRET_KEY_FILENAME",                          "secret_api_keys.php");
define( "FRONT_END_API_VAR_NAME",                       "eventsData");

define( "DEBUG", TRUE );
//define( "DEBUG", FALSE );

//Load plugin internals
require_once plugin_dir_path( __FILE__ ) . "inc/class/api-interface.php";
require_once plugin_dir_path( __FILE__ ) . "inc/class/action-network-interface.php";
require_once plugin_dir_path( __FILE__ ) . "inc/class/action-network-event-campaign-interface.php";
require_once plugin_dir_path( __FILE__ ) . "inc/class/database-record.php";
require_once plugin_dir_path( __FILE__ ) . "inc/class/events-manager.php";

//Load main events manager
$EventsManager = new EventsManager();

//Load the secret API keys
require_once plugin_dir_path( __FILE__ ) . "private/" . SECRET_KEY_FILENAME;
$api_keys = array( 
    'action_network'    => defined( 'ACTION_NETWORK_API_KEY' ) ? ACTION_NETWORK_API_KEY : FALSE,
    'google_maps'       => defined( 'GOOGLE_MAPS_API_KEY' ) ? GOOGLE_MAPS_API_KEY : FALSE
);

/**
 * Ensure a secret key has been loaded correctly, or warn
 */
foreach( $api_keys as $api_name => $global_key ) {
    if ( ! $global_key ) { 
        if ( DEBUG ) { error_log( '[Events Collector] Warning: ' . $api_key . ' is missing or malformed.' ); }
        add_action( 
            'admin_notices', 
            function(){ ?><div class="notice notice-warning is-dismissible"><p><?php echo "Warning: An API Key is missing. Event displays may not function properly."; ?></p></div><?php } );
    }
}


/**
 * Provide a shortcode to embed a custom map 
 */
require_once plugin_dir_path( __FILE__ ) . "inc/" . MAP_SHORTCODE_FILENAME;


/**
 * Add settings page with info on the APIs/caching state
 */
add_action( 'admin_menu', 'add_events_collector_admin_page' );
function add_events_collector_admin_page() {
    add_menu_page( 'Events API Info', 'Events APIs', 'manage_options', SETTINGS_PAGE_MENU_SLUG, 'events_collector_settings_page' );
}
function events_collector_settings_page() {
    require_once  plugin_dir_path( __FILE__ ) . "inc/" . SETTINGS_PAGE_FILENAME;
}


/**
 * Load scripts and styles needed only on the admin settings page
 */
add_action( 'admin_enqueue_scripts', 'events_collector_admin_styles' );
function events_collector_admin_styles( $admin_page_slug ) {
    // Only load on our admin settings page
    if ( $admin_page_slug != 'toplevel_page_' . SETTINGS_PAGE_MENU_SLUG ) { return; }

    wp_enqueue_style( 'events-collector-css', plugin_dir_url( __FILE__ ) . 'inc/admin/admin-styles.css', NULL, filemtime(plugin_dir_path( __FILE__ ) . 'inc/admin/admin-styles.css' ) );
    wp_enqueue_script( 'events-collector-js', plugin_dir_url( __FILE__ ) . 'inc/admin/admin-scripts.js', array('jquery'), filemtime(plugin_dir_path( __FILE__ ) . 'inc/admin/admin-scripts.js') );
   
}


/**
 * Submit a request to an API endpoint, and convert the 
 * response records into internal Events
 * 
 * @return Array(Events)
 */
function fetch_api_data( $eManager = null, $update = true, $endpoint = ACTION_NETWORK_EVENTS_ENDPOINT, $api_name = 'Action Network' ) {
    if (DEBUG) { error_log('---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'); }

    // Fire up a new API interface and try to map incoming data
    try {

        if ( $api_name == AN_EVENTS_NAME ) {
            $apiInterface = new APIInterface_ActionNetwork( $endpoint, $api_name );
        } else if ( $api_name == AN_EVENTS_CAMPAIGN_NAME ) {
            $apiInterface = new APIInterface_ANEventCampaign( $endpoint, $api_name );
        } else {

        }
        
        $parsed_events = $apiInterface->getParsedResponse();

        error_log( var_export( $parsed_events, true ) );
 
        $eManager->addEvents( $parsed_events );
        $result = $eManager->getEvents();

    } catch ( Exception $e ) {
        if (DEBUG) { error_log( 'Error processing API: ' . print_r( $e->getMessage(), true ) ); }
    }

    if ( is_wp_error( $result ) ) {
        if ( DEBUG ) { error_log( "[Events Collector] Error retrieving API request on settings page load. Error: " . $result->get_error_message() ); }
        if ( $update ) { update_option( EVENTS_COLLECTOR_SETTING_NAME, FALSE ); }
        return FALSE;
    } 

    if ( $update ) {       
        update_option( EVENTS_COLLECTOR_SETTING_NAME, $result );
    }

    return $result;
    
}
// @todo? Create a table with our event results
// For now, trigger on page load:
fetch_api_data( $EventsManager, TRUE, ACTION_NETWORK_EVENTS_ENDPOINT, 'Action Network' );
//Need to handle multi-page events 'categories':
//fetch_api_data( $EventsManager, TRUE, 'https://actionnetwork.org/api/v2/event_campaigns/aafc4b0f-38ff-4cae-8891-8b6dec64b170', 'Action Network - Events Campaign' );
//fetch_api_data( $EventsManager, TRUE, 'https://actionnetwork.org/api/v2/event_campaigns/5565fc1e-b0bf-43e8-ad66-13bdb9605d7f', 'Action Network - Events Campaign' );
//fetch_api_data( $EventsManager, TRUE, 'https://actionnetwork.org/api/v2/event_campaigns/8765fc89-1ec6-44ea-87ef-5b3d5e6eb848', 'Action Network - Events Campaign' );


/**
* Add the AJAX endpoint for our internal settings form (logged-in users only)
* 
*/
add_action( 'wp_ajax_events_collector_settings', 'ajax_fetch_api_data' );
add_action( 'wp_ajax_retrieve_cache', 'ajax_retrieve_cache' );
add_action( 'wp_ajax_clear_cache', 'ajax_clear_cache' );
add_action( 'wp_ajax_manual_endpoint_fetch', 'ajax_retrieve_cache' );


/**
 * Perform a new API request and return the data as a response
 */
function ajax_fetch_api_data() {

    $endpoint = array_key_exists( 'endpoint', $_POST ) ? $_POST['endpoint'] : NULL;
    $update = array_key_exists( 'update', $_POST ) ? $_POST['update'] : TRUE;

    // Perform the api fetch
    if ( $endpoint ) { 
        $data = fetch_api_data( $update, $endpoint ); //optional Endpoint coming from JS
    } else {
        $data = fetch_api_data( $update ); 
    }

    if ( $_POST['update'] === "TRUE" ) {
        update_option( EVENTS_COLLECTOR_SETTING_NAME, $data );
    }

    send_ajax_response( $data );
}


/**
* @return a json-encoded object with the current cache
*/
function ajax_retrieve_cache() {

    $data = get_option( EVENTS_COLLECTOR_SETTING_NAME );
    send_ajax_response( $data );

}

/**
* Clear the current cache
*/
function ajax_clear_cache() {

    update_option( EVENTS_COLLECTOR_SETTING_NAME, 'none' );
    send_ajax_response( array( 'content' => 'none' ) );
}


/**
 * Helper function: handle steps for returning data in correct JSON format
 */
function send_ajax_response( $data ) {
    //Package up the json object
    wp_send_json( $data );

    //Close the connection for a properly formed response
    wp_die();
}