<?php

add_theme_support('woocommerce');

// ==================== OTIMIZAÇÕES DE PERFORMANCE ====================

// Desabilitar emojis (reduz requisições HTTP)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remover scripts desnecessários do WordPress
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Desabilitar embeds (reduz requisições)
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// Habilitar lazy loading para imagens
add_filter('wp_get_attachment_image_attributes', function($attr) {
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}, 10, 1);

// Otimizar consultas: cachear resultados de get_pages
function vm_artesanato_get_pages_cached($args = array()) {
    $cache_key = 'vm_pages_' . md5(serialize($args));
    $pages = wp_cache_get($cache_key, 'vm_artesanato');
    
    if (false === $pages) {
        $pages = get_pages($args);
        wp_cache_set($cache_key, $pages, 'vm_artesanato', 3600); // Cache por 1 hora
    }
    
    return $pages;
}

// Otimizar carregamento de scripts (defer/async)
function vm_artesanato_defer_scripts($tag, $handle, $src) {
    $defer_scripts = array('aos-js', 'vm-artesanato-script');
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'vm_artesanato_defer_scripts', 10, 3);

// Limitar revisões de posts
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

// Otimizar consultas do WooCommerce (reduz consultas N+1)
function vm_artesanato_optimize_woocommerce_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $query->set('posts_per_page', 12);
            $query->set('update_post_meta_cache', true);
            $query->set('update_post_term_cache', true);
        }
    }
}
add_action('pre_get_posts', 'vm_artesanato_optimize_woocommerce_queries');

// Desabilitar queries desnecessárias do WooCommerce
function vm_artesanato_disable_woocommerce_queries() {
    // Desabilitar contagem de produtos por categoria na sidebar (se houver)
    if (!is_admin()) {
        remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
    }
}
add_action('init', 'vm_artesanato_disable_woocommerce_queries');

// Cachear objetos de produtos
function vm_artesanato_cache_product_objects($product_id) {
    static $product_cache = array();
    
    if (!isset($product_cache[$product_id])) {
        $product_cache[$product_id] = wc_get_product($product_id);
    }
    
    return $product_cache[$product_id];
}

// Função auxiliar para obter URL da página de loja (garante que sempre funcione)
function vm_artesanato_get_shop_url() {
    // Verificar se WooCommerce está ativo
    if (!function_exists('WC')) {
        return home_url('/produtos');
    }
    
    // Tentar usar a função do WooCommerce (método mais confiável)
    if (function_exists('wc_get_page_permalink')) {
        $shop_url = wc_get_page_permalink('shop');
        if ($shop_url && $shop_url !== false && $shop_url !== '') {
            return esc_url($shop_url);
        }
    }
    
    // Tentar obter pelo ID da página de loja
    if (function_exists('wc_get_page_id')) {
        $shop_page_id = wc_get_page_id('shop');
        if ($shop_page_id && $shop_page_id > 0) {
            $shop_url = get_permalink($shop_page_id);
            if ($shop_url && $shop_url !== false) {
                return esc_url($shop_url);
            }
        }
    }
    
    // Tentar obter pela opção do WordPress
    $shop_page_id = get_option('woocommerce_shop_page_id');
    if ($shop_page_id && $shop_page_id > 0) {
        $shop_url = get_permalink($shop_page_id);
        if ($shop_url && $shop_url !== false) {
            return esc_url($shop_url);
        }
    }
    
    // Fallback: buscar página com template "Produtos"
    if (function_exists('vm_artesanato_get_pages_cached')) {
        $produtos_page = vm_artesanato_get_pages_cached(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-produtos.php',
            'number' => 1
        ));
        if (!empty($produtos_page) && isset($produtos_page[0]->ID)) {
            $shop_url = get_permalink($produtos_page[0]->ID);
            if ($shop_url && $shop_url !== false) {
                return esc_url($shop_url);
            }
        }
    }
    
    // Último fallback: URL padrão
    return esc_url(home_url('/produtos'));
}

// ==================== FIM DAS OTIMIZAÇÕES ====================

