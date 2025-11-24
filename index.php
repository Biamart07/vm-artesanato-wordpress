<?php get_header(); ?>

 <main>
        <!-- Hero-->
        <?php
          // Primeiro, pegamos a URL da imagem e guardamos em uma variável PHP.
          // Usamos get_field() porque queremos guardar o valor, não imprimi-lo ainda.
          $url_imagem_hero = get_field('imagem_hero');
        ?>

        <section class="border-t-2 border-dashed border-marrom flex flex-col items-center justify-center mx-0 max-md:mx-0 max-md:px-2 w-full text-center py-20 md:py-24 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo $url_imagem_hero; ?>');">
            
          <h1 class="text-2xl md:text-3xl font-texto font-medium text-teste max-w-2xl"><?php the_field('titulo_hero'); ?></h1>
            <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro"></div>
            <p class="text-sm md:text-base font-texto text-cinzaescuro max-w-xl">
            <?php the_field('texto_hero'); ?>
            </p>
            <?php
            // URL da página de loja do WooCommerce
            $produtos_url = vm_artesanato_get_shop_url();
            ?>
            <a href="<?php echo esc_url($produtos_url); ?>" class="no-underline px-8 py-2.5 mt-4 text-sm bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white rounded-full">
                <?php the_field('texto_botao_hero'); ?>
            </a>
            
            
        </section>

        <!--Categorias -->
        <section class="border-y-2 border-dashed border-marrom py-10 md:grid-cols-6" id="categorias">

          <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center mx-auto"><?php the_field('titulo_criacoes'); ?></h2>

          <div class="flex flex-col gap-20 gap-y-30 pt-12 md:flex-row items-center justify-center text-center" data-aos="fade-up" data-aos-duration="1000">
          


          <?php if( have_rows('criacoes') ): ?>
            <?php while( have_rows('criacoes') ) : the_row(); ?>

          <?php //echo var_dump(get_sub_field('imagem')); ?>
            <div class="max-w-100 w-full h-full hover:-translate-y-0.5 transition duration-300">
              <img class="rounded-xl border-2 border-marrom shadow-xl h-56 w-90 object-cover mx-auto" src="<?php echo get_sub_field('imagem')['url']; ?>" alt="<?php echo get_sub_field('imagem')['alt']; ?>">
              <h3 class="pt-3 font-semibold font-titulo text-xl"><?php the_sub_field('titulo'); ?></h3>
            </div>

          

          <?php endwhile; ?>
          <?php endif; ?>

          </div>

        </section>

        <!-- Preview de Produtos -->
        <section class="border-b-2 border-dashed border-marrom flex flex-col items-center py-10" id="produtos-preview">
            <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center mb-4"><?php the_field('titulo_produtos'); ?></h2>
            <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-marromescuro"></div>

            <?php
            // URL da página de loja do WooCommerce
            $shop_url = vm_artesanato_get_shop_url();
            ?>

            <div class="flex flex-col gap-15 pt-12 md:grid grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto px-4" data-aos="fade-up" data-aos-duration="1000">
                <?php
                // Buscar apenas alguns produtos para preview (6 produtos) - OTIMIZADO
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'update_post_meta_cache' => true, // Cache de meta
                    'update_post_term_cache' => true, // Cache de termos
                    'no_found_rows' => true // Não contar total (mais rápido para preview)
                );
                $products_preview = new WP_Query($args);

                if ($products_preview->have_posts()) :
                    while ($products_preview->have_posts()) : $products_preview->the_post();
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
                else :
                    echo '<p class="col-span-full text-center text-cinzaescuro dark:text-white">Nenhum produto encontrado.</p>';
                endif;
                ?>
            </div>

            <!-- Botão para ver todos os produtos -->
            <div class="mt-8">
                <a href="<?php echo esc_url($shop_url); ?>" class="no-underline px-8 py-2.5 text-sm bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white rounded-full">
                    Ver Todos os Produtos
                </a>
            </div>
        </section>

        <!--Sobre nós -->
        <section class="border-b-2 border-dashed border-marrom flex flex-col items-center justify-center gap-10 max-md:px-4 md:flex-row py-20" id="sobre">
          <div class="relative shrink-0 overflow-hidden rounded-2xl shadow-2xl shadow-indigo-600/40" data-aos="fade-up" data-aos-duration="1000">
            <img class="w-full max-w-md rounded-2xl object-cover" src="<?php the_field('imagem_secao_sobre'); ?>" alt="foto" />
          </div>
          <div class="max-w-lg text-sm text-slate-600">
            <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white mx-auto"><?php the_field('titulo_secao_sobre'); ?></h2>
            <div class="h-[3px] w-32 my-1 bg-gradient-to-l from-transparent to-marrom dark:to-verde"></div>
            <p class="mt-8 font-texto text-cinzaescuro dark:text-white"> <?php the_field('texto_secao_sobre'); ?></p>
          </div>
        </section>

    </main>

<?php get_footer(); ?>