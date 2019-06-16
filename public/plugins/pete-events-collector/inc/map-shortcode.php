<?php

define( 'EVENTMAPPER_PATH', 'map/eventmapper-client' );
 
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
    
    // Capture all shortcode output to a buffer for potential filtering
    ob_start();
    ?>
    <!-- Expose events API to the front end -->
    <script>
        var <?php echo $args['cache_var_name']; ?> = <?php echo json_encode( get_option( EVENTS_COLLECTOR_SETTING_NAME ) ); ?>
    </script>

    <!-- EventsMap output -->
    <div class=<?php echo implode( " ", $args['output_wrapper_classes']); ?>>
    
        <!-- Load custom map DOM -->
        <?php require_once plugin_dir_path( __FILE__ ) . "map/" . "map-partial.php"; ?>
        <!-- End custom map DOM -->

        <p id="ajaxPlaceholder">Map Content Placeholder</p>

        <!-- Load custom map JS -->
        <script src="<?php echo plugins_url( EVENTMAPPER_PATH . "/infobubble-compiled.js", __FILE__ );  ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url( EVENTMAPPER_PATH . "/eventmapper.js", __FILE__ );  ?>"></script>
        <!-- End custom map JS -->
 
    </div>
    <!-- /EventsMap output -->

    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
add_shortcode( 'EventsMap', 'register_map_shortcode' );
