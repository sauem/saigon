<?php
/**
 * Plugin Name: Crawl image product
 * Plugin URI:
 * Description: Hello guy
 * Version: 1.0
 * Author: Dinh thang
 * Author URI:
 */

require("simple_html_dom.php");
require_once(ABSPATH . 'wp-admin/includes/image.php');
add_action('add_meta_boxes', 'layers_child_add_meta_box');
function layers_child_add_meta_box()
{
    $screens = array('product');
    foreach ($screens as $screen) {

        add_meta_box(
            'layers_child_meta_sectionid',
            __('Crawl ảnh sản phẩm', 'layerswp'),
            'layers_child_meta_box_callback',
            $screen,
            'normal',
            'high'
        );
    }
}

function layers_child_meta_box_callback($post)
{

    wp_nonce_field('layers_child_meta_box', 'layers_child_meta_box_nonce');
    $url = get_post_meta($post->ID, 'thumbnail_url', true);
    ?>
    <div class="row">
        <div class="col-4">
            <label>Link lấy dữ liệu sản phẩm:</label>
            <br>
            <input autocomplete name="url" type="text"> <br>
            <label>CSS class wraper:</label>
            <br>
            <input autocomplete" name="css_element" placeholder="css element wrap" type="text">
            <br>
            <label><input checked type="checkbox" name="save_css"> Lưu class</label>
            <hr>
            <button style="margin-top: 10px;float: right" id="btnCrawler" type="button" class="button">Lấy dữ liệu
            </button>
        </div>
        <div class="col-8" id="result-wrap">
            <button id="selectAll" type="button" class="button">Chọn tất cả</button>
            <button id="applyAll" type="button" class="button">Áp dụng</button>
            <hr>
            <div id="result-crawl">

            </div>
        </div>
    </div>
    <script id="item-template" type="text/x-handlebars-template">
        {{#each this}}
        <div class="item-box">
            <input data-key="{{@index}}" type="checkbox">
            <img width="200" class="item-image" src="{{this}}"/>
        </div>
        {{/each}}
    </script>
    <script id="thumb-template" type="text/x-handlebars-template">
        {{#each this}}
        <li class="image" data-attachment_id="{{this.id}}">
            <img src="{{this.src}}">
            <ul class="actions">
                <li><a href="#" class="delete" title="Xóa ảnh">Xoá</a></li>
            </ul>
        </li>
        {{/each}}
    </script>
    <?php
}

function admin_crawler_enqueue_scripts()
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

   wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'js/crawl.js', array('jquery'), '', true);
    wp_enqueue_style('style-css', plugin_dir_url(__FILE__) . 'css/crawl.css');
}

add_action('admin_enqueue_scripts', 'admin_crawler_enqueue_scripts');


function getImage($url, $dom)
{
    $thumbs = [];
    if ($url && $dom) {
        $html = file_get_html($url);
        $html->outertext = '';
        $html->load($html->save());

        $list = $html->find(".$dom", 0)->find("img");
        $removeSize = ["-10x10", "-150x150", "-100x100", "-300x300", "-600x600", "-200x200", '-250x250', '-300x300'];
        if ($list) {
            foreach ($list as $k => $img) {
//                $src = str_replace("//", "", $img->src);
//                $src = !strpos($src, "https://") ? "https://" . $src : $src;
//                $src = strpos($src, "https:") ? "" . $src : $src;

                $src = addhttp($img->src);
                foreach ($removeSize as $size) {
                    $src = str_replace($size, "", $src);
                    $src = str_replace("////", "//", $src);
                }
                $thumbs[$k] = $src;
            }
        }
        return [
            'thumbs' => $thumbs,
            'content' => $url,
            'name' => $dom
        ];
    }

    return [
        "thumbs" => [],
        "content" => "",
        "name" => ""
    ];
}

function addhttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function TH_crawler()
{
    $url = $_POST['url'] ? esc_attr($_POST['url']) : "";
    $dom = $_POST['dom'] ? esc_attr($_POST['dom']) : "";
    try {
        $data = getImage($url, $dom);
        wp_send_json_success([
            'success' => 1,
            'result' => $data
        ]);
    } catch (\Exception $e) {
        wp_send_json([
            'success' => 0,
            'msg' => $e->getMessage()
        ]);
    }

    wp_die();
}

add_action('wp_ajax_crawler', 'TH_crawler');
add_action('wp_ajax_nopriv_crawler', 'TH_crawler');


function TH_save_thumbnail()
{

    $thumbs = $_POST['thumbs'] ? $_POST['thumbs'] : [];
    $uploadDir = wp_upload_dir()['path'];
    if (!$thumbs) {
        wp_send_json_error(['msg' => 'Không có hình ảnh nào được chọn']);
        wp_die();
    }
    try {
        $files = [];
        foreach ($thumbs as $k => $thumb) {
            $name = basename($thumb);
            $fileDir = $uploadDir . "/$name";
            $res = file_put_contents($fileDir, file_get_contents($thumb));
            if (!$res) {
                wp_send_json_error(['msg' => 'Lỗi upload ảnh!']);
            }
            $wp_filetype = wp_check_filetype($name, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($name),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $fileDir);
            $attach_data = wp_generate_attachment_metadata($attach_id, $fileDir);
            wp_update_attachment_metadata($attach_id, $attach_data);
            $files[$k] = [
                'id' => $attach_id,
                'src' => wp_get_attachment_image_url($attach_id, 'thumbnail')
            ];
        }
        wp_send_json_success([
            'msg' => 'Thành công!',
            'files' => $files
        ]);

    } catch (\Exception $exception) {
        wp_send_json_error([
            'msg' => $exception->getMessage()
        ]);
    }
    wp_die();
}

add_action('wp_ajax_apply_thumbs', 'TH_save_thumbnail');
add_action('wp_ajax_nopriv_apply_thumbs', 'TH_save_thumbnail');

add_action('add_attachment', function ($attachmentID) {
    if (!class_exists('WC_Product')) return; // if no WooCommerce do nothing

    // an attachment was jus saved, first of all get the file name
    $src = wp_get_attachment_image_src($attachmentID, 'full');
    $filename = pathinfo($src[0], PATHINFO_FILENAME);

    // now let's see if exits a product with the sku that match filename
    $args = array(
        'meta_key' => '_sku',
        'meta_value' => $filename,
        'post_type' => 'product',
        'posts_per_page' => '1' // assuming sku is unique get only one post
    );
    $prods = get_posts($args);
    if (!empty($prods)) {

        // ok we have a match, exists a product having sku that match filename
        $product = array_pop($prods);

        // set the thumbnail for the product
        set_post_thumbnail($product, $attachmentID);

        // now "attach" the post to the product setting 'post_parent'
        $attachment = get_post($attachmentID);
        $attachment->post_parent = $product->ID;
        wp_update_post($attachment);
    }
});