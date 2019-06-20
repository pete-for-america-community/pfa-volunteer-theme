<?php

define( 'EVENTMAPPER_PATH', 'pete-events-collector/inc/map/eventmapper-client' );

/**
 * Registers a shortcode to display an events map which can be referenced outside the WP Admin interfaces
 * 
 * Usage: 
 * [EventsMap] in Admin interface context 
 * <?php do_shortcode( '[EventsMap]' ); ?> in PHP context
 * 
 * @param $args Array initial configuration options 
 *
 * @return false|string
 */
function register_map_shortcode( $args ){

    // Handle merging default and incoming configurations
    $defaults = array(
        'temp_setting_placeholder'      => TRUE,
        'map_id'                        => 1,
        'allowed_sources'               => Array(  ),
        'output_wrapper_classes'        => Array( "events-map", "custom-css-class" ),
        'map_src'                       => 'events-map-script.js',
        'cache_var_name'                => FRONT_END_API_VAR_NAME

    );
    $args = wp_parse_args( $args, $defaults );

    global $EventsManager;
    
    // Capture all shortcode output to a buffer for potential filtering
    ob_start();
    ?>
    <!-- Inline CSS for map wrapper -->
    <style>.events-map { height: 400px; }</style>

    <!-- Expose events API to the front end -->
    <script>
        var <?php echo $args['cache_var_name']; ?> = <?php echo $EventsManager->getEventsJSON(); ?>;
        var pluginPath = "<?php echo plugins_url( EVENTMAPPER_PATH . '/', "map-options.json" ); ?>";
    </script>

    <!-- EventsMap output -->
    <div class=<?php echo implode( " ", $args['output_wrapper_classes']); ?>>
    
        <!-- Load custom map DOM -->
        <div id="map"></div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://apis.google.com/js/api.js"></script>
        <script type="text/javascript" src="<?php echo plugins_url( EVENTMAPPER_PATH . "/infobubble-compiled.js" );  ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url( EVENTMAPPER_PATH . "/eventmapper.js" );  ?>"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=visualization&callback=initMap" async defer></script>
        <link href="<?php echo plugins_url( EVENTMAPPER_PATH . "/eventmapper.css" );  ?>" rel="stylesheet" type="text/css">
        <!-- End custom map DOM -->
                
        <!-- End custom map JS -->

 
    </div>
    <!-- /EventsMap output -->

    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
add_shortcode( 'EventsMap', 'register_map_shortcode' );
