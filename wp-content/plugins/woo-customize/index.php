<?php
/**
 * Plugin Name: Pod custome woocommerce
 * Plugin URI:
 * Description: Nguyen Dinh thang - teenighty team - web developer
 * Version: 1.2
 * Author:Nguyen Dinh thang
 * Author URI: https://fb.com/sauem97
 */
require_once(ABSPATH . 'wp-admin/includes/image.php');


function admin_woo_enqueue_scripts()
{
    if (!wp_script_is("jquery-local-js")) {
        wp_enqueue_script('jquery-local-js', plugin_dir_url(__FILE__) . 'js/jquery.min.js', array('jquery'), '', true);

    }
    if(!wp_script_is("alert-local-js")){
        wp_enqueue_script('alert-local-js', plugin_dir_url(__FILE__) . 'js/alert.min.js', array('jquery'), '', true);
    }
    if(!wp_script_is("handle-local-js")){
        wp_enqueue_script('handle-local-js', plugin_dir_url(__FILE__) . 'js/handlebars-v4.7.6.js', array('jquery'), '', true);
    }
    if(!wp_script_is("select2")){
        wp_enqueue_script('select2', plugin_dir_url(__FILE__) . 'js/select2.js', array('jquery'), '', true);
    }
    if(!wp_style_is("select2")){
        wp_enqueue_style('select2', plugin_dir_url(__FILE__) . 'css/select2.css');
    }

    wp_enqueue_script('woo-custom-js', plugin_dir_url(__FILE__) . 'js/woo-customize.js', array('jquery'), '', true);
    wp_enqueue_style('woo-style-css', plugin_dir_url(__FILE__) . 'css/woo-customize.css');
}

add_action('admin_enqueue_scripts', 'admin_woo_enqueue_scripts');


add_action('add_meta_boxes', 'disign_meta_box');
function disign_meta_box()
{
    $screens = array('product');
    foreach ($screens as $screen) {

        add_meta_box(
            'layers_child_meta_design',
            __('Ảnh mẫu sản phẩm ', 'layerswp'),
            'deisgn_meta_box_callback',
            $screen,
            'normal',
            'high'
        );
    }
}

function deisgn_meta_box_callback($post)
{
    include __DIR__ . "/temp/design.php";
}

function ajax_attributes(){
    global $wpdb;

    $attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;" );
    set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );

    $attribute_taxonomies = array_filter( $attribute_taxonomies  ) ;
    $result = [];
    foreach ($attribute_taxonomies as $k => $tax ){

        $result[$tax->attribute_name] = [
            'name' => $tax->attribute_name,
            'variants' => get_terms([
                'taxonomy' => "pa_$tax->attribute_name",
                'hide_empty' => false
            ])
        ];
    }
    wp_send_json_success($result);
    die();
}
//add_action( 'wp_ajax_nopriv_attributes', 'ajax_attributes' );
add_action('wp_ajax_attributes','ajax_attributes');