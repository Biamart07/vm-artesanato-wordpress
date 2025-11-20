<?php
/**
 * Proceed to checkout button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> no-underline px-8 py-3 text-sm bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white rounded-full font-semibold">
	<?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?>
</a>

