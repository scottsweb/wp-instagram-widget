<ul class="<?php echo esc_attr( $ulclass ); ?>">
    <?php foreach ( $media_array as $item ) { ?>
        <?php include $item_template; ?>
    <?php } ?>
</ul>