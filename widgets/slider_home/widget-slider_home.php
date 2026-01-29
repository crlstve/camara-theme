<?php
/**
 * Widget Slider para Elementor - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Verificar que Elementor esté cargado
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * Clase del Widget de Slider
 */
class Camara_Slider_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'slider_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Slider', 'slider_widget' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-slider-3d';
    }

    /**
     * Categoría del widget
     */
    public function get_categories() {
        global $camara_elementor_category_registered;
        
        // Usar la categoría registrada globalmente, o 'general' como fallback
        $category = $camara_elementor_category_registered ?: 'general';
        
        return [ $category ];
    }

    /**
     * Palabras clave
     */
    public function get_keywords() {
        return [ 'slider' ];
    }


    public function get_script_depends() {
        return [ 'slider-script' ];
    }
    
    protected function register_scripts() {
        wp_register_script( 'slider-script', get_stylesheet_directory_uri() . '/assets/js/slider.js', array('jquery'), '1.0.3', true );
    }
    
    protected function register_styles() {
        // Registrar estilos del card slider
        wp_register_style( 'slider-style', get_stylesheet_directory_uri() . '/assets/css/slider.css', array(), '1.0.0' );
    }
        
    public function get_style_depends() {
        return [ 'slider-style' ];
    }
    
    /**
     * Constructor del widget
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->register_styles();
        $this->register_scripts();
    }

    /**
     * Configuración de controles
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Contenido Principal', 'slider-home' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        wp_enqueue_script( 'slider-script' );
        wp_enqueue_style( 'slider-slider-style' );

        // Consulta para obtener los slides
        $fecha_actual = date('Ymd'); // Formato: YYYYMMDD para comparar fechas ACF
        
        $args = array(
            'post_type'      => 'slider_home',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'slider_fechas_fecha_inicio',
                        'value'   => $fecha_actual,
                        'compare' => '<=',
                        'type'    => 'NUMERIC',
                    ),
                    array(
                        'key'     => 'slider_fechas_fecha_fin',
                        'value'   => $fecha_actual,
                        'compare' => '>=',
                        'type'    => 'NUMERIC',
                    )
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'slider_fechas_fecha_inicio',
                       'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key'     => 'slider_fechas_fecha_fin',
                        'compare' => 'NOT EXISTS'
                    ),
                ),
            ),
        );
        $slides = new WP_Query( $args );
         
    ?>

        <section>
            <div class="head" style="display: none;">
                <div class="controls">
                <button id="prev" class="nav-btn" aria-label="Prev">‹</button>
                <button id="next" class="nav-btn" aria-label="Next">›</button>
                </div>
            </div>

            <div class="slider">
                <div class="track" id="track">

                    <?php
                        if ( $slides->have_posts() ) :
                            while ( $slides->have_posts() ) : $slides->the_post();     
                                // Obtener campos personalizados ACF
                                $imagen = get_field('slider_imagen');
                                $color_overlay = get_field('slider_overlay');
                                $title_logo = get_field('slider_title_logo');
                                $title = get_the_title();
                                $subtitulo = get_field('slider_subtitulo');
                                $texto = get_field('slider_texto');
                                $color_titulo = get_field('slider_color_titulo');
                                $color_subtitulo = get_field('slider_color_subtitulo');
                                $color_texto = get_field('slider_color_texto');
                                $button = get_field('slider_boton');
                                $button_hover = get_field('slider_boton_hover');
                                $button_2 = get_field('slider_boton_2');
                                $button_2_hover = get_field('slider_boton_2_hover');
                                $fechas = get_field('slider_fechas');
                                $hoy = date('Ymd');
                                $logos = get_field('field_slider_logos');
                    ?>

                        <article class="slider-card" style="background: linear-gradient(<?= $color_overlay['grados'] ?>deg, <?= $color_overlay['color1'] ?> <?= $color_overlay['posicion_color1'] ?>%, <?= $color_overlay['color2'] ?> <?= $color_overlay['posicion_color2'] ?>%), url(<?= esc_url($imagen); ?>); background-size: cover; background-repeat: no-repeat; background-position: center top;">
                            <div class="slider-card__content">
                                <div class="slider-card_wrap-content">
                                    <?php if ( $title_logo ) : ?>
                                        <div class="slider-card__logo">
                                            <?= wp_get_attachment_image( $title_logo,'full',false, ['class' => 'slider-card__logo-image'] ); ?>
                                        </div>
                                    <?php else: ?>
                                        <h3 class="slider-card__title" style="color: <?= esc_attr($color_titulo); ?>"><?= esc_html($title) ?></h3> 
                                    <?php endif; ?>
                                    <?php if($subtitulo): ?>
                                        <h4 class="slider-card__subtitle" style="color: <?= esc_attr($color_subtitulo); ?>"><?= esc_html($subtitulo); ?></h4>
                                    <?php endif; ?>

                                    <?php if($texto): ?>
                                        <p class="slider-card__desc" style="color: <?= esc_attr($color_texto); ?>"><?= esc_html($texto);?></p>
                                    <?php endif; ?>

                                    <?php if ( $button ) : ?>
                                        <a href="<?= esc_url($button['enlace']); ?>" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           class="slider-card__btn"
                                           style="--btn-bg-color: <?= esc_attr($button['color_fondo']); ?>; 
                                                  --btn-border-color: <?= esc_attr($button['color_borde']); ?>; 
                                                  --btn-hover-text-color: <?= $button_hover ? esc_attr($button_hover) : '#1E1E1E'; ?>; 
                                                  color: <?= esc_attr($button['color_texto']); ?>; 
                                                  background-color: <?= esc_attr($button['color_fondo']); ?>; 
                                                  border: 1px solid <?= esc_attr($button['color_borde']); ?>;">
                                            <?= esc_html($button['texto']); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $button_2['enlace'] && $button_2['texto'] ) : ?>
                                        <a href="<?= esc_url($button_2['enlace']); ?>" target="_blank" rel="noopener noreferrer" class="slider-card__btn" style="
                                        <?= $button_2['color_fondo'] ? "background-color: " . esc_attr($button_2['color_fondo']) . ";" : ""; ?> color: <?= esc_attr($button_2['color_texto']); ?>; border: <?= esc_attr($button_2['color_borde']); ?> solid 1px;--color-btn-hover: <?= esc_attr($button_2['color_hover']); ?>;">
                                            <?= esc_html($button_2['texto']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php if($logos): ?>
                                        <div class="slider-card__partners">
                                            <div class="slider-card__partners-grid">
                                            <?php foreach($logos as $logo): ?>
                                                <div class="slider-card__partner-logo">
                                                    <?= wp_get_attachment_image( $logo['imagen'],'full',false, ['class' => 'slider-card__partner-image'] ); ?>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php
                            endwhile;
                        endif;
                    ?>
 

                </div>
            </div>

            <div class="dots" id="dots"></div>
        </section>
   
    <?php
        wp_reset_postdata();
    }
}

// El registro del widget se maneja en functions-slider.php
