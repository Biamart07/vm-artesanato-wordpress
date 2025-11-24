<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// Se o checkout não está disponível, retornar
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<section class="border-b-2 border-dashed border-marrom flex flex-col items-center py-10 min-h-screen" id="checkout">
	<h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center mb-4">Finalizar Compra</h2>
	<div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro mx-auto"></div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout max-w-6xl mx-auto px-4 w-full mt-8" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set grid grid-cols-1 lg:grid-cols-2 gap-8 px-2" id="customer_details">
				<div class="col-1 px-2">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2 px-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order mt-8">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</section>