// Garantir que a página de loja do WooCommerce existe e está configurada
function vm_artesanato_ensure_shop_page() {
    if (!function_exists('WC') || !function_exists('wc_get_page_id')) {
        return;
    }
    
    // Verificar se a página de loja já existe
    $shop_page_id = wc_get_page_id('shop');
    
    // Se não existe, criar ou configurar
    if (!$shop_page_id || $shop_page_id < 0) {
        // Primeiro, buscar página existente com slug "loja"
        $existing_page = get_page_by_path('loja');
        
        if (!$existing_page) {
            // Buscar página existente com template "Produtos"
            $produtos_page = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-produtos.php',
                'number' => 1
            ));
            
            if (!empty($produtos_page)) {
                // Usar página existente e atualizar o slug se necessário
                $shop_page_id = $produtos_page[0]->ID;
                wp_update_post(array(
                    'ID' => $shop_page_id,
                    'post_name' => 'loja'
                ));
            } else {
                // Criar nova página
                $shop_page = array(
                    'post_title'   => 'Loja',
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_name'    => 'loja'
                );
                $shop_page_id = wp_insert_post($shop_page);
                
                if ($shop_page_id && !is_wp_error($shop_page_id)) {
                    // Definir template
                    update_post_meta($shop_page_id, '_wp_page_template', 'page-produtos.php');
                }
            }
        } else {
            $shop_page_id = $existing_page->ID;
        }
        
        // Configurar WooCommerce para usar esta página
        if ($shop_page_id && $shop_page_id > 0) {
            update_option('woocommerce_shop_page_id', $shop_page_id);
            // Forçar flush de permalinks
            flush_rewrite_rules(false);
        }
    }
}
add_action('admin_init', 'vm_artesanato_ensure_shop_page');
add_action('after_switch_theme', 'vm_artesanato_ensure_shop_page');

// Garantir que a página de carrinho do WooCommerce existe e está configurada
function vm_artesanato_ensure_cart_page() {
    if (!function_exists('WC') || !function_exists('wc_get_page_id')) {
        return;
    }
    
    // Verificar se a página de carrinho já existe
    $cart_page_id = wc_get_page_id('cart');
    
    // Se não existe, criar ou configurar
    if (!$cart_page_id || $cart_page_id < 0) {
        // Primeiro, buscar página existente com slug "carrinho" ou "cart"
        $existing_page = get_page_by_path('carrinho');
        if (!$existing_page) {
            $existing_page = get_page_by_path('cart');
        }
        
        if (!$existing_page) {
            // Buscar página existente com template "Carrinho"
            $carrinho_page = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-carrinho.php',
                'number' => 1
            ));
            
            if (!empty($carrinho_page)) {
                // Usar página existente e atualizar o slug se necessário
                $cart_page_id = $carrinho_page[0]->ID;
                wp_update_post(array(
                    'ID' => $cart_page_id,
                    'post_name' => 'carrinho'
                ));
            } else {
                // Criar nova página
                $cart_page = array(
                    'post_title'   => 'Carrinho',
                    'post_content' => '[woocommerce_cart]',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_name'    => 'carrinho'
                );
                $cart_page_id = wp_insert_post($cart_page);
                
                if ($cart_page_id && !is_wp_error($cart_page_id)) {
                    // Definir template
                    update_post_meta($cart_page_id, '_wp_page_template', 'page-carrinho.php');
                }
            }
        } else {
            $cart_page_id = $existing_page->ID;
            // Garantir que o template está configurado
            update_post_meta($cart_page_id, '_wp_page_template', 'page-carrinho.php');
        }
        
        // Configurar WooCommerce para usar esta página
        if ($cart_page_id && $cart_page_id > 0) {
            update_option('woocommerce_cart_page_id', $cart_page_id);
            // Forçar flush de permalinks
            flush_rewrite_rules(false);
        }
    } else {
        // Garantir que a página existente tem o template correto
        $current_template = get_post_meta($cart_page_id, '_wp_page_template', true);
        if ($current_template !== 'page-carrinho.php') {
            update_post_meta($cart_page_id, '_wp_page_template', 'page-carrinho.php');
        }
    }
}
add_action('admin_init', 'vm_artesanato_ensure_cart_page');
add_action('after_switch_theme', 'vm_artesanato_ensure_cart_page');

