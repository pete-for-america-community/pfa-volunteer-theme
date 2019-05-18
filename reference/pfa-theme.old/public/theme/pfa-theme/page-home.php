<?php
/* 
Template Name: Home Page
*/
namespace PFA;
?>

<?php
// Load custom post metadata and fw settings
$meta = get_post_meta_single( $post->ID );
$theme_options = get_option( 'theme_options' );

// Additional Variable Processing
?>

<?php locate_template( 'header.php', TRUE ); ?>

<!-- Home Page Loop -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <!-- main content placeholder, not sure what we'll use this for <div class="entry-content"><?php the_content(); ?></div> -->
<main>

    <section class="hero bg-primary">
        <div class="content">
            <h1 class="title">We are <span>#TeamPete</span></h1>
            <p class="text-contrast">
            This is the online home of the Pete For America grassroots
            volunteer community. By volunteers for volunteers. We’re a diverse
            group of engaged Americans who believe 2020 isn’t just about winning
            an election, it’s about winning the era.We believe Pete Buttigieg is the
            candidate to do it.
            </p>

        </div>
        <div class="contact-form bg-secondary">
            <div class="title">
                Join the GrassRoots Team
            </div>
            <div class="contact-form-wrapper">
                <?php 
                    echo "Contact Form Placeholder"; //do_shortcode('[contact-form-7 title="signup"]');
                ?>
            </div>    
        </div>
    </section>

    <div class="divider bg-secondary solid"></div>

    <section class="action-network bg-white">
        <div class="header">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                    <h2 class="title">Find Your Local Group</h2>
                        <div class="interface">
                            <p>Map Controls Placeholder (this section will house a map, long term)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="map">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-10 offset-sm-1">
                        <?php echo do_shortcode('[actionnetwork id=3]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="divider bg-secondary solid"></div>

    <section class="cta values bg-white">
        <div class="cta-image">
            <img src="https://picsum.photos/930/700" class="img-responsive" />
        </div>
        <div class="content-wrapper">
            <div class="content">
                <h2 class="title">Rules For The Road & Other Resources</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                <ul class="values-list">
                    <li><i class="far fa-check-circle"></i>SUBSTANCE</li>
                    <li><i class="far fa-check-circle"></i>BOLDNESS</li>
                    <li><i class="far fa-check-circle"></i>RESPECT</li>
                    <li><i class="far fa-check-circle"></i>NONCONFORMITY</li>
                    <li><i class="far fa-check-circle"></i>TRUTH</li>
                    <li><i class="far fa-check-circle"></i>DISCIPLINE</li>
                    <li><i class="far fa-check-circle"></i>JOY</li>
                </ul>
            </div>
        </div>

    </section>

    <div class="divider bg-secondary solid"></div>

    <section class="cta categories bg-white">
        <div class="content-wrapper">
            <div class="content">
                <h2 class="title mb-5">Find Or Create An Event</h2>
                <ul class="values-list">
                    <li><i class="far fa-check-circle"></i>Category 1</li>
                    <li><i class="far fa-check-circle"></i>Category 2</li>
                    <li><i class="far fa-check-circle"></i>Category N</li>
                    <li><i class="far fa-check-circle"></i>Category N</li>
                    <li><i class="far fa-check-circle"></i>Category N</li>
                    <li><i class="far fa-check-circle"></i>Category N</li>
                    <li><i class="far fa-check-circle"></i>Category N</li>
                </ul>
                <div class="button-wrapper bg-secondary">
                    <a class="btn btn-block">Catch All CTA!</a>
                </div>
            </div>
        </div>
        <div class="cta-image">
            <img src="https://picsum.photos/930/700" class="img-responsive" />
        </div>
    </section>

    <div class="divider bg-secondary solid"></div>

    <section class="feed bg-white">
        <h2 class="title">PFA On The Twitters</h2>
        <div class="slider-wrapper">
            <div class="tweet-slider">

                <?php
                $tweet_card = '
                <div class="tweet-card-wrapper">
                    <div class="tweet-card">
                        <div class="title">@Neighbors4Pete</div>
                        <div class="tweet-content">
                            <p>Lorem ipsum dolor sit amet, consectetur
                        adipisicing elit, sed do eiusmod tempor
                        incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip ex
                        ea commodo consequat m dolor 
                            </p>
                        </div>
                        <div class="details">
                            <div class="twitter-icon"><i class="fab fa-twitter"></i></div>
                            <div class="date">05/03/2019</div>
                        </div>
                    </div>
                </div>';

                for( $i = 0; $i <12; $i++ ) {
                    echo $tweet_card;
                }
                ?>

            </div>
        </div>

    </section>

    <div class="divider bg-secondary solid"></div>

    <section class="action bg-white">
        <div class="slider bg-primary">
            <div class="background"></div>
            <div class="content bg-white">
                <h3 class="title">#TeamPete</h3>
                <h4 class="subtitle">In Action</h4>
                <p>A live/rotating gallery of pictures and video of our everwiddening grassroots efforts on-the-ground.</p>
                <div class="button-wrapper bg-secondary">
                    <a class="btn btn-block">See Full Gallery</a>
                </div>
            </div>
        </div>
    </section>

    <div class="divider bg-secondary solid"></div>

</main>

<?php endwhile; else : ?>
    <p>Sorry, no page found in database!</p>
<?php endif; ?>
<!-- /End Home Page Loop -->

<?php locate_template( 'footer.php', TRUE ); ?>
