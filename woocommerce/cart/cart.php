<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<section class="border-b-2 border-dashed border-marrom my-2 flex flex-col items-center py-10 min-h-screen" id="carrinho">
        <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center mb-4">Carrinho de Compras</h2>
        <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro"></div>

        <form class="woocommerce-cart-form max-w-6xl mx-auto px-4 w-full" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full mt-8" cellspacing="0">
                <thead class="hidden md:table-header-group">
                    <tr class="border-b-2 border-marrom">
                        <th class="product-remove text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
                        <th class="product-thumbnail text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-name text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-price text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                        <th class="product-quantity text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                        <th class="product-subtotal text-left py-4 px-4 font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>
                            <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> border-b border-marrom/30">
                                <td class="product-remove py-4 px-4">
                                    <?php
                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove text-marromhover hover:text-marrom dark:text-verde dark:hover:text-white transition-colors duration-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove this item', 'woocommerce' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </td>

                                <td class="product-thumbnail py-4 px-4">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                    }
                                    ?>
                                </td>

                                <td class="product-name py-4 px-4" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="text-cinzaescuro dark:text-white hover:text-marromhover dark:hover:text-verde transition-colors duration-300 no-underline">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                    // Meta data.
                                    echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                    // Backorder notification.
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                    }
                                    ?>
                                </td>

                                <td class="product-price py-4 px-4" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                    ?>
                                </td>

                                <td class="product-quantity py-4 px-4" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                    ?>
                                </td>

                                <td class="product-subtotal py-4 px-4 font-semibold text-cinzaescuro dark:text-white" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                    <tr>
                        <td colspan="6" class="actions py-4 px-4">
                            <?php if ( wc_coupons_enabled() ) { ?>
                                <div class="coupon flex flex-col md:flex-row gap-4 mb-4">
                                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
                                    <input type="text" name="coupon_code" class="input-text px-4 py-2 border border-marrom rounded-md" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                                    <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            <?php } ?>

                            <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white px-6 py-2 rounded-full" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>

                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </td>
                    </tr>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

        <div class="cart-collaterals max-w-6xl mx-auto px-4 w-full mt-8">
            <div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
                <?php do_action( 'woocommerce_before_cart_totals' ); ?>

                <h2 class="text-2xl font-titulo text-cinzaescuro dark:text-white mb-4"><?php esc_html_e( 'Cart totals', 'woocommerce' ); ?></h2>

                <table cellspacing="0" class="shop_table shop_table_responsive w-full">
                    <tbody>
                        <tr class="cart-subtotal border-b border-marrom/30">
                            <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                            <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_subtotal_html(); ?></td>
                        </tr>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-b border-marrom/30">
                                <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                                <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

                            <tr class="shipping border-b border-marrom/30">
                                <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
                                <td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php woocommerce_shipping_calculator(); ?></td>
                            </tr>

                        <?php endif; ?>

                        <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                            <tr class="fee border-b border-marrom/30">
                                <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( $fee->name ); ?></th>
                                <td data-title="<?php echo esc_attr( $fee->name ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                            $taxable_address = WC()->customer->get_taxable_address();
                            $estimated_text  = '';

                            if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                                /* translators: %s location. */
                                $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                            }

                            if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                    ?>
                                    <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-b border-marrom/30">
                                        <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                        <td data-title="<?php echo esc_attr( $tax->label ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr class="tax-total border-b border-marrom/30">
                                    <th class="py-4 px-4 text-left font-texto text-cinzaescuro dark:text-white"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                    <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>" class="py-4 px-4 text-right font-texto text-cinzaescuro dark:text-white"><?php wc_cart_totals_taxes_total_html(); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

                        <tr class="order-total border-t-2 border-marrom">
                            <th class="py-4 px-4 text-left font-texto text-lg font-semibold text-cinzaescuro dark:text-white"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                            <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>" class="py-4 px-4 text-right font-texto text-lg font-semibold text-cinzaescuro dark:text-white"><?php wc_cart_totals_order_total_html(); ?></td>
                        </tr>

                        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
                    </tbody>
                </table>

                <div class="wc-proceed-to-checkout mt-6">
                    <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                </div>

                <?php do_action( 'woocommerce_after_cart_totals' ); ?>
            </div>
        </div>

        <?php do_action( 'woocommerce_after_cart_collaterals' ); ?>
    </section>

<?php do_action( 'woocommerce_after_cart' ); ?>

