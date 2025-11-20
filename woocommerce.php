<?php
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