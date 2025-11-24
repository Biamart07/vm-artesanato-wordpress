<?php
/**
 * Template Name: Carrinho
 * Template para página de carrinho do WooCommerce
 * Este template será usado automaticamente quando is_cart() for true
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
        
        // Carregar o template do carrinho diretamente
        // Quando usado como Template Name, precisamos carregar o template manualmente
        $cart_template = locate_template('woocommerce/cart/cart.php');
        
        if ($cart_template && file_exists($cart_template)) {
            // Incluir o template do carrinho
            include($cart_template);
        } else {
            // Fallback: usar woocommerce_content() se o template não for encontrado
            woocommerce_content();
        }
    } else {
        // Fallback caso WooCommerce não esteja ativo ou carrinho não inicializado
        ?>
        <section class="border-y-2 border-dashed border-marrom flex flex-col items-center py-10 min-h-screen" id="carrinho">
            <div class="max-w-6xl mx-auto px-4 w-full text-center">
                <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white mb-4">Carrinho de Compras</h2>
                <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro mx-auto"></div>
                <?php if (function_exists('WC') && WC()->cart && WC()->cart->is_empty()) : ?>
                    <p class="text-cinzaescuro dark:text-white mt-8">Seu carrinho está vazio.</p>
                <?php else : ?>
                    <p class="text-cinzaescuro dark:text-white mt-8">WooCommerce não está ativo ou carrinho não inicializado.</p>
                <?php endif; ?>
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

