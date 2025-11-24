<?php
/**
 * Template Name: Checkout
 * Template para página de checkout do WooCommerce
 * Este template será usado automaticamente quando is_checkout() for true
 */
get_header(); ?>

<main>
    <?php
    // Verificar se WooCommerce está ativo e inicializar se necessário
    if (function_exists('WC')) {
        // Garantir que o carrinho está inicializado
        if (!WC()->cart) {
            wc_load_cart();
        }
        
        // Verificar se há produtos no carrinho
        if (WC()->cart->is_empty()) {
            // Se o carrinho estiver vazio, redirecionar para a loja
            $shop_url = function_exists('vm_artesanato_get_shop_url') ? vm_artesanato_get_shop_url() : home_url('/produtos');
            wp_safe_redirect($shop_url);
            exit;
        }
        
        // Carregar o template do checkout diretamente
        // Quando usado como Template Name, precisamos carregar o template manualmente
        $checkout_template = locate_template('woocommerce/checkout/form-checkout.php');
        
        if ($checkout_template && file_exists($checkout_template)) {
            // Incluir o template do checkout
            // O template precisa do objeto $checkout
            if (!isset($checkout)) {
                $checkout = WC()->checkout();
            }
            include($checkout_template);
        } else {
            // Fallback: usar woocommerce_content() se o template não for encontrado
            woocommerce_content();
        }
    } else {
        // Fallback caso WooCommerce não esteja ativo ou checkout não inicializado
        ?>
        <section class="border-y-2 border-dashed border-marrom flex flex-col items-center py-10 min-h-screen" id="checkout">
            <div class="max-w-6xl mx-auto px-4 w-full text-center">
                <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white mb-4">Finalizar Compra</h2>
                <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro mx-auto"></div>
                <p class="text-cinzaescuro dark:text-white mt-8">WooCommerce não está ativo ou checkout não inicializado.</p>
                <?php
                $shop_url = function_exists('vm_artesanato_get_shop_url') ? vm_artesanato_get_shop_url() : home_url('/produtos');
                ?>
                <a href="<?php echo esc_url($shop_url); ?>" class="no-underline inline-block px-8 py-2.5 mt-4 text-sm bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white rounded-full">
                    Continuar Comprando
                </a>
            </div>
        </section>
        <?php
    }
    ?>
</main>

<?php get_footer(); ?>

