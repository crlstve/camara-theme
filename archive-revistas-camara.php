<?php
/**
 * Registrar estilos y scripts específicos para revistas
 * @package Cámara Valencia
 * @since 1.0.0
 * 
 * Archivo de funciones para el módulo de Revistas
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header(); 

    $revistas = new WP_Query( array(
        'post_type' => 'revistas',
        'posts_per_page' => -1,
        'offset' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ) );

?>
<main>

    <section class="revistas-archive">
        <div class="container">
            <h1 class="section-title"><?php _e( 'Revistas Cámara Valencia', 'camara-valencia' ); ?></h1>
            <?php if ( $revistas->have_posts() ) : ?>
                <ul class="revistas-list">
                    <?php 
                        while ( $revistas->have_posts() ) : $revistas->the_post(); 
                        $img = get_field('revista_imagen');
                        $text = get_field('field_revista_texto');                    
                    ?>
                        <li class="revista-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ( $img ) : ?>
                                    <div class="revista-thumbnail">
                                        <?= wp_get_attachment_image( $img['id'], 'full', false, ['class'=>'revista-image']); ?>
                                    </div>
                                <?php endif; ?>
                                <h2 class="revista-title"><?php the_title(); ?></h2>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php _e( 'No se encontraron revistas.', 'camara-valencia' ); ?></p>
            <?php endif; ?>
        </div>
    </section>



</main>
<?php get_footer(); ?>