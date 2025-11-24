<?php
/**
 * Template WooCommerce
 * Usa page-produtos.php para shop, categorias e tags
 * Usa woocommerce/cart/cart.php para a página de carrinho
 */

// Verificar se WooCommerce está ativo e carregado
if (!function_exists('WC')) {
    // Se WooCommerce não estiver carregado, usar template padrão
    get_header();
    ?>
    <main>
        <section class="border-b-2 border-dashed border-marrom flex flex-col items-center py-10">
            <div class="max-w-7xl mx-auto px-4 w-full">
                <?php woocommerce_content(); ?>
            </div>
        </section>
    </main>
    <?php
    get_footer();
    exit;
}

// Verificar se é página de loja (shop), categoria ou tag de produto
$is_shop_page = (function_exists('is_shop') && is_shop()) || 
                (function_exists('is_product_category') && is_product_category()) || 
                (function_exists('is_product_tag') && is_product_tag());

if ($is_shop_page) {
    // Carregar o template customizado page-produtos.php
    $custom_template = locate_template('page-produtos.php');
    if ($custom_template && file_exists($custom_template)) {
        include($custom_template);
        exit; // Importante: sair para não continuar com este template
    }
}

// Verificar se é página de carrinho
// Verificar de múltiplas formas para garantir detecção
$is_cart_page = false;
if (function_exists('is_cart')) {
    $is_cart_page = is_cart();
}
// Verificação adicional pela URL
if (!$is_cart_page) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $cart_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('cart') : get_option('woocommerce_cart_page_id');
    if ($cart_page_id) {
        $cart_page = get_post($cart_page_id);
        if ($cart_page) {
            $cart_slug = $cart_page->post_name;
            if (strpos($request_uri, $cart_slug) !== false || strpos($request_uri, 'carrinho') !== false || strpos($request_uri, 'cart') !== false) {
                $is_cart_page = true;
            }
        }
    }
}

if ($is_cart_page) {
    // Carregar o template customizado page-carrinho.php
    $custom_template = locate_template('page-carrinho.php');
    if ($custom_template && file_exists($custom_template)) {
        include($custom_template);
        exit; // Importante: sair para não continuar com este template
    }
}

// Verificar se é página de checkout
$is_checkout_page = false;
if (function_exists('is_checkout')) {
    $is_checkout_page = is_checkout();
}
// Verificação adicional pela URL
if (!$is_checkout_page) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $checkout_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('checkout') : get_option('woocommerce_checkout_page_id');
    if ($checkout_page_id) {
        $checkout_page = get_post($checkout_page_id);
        if ($checkout_page) {
            $checkout_slug = $checkout_page->post_name;
            if (strpos($request_uri, $checkout_slug) !== false || strpos($request_uri, 'checkout') !== false || strpos($request_uri, 'finalizar-compra') !== false) {
                $is_checkout_page = true;
            }
        }
    }
}

if ($is_checkout_page) {
    // Carregar o template customizado page-checkout.php
    $custom_template = locate_template('page-checkout.php');
    if ($custom_template && file_exists($custom_template)) {
        include($custom_template);
        exit; // Importante: sair para não continuar com este template
    }
}

// Para outras páginas do WooCommerce (produto único, etc), usar o template padrão
get_header();
?>

<main>
    <section class="border-b-2 border-dashed border-marrom my-2 flex flex-col items-center py-10">
        <div class="max-w-7xl mx-auto px-4 w-full">
            <?php woocommerce_content(); ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>