<?php

add_theme_support('woocommerce');

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

// Fazer a página de loja usar o template customizado
function vm_artesanato_woocommerce_template( $template ) {
    if ( is_shop() ) {
        $custom_template = locate_template( 'page-produtos.php' );
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
    
    // Fragmento para o badge do carrinho
    if ( $cart_count > 0 ) {
        $fragments['#cart-count-badge'] = '<span id="cart-count-badge" class="absolute -top-1 -right-1 bg-verde text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">' . esc_html( $cart_count ) . '</span>';
    } else {
        $fragments['#cart-count-badge'] = '';
    }
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'vm_artesanato_add_to_cart_fragments' );

?>