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

?>