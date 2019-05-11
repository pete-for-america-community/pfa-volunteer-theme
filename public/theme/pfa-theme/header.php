<?php 
global $post;
$body_classes = 'v' . PFATHEME;
global $theme_options;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="wp-content/themes/pfa-theme/style.css">
    <!-- wp_head() --><?php wp_head(); ?><!-- /wp_head() -->
</head>

<body <?php body_class( $body_classes  ); ?>>

<header class="sticky">
    <nav class="navbar navbar-expand-lg bg-primary large">

        <!-- Responsive Nav menu trigger -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div id="navbarSupportedContent" class="nav-wrapper collapse navbar-collapse">
            <!-- Logo -->
            <div class="navbar-brand nav-logo">
                <a href="<?php bloginfo('url'); ?>">
                    <img src="<?php echo get_template_directory_uri() ?>/img/pfa-logo.svg" class="logo svg img-responsive">
                </a>
            </div>

            <!-- Main Navigation -->
            <div class="main-nav-wrapper nav-links">
                <?php
                if ( has_nav_menu('primary') ) {
                    wp_nav_menu( array(
                        'depth'          => '0',
                        'menu'           => 'primary',
                        'theme_location' => 'primary',
                        'menu_class'     => 'navbar-nav',
                        'walker'         => new WP_Bootstrap_Navwalker()
                    ) );
                }
                ?>
            </div><!-- /.main-nav-wrapper -->

            <div class="nav-donate">
                <div class="button-wrapper">
                    <a class="btn btn-block btn-transparent">Donate</a>
                </div>
            </div>

            <!-- Slide-out Side Navigation Placeholder
            <div class="side-nav-wrapper">
                <?php
                /* Placeholder
                if ( has_nav_menu('primary') ) {
                    wp_nav_menu( array(
                        'depth'             => ( !isset($fw_options['sidenav_submenu_toggle']) ) ? '0' : '1',
                        'menu'              => 'primary',
                        'theme_location'    => 'side',
                        'menu_class'        => 'side-nav bg-tertiary',
                        'menu_id'           => 'mobile',
                        'container_class'   => 'menu-side-nav',
                        'walker'            => new WP_Bootstrap_Navwalker()
                    ) );
                }
                */
                ?>
            </div> /.side-nav-wrapper -->
            
        </div><!-- /.nav-wrapper #navbarSupportedContent -->

    </nav>
</header><!-- /.sticky -->
<!-- /header.php -->
