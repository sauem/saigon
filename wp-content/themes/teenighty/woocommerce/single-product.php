<?php
get_header();
global $product;
if (!is_object($product)) $product = wc_get_product(get_the_ID());
if ($product->is_type('variable')) {
    $available_variations = $product->get_available_variations();
    $variations_json = wp_json_encode($available_variations);
    $variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
}
$thumbs = $product->get_gallery_image_ids();
?>
    <section class="box">
        <div class="row">
            <div class="col-md-7">
                <div class="big-image">
                    <img src="<?= get_the_post_thumbnail_url() ?>" class="img-fluid rounded"/>
                </div>
                <?php if ($thumbs) : ?>
                    <div class="owl-carousel list-thumbs">
                        <?php foreach ($thumbs as $id) : ?>
                            <a data-img="<?= wp_get_attachment_url($id) ?>" href="javascript:void(0)" class="thumbitem">
                                <img src="<?= wp_get_attachment_url($id) ?>" class="img-fluid">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-5">
                <form class="cart"
                      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                      method="post" enctype='multipart/form-data'>
                    <h4 class=""> <?= get_the_title() ?></h4>
                    <p><?= nl2br(get_the_excerpt()) ?></p>
                    <h5>
                        <?= $product->get_price_html() ?>
                    </h5>
                    <div class="mb-5">
                        <?php

                        woocommerce_quantity_input(array(
                            'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                            'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                            'classes' => apply_filters('woocommerce_quantity_input_classes', array('form-control', 'text-center', 'input-text', 'qty', 'text'), $product),
                            'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                        ));

                        ?>
                    </div>
                    <button type="submit"
                            class="buy-now btn btn-sm btn-primary single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>"/>
                    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>"/>
                    <input type="hidden" name="variation_id" class="variation_id" value="0"/>
                </form>
            </div>
        </div>
    </section>
<?php
get_footer();
