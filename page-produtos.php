<?php
/*
Template Name: Produtos
*/
get_header(); ?>

<main>
    <section class="border-b-2 border-dashed border-marrom my-2 flex flex-col items-center py-10" id="produtos">
        <?php
        // Verificar se é página de loja do WooCommerce ou página normal
        if (function_exists('is_shop') && is_shop()) {
            $shop_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : get_option('woocommerce_shop_page_id');
            $shop_page = $shop_page_id ? get_post($shop_page_id) : null;
            $titulo = $shop_page ? $shop_page->post_title : 'Produtos';
        } else {
            $titulo = get_field('titulo_produtos') ? get_field('titulo_produtos') : 'Produtos';
        }
        ?>
        <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center"><?php echo esc_html($titulo); ?></h2>
        <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro"></div>

        <?php
        // URL da página de loja para o formulário de busca e links dos produtos
        $shop_url = wc_get_page_permalink('shop') ? wc_get_page_permalink('shop') : home_url('/');
        ?>
        <form role="search" method="get" class="search-form mt-4" action="<?php echo esc_url($shop_url); ?>">
            <label>
                <span class="screen-reader-text">Buscar produtos:</span>
                <input type="search" class="search-field px-4 py-2 rounded-md border border-marrom" placeholder="Buscar produtos..." value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
            </label>
            <input type="submit" class="search-submit px-6 py-2 bg-gradient-to-r from-verde to-marrom text-white rounded-md hover:scale-105 transition duration-300 cursor-pointer" value="Buscar" />
        </form>

        <div class="flex flex-col gap-15 pt-12 md:grid grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto px-4" data-aos="fade-up" data-aos-duration="1000">
            <?php
            // Verificar se há busca
            $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
            
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            
            // Adicionar busca se houver termo de pesquisa
            if (!empty($search_query)) {
                $args['s'] = $search_query;
            }
            
            $products_query = new WP_Query($args);

            if ($products_query->have_posts()) :
                while ($products_query->have_posts()) : $products_query->the_post();
                    global $product;
                    
                    // Garantir que temos o objeto do produto
                    if (!$product) {
                        $product = wc_get_product(get_the_ID());
                    }
                    
                    if (!$product) {
                        continue;
                    }
            ?>
                    <div class="flex flex-col rounded-xl shadow-md m-5 lg:w-72">
                        <a href="<?php echo esc_url($shop_url); ?>" class="block">
                            <img class='h-48 w-full object-cover rounded-t-[10px] bg-fixed transition duration-300 ease-in-out hover:opacity-80' 
                                 src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>">
                        </a>
                        <div class="bg-begefundo rounded-b-xl p-4 text-sm">
                            <p class="font-texto text-cinzaescuro text-lg font-semibold mb-2">
                                <?php echo $product->get_price_html(); ?>
                            </p>
                            <h3 class="font-texto text-cinzaescuro text-base font-medium my-1.5">
                                <a href="<?php echo esc_url($shop_url); ?>" class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p class="font-texto text-cinzaescuro line-clamp-2">
                                <?php echo wp_trim_words(get_the_excerpt() ? get_the_excerpt() : get_the_content(), 15, '...'); ?>
                            </p>
                            <div class="flex items-center justify-center gap-2 mt-3 mx-3 bg-white/20 rounded-md active:scale-100 hover:scale-105 transition-all duration-300">
                                <?php
                                $add_to_cart_url = $product->add_to_cart_url();
                                $add_to_cart_text = $product->add_to_cart_text();
                                ?>
                                <a role="button" href="<?php echo esc_url($add_to_cart_url); ?>" 
                                   class="no-underline font-semibold text-white text-sm text-center bg-gradient-to-t from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 h-full w-full rounded py-2">
                                    <?php echo esc_html($add_to_cart_text); ?>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="col-span-full text-center text-cinzaescuro dark:text-white py-8">Nenhum produto encontrado.</p>';
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>