<?php
/**
 * Widget de Revistas para Elementor - Cámara Valencia
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
 * Clase del Widget de Revistas
 */
class Camara_Revistas_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'revistas_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Revistas', 'revistas_widget' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-parallax';
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
        return [ 'revistas' ];
    }


    public function get_script_depends() {
        return [ 'revistas-script' ];
    }
    
    protected function register_scripts() {
        wp_register_script( 'revistas-script', get_stylesheet_directory_uri() . '/assets/js/revistas.js', array('jquery'), '1.0.3', true );
    }
    
    protected function register_styles() {
        // Registrar estilos del card slider
        wp_register_style( 'revistas-slider-style', get_stylesheet_directory_uri() . '/assets/css/revistas-slider.css', array(), '1.0.0' );
    }
        
    public function get_style_depends() {
        return [ 'revistas-slider-style' ];
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
                'label' => __( 'Configuración', 'revistas_widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __( 'Altura del Slider', 'revistas_widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .revistas-card-slider' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_max_width',
            [
                'label' => __( 'Ancho Máximo', 'revistas_widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 15,
                        'max' => 60,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .revistas-card-slider' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        wp_enqueue_script( 'revistas-script' );
        wp_enqueue_style( 'revistas-slider-style' );
        
        $revistas = new WP_Query( array(
            'post_type' => 'revistas',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
        ) );   
    ?>
        <div class="revistas-card-slider">
            <!-- Controles del slider -->
            <div class="slider-controls">
                <button class="slider-btn prev-btn" aria-label="Revista anterior">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
                <button class="slider-btn next-btn" aria-label="Siguiente revista">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                    </svg>
                </button>
            </div>
            
            <!-- Contenedor de las cartas -->
            <div class="cards-container">
                <ul class="cards-stack">
                    <?php 
                        $index = 0;
                        while( $revistas->have_posts() ) : 
                            $revistas->the_post(); 
                            $url = get_field('enlace');
                            $titulo = get_the_title();
                    ?>
                        <li class="revista-card" data-index="<?php echo $index; ?>">
                            <a href="<?php echo esc_url($url['url']); ?>" target="<?php echo esc_attr($url['target'] ?: '_blank'); ?>"
                               class="card-link">
                                <div class="card-image">
                                    <?php the_post_thumbnail( 'large', ['class'=>'revista-image'] ); ?>
                                </div>
                            </a>
                        </li>
                            
                    <?php 
                        $index++;
                        endwhile; 
                    ?>
                </ul>
            </div>
            
            <?php if ($revistas->post_count > 1): ?>
            <!-- Indicadores -->
            <div class="slider-indicators">
                <?php for ($i = 0; $i < $revistas->post_count; $i++): ?>
                    <span class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></span>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    <?php
        wp_reset_postdata();
    }
}

// El registro del widget se maneja en functions-revistas.php
