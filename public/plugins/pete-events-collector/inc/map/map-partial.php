<?php 
/** 
 * Any DOM elements required by the front-end JS
 * 
 * Note: This is the core output of eventMapper-client/eventmapper.html
 * 
 * GOOGLE_MAPS_API_KEY is a constant defined by the plugin to keep the real maps key 
 * private from the repo, but note that in this scenario it will still be public on 
 * the front-end when rendered. 
 * 
 * @todo We need a better way to obfuscate the Google key
 */

?><div id="map"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://apis.google.com/js/api.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=visualization&callback=initMap" async defer></script>