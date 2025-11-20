<!DOCTYPE html>
<html class="h-full dark:bg-marromescuro" lang="pt-br"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./" type="image/x-icon">
    <link rel="shortcut icon" href="./assets/img/logo_minimalista_flor.svg" type="image/x-icon">
    <title>V&M Artesanato</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-normalize@3.0.1/modern-normalize.min.css">
   <?php wp_head(); ?> 
</head>

<body class="h-screen bg-begefundo dark:bg-marromescuro w-full z-20 top-0 start-0 text-cinzaescuro transition-colors duration-300 ease-in-out">
  <!-- Navbar -->
    <header class="sticky top-0 z-50">
        <nav class="bg-marrom dark:bg-marromescuro transition-colors duration-300 ease-in-out" id="inicio">
        
            <div class="flex justify-between mx-5 p-4">
               <a href="/" class="no-underline flex items-center space-x-1 rtl:space-x-reverse" >
                <img id ="logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-semtexto.png" alt="Logo V&M Artesanato" class="hidden w-20 h-20 md:block contrast-150 hover:scale-105 transition duration-300">
                <span class="hidden lg:block text-xl font-texto font-semibold text-cinzaescuro dark:text-white">V&M Artesanato</span>
                </a>

                <?php
                // URL da página de loja do WooCommerce
                $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : null;
                // Fallback: buscar a página com o template "Produtos"
                if (!$shop_url) {
                    $produtos_page = get_pages(array(
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-produtos.php'
                    ));
                    $shop_url = !empty($produtos_page) ? get_permalink($produtos_page[0]->ID) : home_url('/produtos');
                }
                ?>
                <div class="hidden items-center md:flex md:gap-8 lg:gap-12 mr-20">
                  <ul class="list-none flex flex-row space-x-6 font-texto text-cinzaescuro font-medium dark:text-white">
                      <li><a class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors duration-300 ease-in-out" href="#inicio">Início</a></li>
                      <li><a class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors duration-300 ease-in-out" href="#categorias">Categorias</a></li>
                      <li><a class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors duration-300 ease-in-out" href="<?php echo esc_url($shop_url); ?>">Produtos</a></li>
                      <li><a class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors duration-300 ease-in-out" href="#sobre">Sobre Nós</a></li>
                      <li><a class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors duration-300 ease-in-out" href="#contato">Contato</a></li>
                  </ul>
                </div>

                <!-- Botão abrir menu (mobile) -->
                <button id="openMenu" type="button" aria-controls="mobileMenu" aria-expanded="false" class="md:hidden cursor-pointer inline-flex items-center justify-center rounded-md p-2 mr-2 text-cinzaescuro hover:text-verde dark:text-white  focus:outline-2 focus:-outline-offset-1 focus:outline-marrom transition-colors duration-300 ease-in-out pr-50">
                  <span class="sr-only">Abrir menu</span>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-7">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>

                <!-- Mobile Menu -->
                <div id="mobileMenu" aria-hidden="true" class="fixed inset-0 z-50 flex flex-col items-center justify-center gap-6 text-lg font-medium bg-marrom dark:bg-marromescuro md:hidden transition duration-300 translate-x-full">
                  <a href="#inicio">Inicio</a>
                  <a href="#categorias">Categorias</a>
                  <a href="<?php echo esc_url($shop_url); ?>">Produtos</a>
                  <a href="#sobre">Sobre nós</a>
                  <a href="#contato">Contato</a>
                  <button id="closeMenu" aria-label="Fechar menu"
                      class="aspect-square size-10 p-1 items-center justify-center bg-marromhover hover:bg-marrom transition-colors duration-300 text-white rounded-md flex">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-x-icon lucide-x">
                          <path d="M18 6 6 18" />
                          <path d="m6 6 12 12" />
                      </svg>
                  </button>
                </div>
                
                <!-- Botão para mudar tema claro/escuro -->
                <div class="flex items-center">
                    <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode font-medium text-cinzaescuro rounded-full hover:bg-begefundo focus:outline-hidden focus:bg-begefundo dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800 transition-colors duration-300 ease-in-out" data-hs-theme-click-value="dark">
                        <span class="group inline-flex shrink-0 justify-center items-center size-9">
                          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                          </svg>
                        </span>
                      </button>

                      <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode font-medium text-cinzaescuro rounded-full hover:bg-begefundo focus:outline-hidden focus:bg-begefundo dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800 transition-colors duration-300 ease-in-out" data-hs-theme-click-value="light">
                        <span class="group inline-flex shrink-0 justify-center items-center size-9">
                          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.41 1.41"></path>
                            <path d="m17.66 17.66 1.41 1.41"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m6.34 17.66-1.41 1.41"></path>
                            <path d="m19.07 4.93-1.41 1.41"></path>
                          </svg>
                        </span>
                      </button>

                    <!-- Carrinho -->
                    <?php
                    // URL do carrinho - obter ID da página do carrinho do WooCommerce
                    $cart_url = '#';
                    $cart_page_id = get_option('woocommerce_cart_page_id');
                    if ($cart_page_id) {
                        $cart_url = get_permalink($cart_page_id);
                    } elseif (function_exists('wc_get_page_id')) {
                        $cart_page_id = wc_get_page_id('cart');
                        if ($cart_page_id) {
                            $cart_url = get_permalink($cart_page_id);
                        }
                    } elseif (function_exists('wc_get_cart_url')) {
                        $cart_url = wc_get_cart_url();
                    }
                    
                    // Contador de itens no carrinho
                    $cart_count = 0;
                    if (function_exists('WC') && WC()->cart) {
                        $cart_count = WC()->cart->get_cart_contents_count();
                    }
                    ?>
                    <div class="flow-root lg:ml-6">
                      <a href="<?php echo esc_url($cart_url); ?>" class="group -m-2 flex items-center p-2 relative">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 shrink-0 text-cinzaescuro dark:text-white group-hover:text-marromhover dark:group-hover:text-verde transition-colors duration-300 ease-in-out">
                          <path d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <?php if ($cart_count > 0) : ?>
                          <span class="absolute -top-1 -right-1 bg-verde text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center" id="cart-count-badge"><?php echo esc_html($cart_count); ?></span>
                        <?php endif; ?>
                        <span class="ml-2 text-sm font-medium text-cinzaescuro dark:text-white group-hover:text-marromhover dark:group-hover:text-verde transition-colors duration-300 ease-in-out" id="cart-count"><?php echo esc_html($cart_count); ?></span>
                        <span class="sr-only">items in cart, view bag</span>
                      </a>
                    </div>

                </div>

            </div>

      </nav>
    
    </header>