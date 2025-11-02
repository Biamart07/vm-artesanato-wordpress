<?php
/*
Template Name: Produtos
*/
get_header(); ?>

<main>
    <section class="border-b-2 border-dashed border-marrom my-2 flex flex-col items-center py-10" id="produtos">
        <h2 class="text-3xl font-titulo text-cinzaescuro dark:text-white text-center"><?php the_field('titulo_produtos'); ?></h2>

        <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
            <label>
                <span class="screen-reader-text">Search for:</span>
                <input type="search" class="search-field" placeholder="Search products..." value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <input type="submit" class="search-submit" value="Search" />
        </form>

        <div class="flex flex-col gap-15 pt-12 md:grid grid-cols-2 lg:grid-cols-3" data-aos="fade-up" data-aos-duration="1000">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                's' => get_search_query()
            );
            $products_query = new WP_Query($args);

            if ($products_query->have_posts()) :
                while ($products_query->have_posts()) : $products_query->the_post();
            ?>
                    <div class="flex flex-col rounded-xl shadow-md m-5 lg:w-72">
                        <img class='h-48 object-cover rounded-t-[10px] bg-fixed transition duration-300 ease-in-out hover:opacity-80' src="<?php echo get_the_post_thumbnail_url(); ?>" alt="imagem de uma necessaire azul escuro">
                        <div class="bg-begefundo rounded-b-xl p-4 text-sm">
                            <p class="font-texto text-cinzaescuro"><?php the_field('preco'); ?></p>
                            <p class="font-texto text-cinzaescuro text-base font-medium my-1.5"><?php the_title(); ?></p>
                            <p class="font-texto text-cinzaescuro"><?php the_content(); ?></p>
                            <div class="flex items-center justify-center gap-2 mt-3 mx-3 bg-white/20 rounded-md active:scale-100 hover:scale-105 transition-all duration-300">
                                <a role="button" href="#" class="no-underline font-semibold text-white text-sm text-center bg-gradient-to-t from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 h-full w-full rounded py-2">
                                    Comprar
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No products found.</p>';
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>