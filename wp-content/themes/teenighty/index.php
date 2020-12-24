<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); ?>
    <section class="box slider">
        <div class="owl-carousel slider-index owl-theme owl-loaded">
            <div style="background-image: url(<?= ASSET ?>images/h1.jpg)" class="slider-item">
                <div class="text">
                    <h1 class="silde-title neon size-50">Title</h1>
                    <p class="slide-description">Description</p>
                </div>
            </div>
            <div style="background-image: url(<?= ASSET ?>images/h2.jpeg)" class="slider-item">
                <div class="text">
                    <h1 class="neon size-50 silde-title">Title</h1>
                    <p class="slide-description">Description</p>
                </div>
            </div>
        </div>
    </section>
    <section class="box">
        <div class="box-title">
            <h2 class="neon">Neon Special</h2>
        </div>
        <div class="row">
            <?php
            query_posts([
                'posts_per_page' => 6,
                'sort' => 'ASC',
                'orderby' => 'date',
                'post_type' => 'product'
            ]);
            if (have_posts()) : while (have_posts()) :the_post();
                global $product;
                ?>
            <div class="col-md-4">
                <div class="box item-box">
                    <div class="img">
                        <img class="img-fluid" src="<?= get_the_post_thumbnail_url()?>"/>
                    </div>
                    <div class="text">
                        <h5 class="text-2"><a href="<?= get_the_permalink()?>"><?= get_the_title()?></a></h5>
                        <span><?= $product->get_price_html()?></span>
                    </div>
                </div>
            </div>
            <?php
            endwhile; endif;
            wp_reset_query();
            ?>
            <div class="col-12 text-center">
                <a class="btn btn-outline-warning button-neon" href="<?= home_url("/shop")?>">Xem thêm</a>
            </div>
        </div>
    </section>
    <section class="box">
        <div class="box-title">
            <h2 class="neon">Neon Libraries</h2>
        </div>
        <div class="owl-carousel owl-theme article">
            <?php
            query_posts([
                'posts_per_page' => 6,
                'order' => 'ASC',
                'orderby' => 'date',
                'post_type' => 'project_done'
            ]);
            if (have_posts()) : while (have_posts()) :the_post();
                ?>
                <div class="box item-box">
                    <div class="img">
                        <img class="img-fluid" src="<?= get_the_post_thumbnail_url()?>"/>
                    </div>
                    <div class="text">
                        <h5 class="text-2"><a href="<?= get_the_permalink()?>"><?= get_the_title()?></a></h5>
                        <p class="text-3"><?= get_the_excerpt()?></p>
                    </div>
                </div>
            <?php
            endwhile; endif;
            wp_reset_query();
            ?>
        </div>
    </section>
<?php
get_footer();
