<?php
/**
 * Widget de Formulario para Elementor - Cámara Valencia
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
 * Clase del Widget de Formulario
 */
class Camara_Form_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'form_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Formulario', 'form_widget' );
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
        return [ 'formulario' ];
    }


    public function get_script_depends() {
        return [ 'formulario-script' ];
    }
    
    protected function register_scripts() {
        wp_register_script( 'formulario-script', get_stylesheet_directory_uri() . '/assets/js/formulario.js', array('jquery'), '1.0.3', true );
    }
    
        
    public function get_style_depends() {
        return [ 'formulario-style' ];
    }
    
    /**
     * Constructor del widget
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->register_scripts();
    }

    /**
     * Configuración de controles
     */
    protected function _register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Configuración', 'form_widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __( 'Altura del Slider', 'form_widget' ),
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
                    '{{WRAPPER}} .camara-form' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_max_width',
            [
                'label' => __( 'Ancho Máximo', 'form_widget' ),
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
                    '{{WRAPPER}} .camara-form' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        wp_enqueue_script( 'formulario-script' );
        wp_enqueue_style( 'formulario-style' );  
    ?>

        <form action="">
            <input type="text" name="empresa" placeholder="Empresa" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="web" placeholder="Sitio web" required>
            <input type="text" name="contacto" placeholder="Persona de contacto" required>
            <input type="text" name="cargo" placeholder="Cargo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>

                <select name="sector" id="sector-1">
                    <option value="">Selecciona un sector</option>
                    <option value="agricultura">Agricultura</option>
                    <option value="alimentacion">Alimentación</option>
                    <option value="automocion">Automoción</option>
                    <option value="construccion">Construcción</option>
                    <option value="energia">Energía</option>
                    <option value="industria">Industria</option>
                    <option value="servicios">Servicios</option>
                    <option value="tecnologia">Tecnología</option>
                    <option value="textil">Textil</option>
                    <option value="transporte">Transporte</option>
                    <option value="otro">Otro</option>
                </select>

            <textarea name="mensaje" placeholder="Mensaje" required></textarea>
            <button type="submit">Enviar</button>



        </form>

    <?php
        wp_reset_postdata();
    }
}

// El registro del widget se maneja en functions-form.php
