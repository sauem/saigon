<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.2
 */

?>
<div class="<?= is_home() ? "" : "container"?>">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            wp_nav_menu([
                'menu'=> 'head',
                'echo' => true,
                'items_wrap' => '<ul class="navbar-nav mr-auto">%3$s</ul>',
                'depth' => 1
            ]);
            ?>
        </div>
    </nav>
</div>