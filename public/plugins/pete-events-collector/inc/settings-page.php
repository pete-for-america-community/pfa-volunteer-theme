<?php

// temporary
$output_content = get_option('events_collector_options');

$spinner = '<div class="spinner"></div>';
?>
<div class="wrap events-collector">

    <h2>
        <?php esc_html_e( 'Events API Collector' ); ?>
    </h2>
    <sub>Built for the Pete For America Community Site</sub>
    
    <form>
        <input type="hidden" name="action" value="events_collector_settings">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="input-text">Events Cache</label>
                    <hr><br>
                    <button id="displayResults" class="button-secondary">See Current Cache<?php echo $spinner; ?></button>
                    <button id="clearCache" class="button-secondary">Clear Cache<?php echo $spinner; ?></button>
                    <button id="regenerateCache" class="button-secondary">Regenerate Cache<?php echo $spinner; ?></button>
                    <br><hr><br>
                    <input id="manualEndpoint" type="text">
                    <button id="manualEndpointButton" class="button-secondary">Manual Endpoint Check<?php echo $spinner; ?></button>
                </th>
                <td>
                    <textarea rows="30" cols="100" id="events-cache-output"><?php print_r( wp_unslash( json_encode( $output_content ) ) ); ?></textarea>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

</div><!-- .wrap -->
