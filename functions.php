<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
function my_theme_scripts() {
    wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array('jquery'), '1.11.2', true);
    wp_enqueue_script('jquery-ui');
    wp_register_script('tabs-scripts', get_stylesheet_directory_uri() . '/js/tabs-script.js', array('jquery', 'jquery-ui'), '1.0', true);
    wp_enqueue_script('tabs-scripts');
    wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

unset( $tabs['reviews'] ); // Remove the reviews tab


return $tabs;

}
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' ); // Remove the add to cart button
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );



function translate_woocommerce($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        switch ($text) {
            case 'SKU':
                $translation = 'Cutsheet #:';
                break;
            case 'SKU:':
                $translation = 'Cutsheet #:';
                break;
        }
    }
    return $translation;
}

add_filter('gettext', 'translate_woocommerce', 10, 3);



add_action( 'woocommerce_single_product_summary', 'custom_single_product_summary_actions', 1 );
function custom_single_product_summary_actions(){

    // Reordering the price (done by you)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 45 );

    // Put back the SKU:
    add_action( 'woocommerce_single_product_summary', 'woocommerce_single_custom_sku', 9 );
    function woocommerce_single_custom_sku(){
        global $product;

        if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
        ?>
            <p><span class="sku_wrapper1"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span></p>
        <?php
        endif;
    }
}