// Garantir que a página de checkout do WooCommerce existe e está configurada
function vm_artesanato_ensure_checkout_page() {
    if (!function_exists('WC') || !function_exists('wc_get_page_id')) {
        return;
    }
    
    // Verificar se a página de checkout já existe
    $checkout_page_id = wc_get_page_id('checkout');
    
    // Se não existe, criar ou configurar
    if (!$checkout_page_id || $checkout_page_id < 0) {
        // Primeiro, buscar página existente com slug "checkout" ou "finalizar-compra"
        $existing_page = get_page_by_path('checkout');
        if (!$existing_page) {
            $existing_page = get_page_by_path('finalizar-compra');
        }
        
        if (!$existing_page) {
            // Buscar página existente com template "Checkout"
            $checkout_page_template = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-checkout.php',
                'number' => 1
            ));
            
            if (!empty($checkout_page_template)) {
                // Usar página existente e atualizar o slug se necessário
                $checkout_page_id = $checkout_page_template[0]->ID;
                wp_update_post(array(
                    'ID' => $checkout_page_id,
                    'post_name' => 'checkout'
                ));
            } else {
                // Criar nova página
                $checkout_page = array(
                    'post_title'   => 'Finalizar Compra',
                    'post_content' => '[woocommerce_checkout]',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_name'    => 'checkout'
                );
                $checkout_page_id = wp_insert_post($checkout_page);
                
                if ($checkout_page_id && !is_wp_error($checkout_page_id)) {
                    // Definir template
                    update_post_meta($checkout_page_id, '_wp_page_template', 'page-checkout.php');
                }
            }
        } else {
            $checkout_page_id = $existing_page->ID;
            // Garantir que o template está configurado
            update_post_meta($checkout_page_id, '_wp_page_template', 'page-checkout.php');
        }
        
        // Configurar WooCommerce para usar esta página
        if ($checkout_page_id && $checkout_page_id > 0) {
            update_option('woocommerce_checkout_page_id', $checkout_page_id);
            // Forçar flush de permalinks
            flush_rewrite_rules(false);
        }
    } else {
        // Garantir que a página existente tem o template correto
        $current_template = get_post_meta($checkout_page_id, '_wp_page_template', true);
        if ($current_template !== 'page-checkout.php') {
            update_post_meta($checkout_page_id, '_wp_page_template', 'page-checkout.php');
        }
    }
}
add_action('admin_init', 'vm_artesanato_ensure_checkout_page');
add_action('after_switch_theme', 'vm_artesanato_ensure_checkout_page');

// Forçar regeneração de permalinks ao ativar o tema
function vm_artesanato_flush_rewrite_rules() {
    if (function_exists('wc_get_page_id')) {
        vm_artesanato_ensure_shop_page();
        flush_rewrite_rules(false);
    }
}
add_action('after_switch_theme', 'vm_artesanato_flush_rewrite_rules');

// Regenerar permalinks uma vez após a ativação do tema
add_action('init', function() {
    if (function_exists('WC') && function_exists('wc_get_page_id')) {
        // Garantir que a página existe sempre
        vm_artesanato_ensure_shop_page();
        
        // Regenerar permalinks uma vez
        if (get_option('vm_artesanato_flush_rewrite_rules_flag') !== '1') {
            flush_rewrite_rules(false);
            update_option('vm_artesanato_flush_rewrite_rules_flag', '1');
        }
    }
}, 99);

add_action('wp_enqueue_scripts', 'vm_artesanato_carregar_recursos');

function vm_artesanato_carregar_recursos() {
    
    // Carrega nosso CSS final, compilado pelo Tailwind
    wp_enqueue_style(
        'vm-artesanato-style',
        get_template_directory_uri() . '/output.css' 
    );

    // Carrega o CSS da biblioteca Animate on Scroll (AOS)
    wp_enqueue_style(
        'aos-css',
        'https://unpkg.com/aos@2.3.1/dist/aos.css'
    );
    
    // Carrega o JavaScript da biblioteca AOS
    wp_enqueue_script(
        'aos-js', // Este é o "nome" da dependência
        'https://unpkg.com/aos@2.3.1/dist/aos.js',
        array(), 
        '2.3.1', 
        true
    );
    
    // Carrega nosso JavaScript
    wp_enqueue_script(
        'vm-artesanato-script',
        get_template_directory_uri() . '/assets/js/script.js',
        array('aos-js'), // Adicionando a dependência
        '1.0',   
        true
    );
}

function custom_search_filter($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('post', 'page', 'product'));
    }
    return $query;
}
add_action('pre_get_posts', 'custom_search_filter');

