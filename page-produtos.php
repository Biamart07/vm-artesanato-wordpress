<?php
/**
 * Template Name: Produtos
 * Template para página de loja do WooCommerce
 * Este template será usado automaticamente quando is_shop() for true
 */
get_header(); ?>

<main>
    <section class="border-y-2 border-dashed border-marrom flex flex-col items-center py-10" id="produtos">
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
        $shop_url = vm_artesanato_get_shop_url();
        ?>
        <form role="search" method="get" class="search-form mt-6 mb-4 flex flex-col md:flex-row items-stretch justify-center gap-7 max-w-2xl mx-auto px-4 w-full" action="<?php echo esc_url($shop_url); ?>">
            <label class="flex-1 min-w-0">
                <span class="screen-reader-text">Buscar produtos:</span>
                <input 
                    type="search" 
                    class="search-field w-full h-full px-4 py-3 rounded-full border-2 border-marrom/30 dark:border-marrom/50 bg-begefundo dark:bg-marromescuro/50 text-cinzaescuro dark:text-white placeholder:text-marrom/50 dark:placeholder:text-marrom/60 focus:outline-none focus:ring-2 focus:ring-marrom dark:focus:ring-verde focus:border-marrom dark:focus:border-verde transition-all duration-300 font-texto text-sm sm:text-base shadow-sm hover:shadow-md" 
                    placeholder="Buscar produtos..." 
                    value="<?php echo esc_attr(get_search_query()); ?>" 
                    name="s"
                    autocomplete="off"
                />
            </label>
            <button 
                type="submit" 
                class="search-submit px-8 py-3 bg-gradient-to-r from-verde to-marrom dark:from-verde dark:to-marromescuro text-white rounded-full hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer font-texto font-medium text-sm sm:text-base shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-marrom focus:ring-offset-2 dark:focus:ring-verde whitespace-nowrap shrink-0"
            >
                Buscar
            </button>
        </form>

        <div class="flex flex-col gap-15 pt-12 md:grid grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto px-4" data-aos="fade-up" data-aos-duration="1000">
            <?php
            global $wp_query;
            
            // Usar a query principal do WooCommerce se estiver na página de loja
            if (is_shop() && isset($wp_query) && $wp_query->is_main_query() && $wp_query->have_posts()) {
                // A query já está configurada pelo WooCommerce
                $products_query = $wp_query;
            } else {
                // Para outras páginas, criar query customizada
                $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
                
                // Otimizar consulta: limitar produtos e usar paginação
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 12, // Limitar a 12 produtos por página (CRÍTICO para performance)
                    'paged' => $paged,
                    'post_status' => 'publish',
                    'no_found_rows' => false, // Permitir paginação
                    'update_post_meta_cache' => true, // Cache de meta (reduz consultas N+1)
                    'update_post_term_cache' => true, // Cache de termos (reduz consultas N+1)
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                // Adicionar busca se houver termo de pesquisa
                if (!empty($search_query)) {
                    $args['s'] = $search_query;
                }
                
                $products_query = new WP_Query($args);
            }

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
                        <a href="<?php the_permalink(); ?>" class="block">
                            <img class='h-48 w-full object-cover rounded-t-[10px] bg-fixed transition duration-300 ease-in-out hover:opacity-80' 
                                 src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>">
                        </a>
                        <div class="bg-begefundo rounded-b-xl p-4 text-sm">
                            <p class="font-texto text-cinzaescuro text-lg font-semibold mb-2">
                                <?php echo $product->get_price_html(); ?>
                            </p>
                            <h3 class="font-texto text-cinzaescuro text-base font-medium my-1.5">
                                <a href="<?php the_permalink(); ?>" class="no-underline text-cinzaescuro hover:text-marromhover dark:text-white dark:hover:text-verde transition-colors">
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
                
                // Paginação
                if ($products_query->max_num_pages > 1) {
                    echo '<div class="col-span-full flex justify-center mt-8 gap-2">';
                    if (is_shop() && isset($wp_query) && $wp_query->is_main_query()) {
                        // Usar paginação do WooCommerce
                        woocommerce_pagination();
                    } else {
                        // Paginação customizada
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        echo paginate_links(array(
                            'total' => $products_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '&laquo; Anterior',
                            'next_text' => 'Próxima &raquo;',
                            'class' => 'flex gap-2'
                        ));
                    }
                    echo '</div>';
                }
                ?>
            <?php else : ?>
                <p class="col-span-full text-center text-cinzaescuro dark:text-white py-8">Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>