<?php
namespace PFA;

/**
 * Load in a Bootstrap-standardized Nav Walker to override WP Core
 */

if ( ! file_exists( get_template_directory() . '/lib/vendor/class-wp-bootstrap-navwalker.php' ) ) {
    return new WP_Error( 'class-wp-bootstrap-navwalker-missing', 'It appears the class-wp-bootstrap-navwalker.php file is missing.  Check the lib/vendor directory.' );
} else {
    require_once get_template_directory() . '/lib/vendor/class-wp-bootstrap-navwalker.php';
}