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
            <a href="#produtos" class="no-underline px-8 py-2.5 mt-4 text-sm bg-gradient-to-r from-verde to-marrom hover:scale-105 dark:to-marromescuro transition duration-300 text-white rounded-full">
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