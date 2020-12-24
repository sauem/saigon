<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?= ASSET ?>/css/owl.carousel.min.css" async/>
    <link rel="stylesheet" type="text/css" href="<?= ASSET ?>/css/owl.theme.default.min.css" async/>
    <link rel="stylesheet" type="text/css" href="<?= ASSET ?>/css/teen.css" async/>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="gradient-divider fixed-header">
    <div class="container">
        <?= get_template_part("template-parts/navigation/navigation", "top") ?>
    </div>
</header>
<div class="<?= (is_home() || is_single()) ? "container" : "container-fluid"?> light" id="wrap">
    <?= get_template_part("template-parts/navigation/navigation", "bottom") ?>

