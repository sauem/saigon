<?php
define("ASSET", get_template_directory_uri() . "/assets/");

add_theme_support('post-thumbnails');
show_admin_bar(false);
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    if(in_array('menu-item-has-children', $classes)){
        $classes[] = 'has-children dropdown';
    }
    $classes[] = 'nav-item';
    return $classes;
}
add_filter( 'nav_menu_link_attributes', 'filter_function_name', 10, 3 );

function filter_function_name( $atts, $item, $args ) {
    $atts['class'] = 'nav-link';
    return $atts;
}


function change_submenu_class($menu) {
    $menu = preg_replace('/ class="sub-menu"/','/ class="dropdown-menu" /',$menu);
    return $menu;
}
add_filter('wp_nav_menu','change_submenu_class');


function neonButton($href = "#", $text = "neon")
{
    return "<a href=\"$href\"><span></span> <span></span><span></span><span></span>$text</a>";
}