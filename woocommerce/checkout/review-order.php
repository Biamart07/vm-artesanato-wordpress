<?php
/**
 * Review Order
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table w-full">
	<thead>
		<tr>
			<th class="product-name text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total text-right py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name py-4 px-4 font-texto text-cinzaescuro dark:text-white">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="product-total py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr class="cart-subtotal border-t border-marrom/30">
			<th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-t border-marrom/30">
				<th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee border-t border-marrom/30">
				<th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( $fee->name ); ?></th>
				<td class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-t border-marrom/30">
						<th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( $tax->label ); ?></th>
						<td class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total border-t border-marrom/30">
					<th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total border-t-2 border-marrom">
			<th class="py-4 px-4 text-left font-titulo text-xl text-cinzaescuro dark:text-white"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td class="py-4 px-4 text-right font-titulo text-xl text-cinzaescuro dark:text-white" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>

