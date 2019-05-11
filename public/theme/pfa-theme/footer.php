<?php
namespace PFA;
global $theme_options; 
?>

<footer class="bg-white">
    <div class="content-wrapper">
        <h2 class="title">#PETE FOR AMERICA</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniamquis.</p>
            
        <div class="links">
            <div class="link-column">
                <div class="list-header">Join the Movement</div>
                <ul>
                    <li><a>Find Your Local Group</a></li>
                    <li><a>Find a Local Event</a></li>
                    <li><a>Find An Affinity Event</a></li>
                    <li><a href="http://peteswag.com">Buy Pete Swap</a></li>
                </ul>
            </div>
            <div class="link-column">
                <div class="list-header">Take Action</div>
                <ul>
                    <li><a>Register A Group</a></li>
                    <li><a>Register An Event</a></li>
                    <li><a>Give</a></li>
                </ul>
            </div>
            <div class="link-column">
                <div class="list-header">Find Resources</div>
                <ul>
                    <li><a>Rules For The Road</a></li>
                    <li><a>Design Tool Kit</a></li>
                    <li><a>Support Guides & Worksheets</a></li>
                    <li><a>Other Stuff</a></li>
                </ul>
            </div>
            <div class="link-column">
                <div class="list-header">Change The Channel To:</div>
                <ul>
                    <li><a href="http://meetpete.org" target="_blank">MeetPete</a></li>
                    <li><a href="https://www.youtube.com/channel/UC6lSCiN9dUVym1hATwQlmvA" target="_blank">YouTube for Pete</a></li>
                    <li><a href="http://petedaily.com" target="_blank">Pete Daily</a></li>
                    <li><a href="http://hearpetespeak.com" target="_blank">HearPeteSpeak.com</a></li>
                </ul>
            </div>
        </div><!-- /.links -->

        <div class="sm-icons">
            <ul>
                <li><a href="" taget="_blank"><i class="fa fa-globe"></i></a></li>
                <li><a href="" taget="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="" taget="_blank"><i class="fab fa-facebook"></i></a></li>
                <li><a href="" taget="_blank"><i class="fab fa-instagram"></i></a></li>
                <li><a href="" taget="_blank"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>

        <div class="legal">
            <div class="terms">
                <p>Terms and Conditions</p>
                <p>Legal</p>
            </div>
            <div class="copyright">
                <p class="copyright">&copy;&nbsp;&nbsp;<?php the_time('Y')?></p>
                <p>| All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>
<div class="divider bg-secondary solid"></div>
<!-- Modals -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwRVrS8bDrcRWsYblL-f-EX0i1ECVfkSY&callback=initMap&libraries=places">
</script>
<script type="text/javascript" src="wp-content/themes/pfa-theme/js/map.js"></script>
<script type="text/javascript" src="wp-content/themes/pfa-theme/js/scripts.js"></script>

<!-- wp_footer() output -->
<?php wp_footer(); ?>
<!-- END wp_footer() output -->

</body>
</html>
<!--/footer.php -->