// Fazer a página de loja usar o template customizado page-produtos.php
function vm_artesanato_woocommerce_template( $template ) {
    // Verificar se é a página de loja do WooCommerce
    if ( function_exists('is_shop') && is_shop() ) {
        $custom_template = locate_template( 'page-produtos.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    
    // Também verificar se é categoria ou tag de produto
    if ( function_exists('is_product_category') && is_product_category() ) {
        $custom_template = locate_template( 'page-produtos.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    
    if ( function_exists('is_product_tag') && is_product_tag() ) {
        $custom_template = locate_template( 'page-produtos.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    
    // Verificar se é a página de carrinho
    $is_cart_page = false;
    if ( function_exists('is_cart') ) {
        $is_cart_page = is_cart();
    }
    
    // Verificação adicional pela URL caso is_cart() não funcione
    if (!$is_cart_page) {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $cart_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('cart') : get_option('woocommerce_cart_page_id');
        if ($cart_page_id) {
            $cart_page = get_post($cart_page_id);
            if ($cart_page) {
                $cart_slug = $cart_page->post_name;
                $cart_url = get_permalink($cart_page_id);
                if ($cart_url) {
                    $cart_path = parse_url($cart_url, PHP_URL_PATH);
                    if ($cart_path && (strpos($request_uri, $cart_path) !== false || strpos($request_uri, $cart_slug) !== false || strpos($request_uri, 'carrinho') !== false || strpos($request_uri, 'cart') !== false)) {
                        $is_cart_page = true;
                    }
                }
            }
        }
    }
    
    if ( $is_cart_page ) {
        $custom_template = locate_template( 'page-carrinho.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter( 'template_include', 'vm_artesanato_woocommerce_template', 99 );

// Adicionar fragmento do carrinho para atualização AJAX
function vm_artesanato_add_to_cart_fragments( $fragments ) {
    $cart_count = WC()->cart->get_cart_contents_count();
    
    // Fragmento para o contador do carrinho
    $fragments['#cart-count'] = '<span id="cart-count" class="ml-2 text-sm font-medium text-cinzaescuro dark:text-white group-hover:text-marromhover dark:group-hover:text-verde transition-colors duration-300 ease-in-out">' . esc_html( $cart_count ) . '</span>';
    
    // Badge removido - usando apenas o contador de texto
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'vm_artesanato_add_to_cart_fragments' );

// Estilizar campos do formulário de checkout
function vm_artesanato_checkout_field_classes( $fields ) {
    foreach ( $fields as $fieldset_key => $fieldset ) {
        foreach ( $fieldset as $key => $field ) {
            // Adicionar classes personalizadas aos campos
            if ( ! isset( $field['class'] ) ) {
                $field['class'] = array();
            }
            
            // Classes base para todos os campos
            $field['class'][] = 'px-4';
            $field['class'][] = 'py-3';
            $field['class'][] = 'text-sm';
            $field['class'][] = 'w-full';
            $field['class'][] = 'rounded-md';
            $field['class'][] = 'border-2';
            $field['class'][] = 'border-marrom/30';
            $field['class'][] = 'bg-begefundo';
            $field['class'][] = 'dark:bg-marromescuro/50';
            $field['class'][] = 'text-cinzaescuro';
            $field['class'][] = 'dark:text-white';
            $field['class'][] = 'font-texto';
            $field['class'][] = 'focus:outline-none';
            $field['class'][] = 'focus:ring-2';
            $field['class'][] = 'focus:ring-marrom';
            $field['class'][] = 'dark:focus:ring-verde';
            $field['class'][] = 'focus:border-marrom';
            $field['class'][] = 'dark:focus:border-verde';
            $field['class'][] = 'transition-all';
            $field['class'][] = 'duration-300';
            
            // Classes para labels
            if ( ! isset( $field['label_class'] ) ) {
                $field['label_class'] = array();
            }
            $field['label_class'][] = 'font-texto';
            $field['label_class'][] = 'text-cinzaescuro';
            $field['label_class'][] = 'dark:text-white';
            $field['label_class'][] = 'mb-2';
            $field['label_class'][] = 'block';
            
            $fields[ $fieldset_key ][ $key ] = $field;
        }
    }
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'vm_artesanato_checkout_field_classes' );

// Estilizar botão de finalizar compra
function vm_artesanato_checkout_button_text( $button_text ) {
    return 'Finalizar Compra';
}
add_filter( 'woocommerce_order_button_text', 'vm_artesanato_checkout_button_text' );

// Adicionar classes ao botão de finalizar compra
function vm_artesanato_checkout_button_html( $button ) {
    // Adicionar classes personalizadas ao botão
    $button = str_replace( 'class="button alt', 'class="button alt bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white px-8 py-3 rounded-full font-texto text-base', $button );
    return $button;
}
add_filter( 'woocommerce_order_button_html', 'vm_artesanato_checkout_button_html' );

// Adicionar estilos customizados para campos do checkout
function vm_artesanato_checkout_custom_styles() {
    if (function_exists('is_checkout') && is_checkout()) {
        ?>
        <style>
            /* Garantir que os campos de input tenham tamanho adequado */
            .woocommerce-checkout input[type="text"],
            .woocommerce-checkout input[type="email"],
            .woocommerce-checkout input[type="tel"],
            .woocommerce-checkout input[type="password"],
            .woocommerce-checkout input[type="number"],
            .woocommerce-checkout select,
            .woocommerce-checkout textarea {
                font-size: 15px !important;
                min-height: 42px !important;
                padding: 10px 16px !important;
                width: 100% !important;
            }
            
            /* Ajustar tamanho dos selects */
            .woocommerce-checkout select {
                padding-right: 40px !important;
            }
            
            /* Ajustar textarea */
            .woocommerce-checkout textarea {
                min-height: 90px !important;
                resize: vertical;
            }
            
            /* Garantir que os labels sejam visíveis */
            .woocommerce-checkout label {
                font-size: 14px !important;
                font-weight: 500 !important;
                margin-bottom: 6px !important;
            }
            
            /* Adicionar padding lateral aos containers dos campos */
            .woocommerce-checkout .woocommerce-billing-fields__field-wrapper,
            .woocommerce-checkout .woocommerce-shipping-fields__field-wrapper,
            .woocommerce-checkout .woocommerce-account-fields {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            /* Garantir padding nas colunas */
            .woocommerce-checkout .col-1,
            .woocommerce-checkout .col-2 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Ajustar espaçamento entre campos */
            .woocommerce-checkout .form-row {
                margin-bottom: 1rem;
            }
            
            /* Estilizar texto de privacidade no dark mode */
            .woocommerce-checkout .woocommerce-privacy-policy-text,
            .woocommerce-checkout .woocommerce-privacy-policy-text p {
                color: #3b3a3a !important;
            }
            
            .dark .woocommerce-checkout .woocommerce-privacy-policy-text,
            .dark .woocommerce-checkout .woocommerce-privacy-policy-text p {
                color: #ffffff !important;
            }
            
            /* Estilizar termos e condições */
            .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper {
                color: #3b3a3a;
            }
            
            .dark .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper {
                color: #ffffff;
            }
            
            .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper a {
                color: #8A9074;
                text-decoration: underline;
            }
            
            .dark .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper a {
                color: #8A9074;
            }
            
            .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper a:hover {
                color: #99402a;
            }
            
            .dark .woocommerce-checkout .woocommerce-terms-and-conditions-wrapper a:hover {
                color: #8A9074;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'vm_artesanato_checkout_custom_styles');

// Adicionar estilos customizados para mensagens do WooCommerce (notices)
function vm_artesanato_woocommerce_notices_styles() {
    if (function_exists('WC')) {
        ?>
        <style>
            /* Estilizar mensagens do WooCommerce (notices) no modo escuro */
            .woocommerce-info,
            .woocommerce-message,
            .woocommerce-error {
                color: #3b3a3a !important;
            }
            
            .dark .woocommerce-info,
            .dark .woocommerce-message,
            .dark .woocommerce-error {
                color: #ffffff !important;
            }
            
            /* Garantir que os links dentro das mensagens também sejam legíveis */
            .woocommerce-info a,
            .woocommerce-message a,
            .woocommerce-error a {
                color: #8A9074 !important;
            }
            
            .dark .woocommerce-info a,
            .dark .woocommerce-message a,
            .dark .woocommerce-error a {
                color: #8A9074 !important;
            }
            
            .woocommerce-info a:hover,
            .woocommerce-message a:hover,
            .woocommerce-error a:hover {
                color: #99402a !important;
            }
            
            .dark .woocommerce-info a:hover,
            .dark .woocommerce-message a:hover,
            .dark .woocommerce-error a:hover {
                color: #8A9074 !important;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'vm_artesanato_woocommerce_notices_styles');

?>