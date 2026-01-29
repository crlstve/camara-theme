<?php
/**
 * Widget de Mega Menú para Elementor - Cámara Valencia
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
 * Clase del Widget Mega Menú
 */
class Camara_Megamenu_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'megamenu';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Mega Menú', 'megamenu' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-menu-toggle';
    }

    /**
     * Categoría del widget
     */
    public function get_categories() {
        global $camara_elementor_category_registered;
        
        // Usar la categoría que se registró
        if ( $camara_elementor_category_registered ) {
            return [ $camara_elementor_category_registered ];
        }
        
        // Verificar qué categoría está disponible como fallback
        $categories_manager = \Elementor\Plugin::$instance->elements_manager;
        $existing_categories = $categories_manager->get_categories();
        
        if ( isset( $existing_categories['camara'] ) ) {
            return [ 'camara' ];
        } elseif ( isset( $existing_categories['camara-megamenu'] ) ) {
            return [ 'camara-megamenu' ];
        } else {
            // Fallback a categoría general
            return [ 'general' ];
        }
    }

    /**
     * Palabras clave
     */
    public function get_keywords() {
        return [ 'menu', 'navigation', 'megamenu' ];
    }


    /**
     * Cargar scripts del widget
     */
    public function get_script_depends() {
        return [ 'megamenu-script' ];
    }


    protected function register_scripts() {
        wp_register_script( 'megamenu-script', get_stylesheet_directory_uri() . '/assets/js/megamenu.js', array(),'1.0.1' );
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
                'label' => __( 'Configuración del Menú', 'megamenu' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'menu_info',
            [
                'label' => __( 'Información', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __( 'Este widget muestra automáticamente el menú asignado a la ubicación "Mega Menu". Configura tu menú en Apariencia → Menús.', 'megamenu' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();

        // Sección de Tipografía
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __( 'Tipografía', 'megamenu' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'label' => __( 'Tipografía del Menú Principal', 'megamenu' ),
                'selector' => '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'submenu_typography',
                'label' => __( 'Tipografía del Submenú', 'megamenu' ),
                'selector' => '{{WRAPPER}} .megamenu-widget .sub-menu a',
            ]
        );

        $this->end_controls_section();

        // Sección de Colores del Menú Principal
        $this->start_controls_section(
            'main_menu_colors_section',
            [
                'label' => __( 'Colores del Menú Principal', 'megamenu' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'main_menu_tabs' );

        $this->start_controls_tab(
            'main_menu_normal_tab',
            [
                'label' => __( 'Normal', 'megamenu' ),
            ]
        );

        $this->add_control(
            'main_menu_text_color',
            [
                'label' => __( 'Color del Texto', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_menu_bg_color',
            [
                'label' => __( 'Color de Fondo', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'main_menu_hover_tab',
            [
                'label' => __( 'Hover', 'megamenu' ),
            ]
        );

        $this->add_control(
            'main_menu_text_color_hover',
            [
                'label' => __( 'Color del Texto al Hover', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_menu_bg_color_hover',
            [
                'label' => __( 'Color de Fondo al Hover', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Sección de Colores del Submenú
        $this->start_controls_section(
            'submenu_colors_section',
            [
                'label' => __( 'Colores del Submenú', 'megamenu' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'submenu_tabs' );

        $this->start_controls_tab(
            'submenu_normal_tab',
            [
                'label' => __( 'Normal', 'megamenu' ),
            ]
        );

        $this->add_control(
            'submenu_text_color',
            [
                'label' => __( 'Color del Texto', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .sub-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_bg_color',
            [
                'label' => __( 'Color de Fondo del Submenú', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megawrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submenu_hover_tab',
            [
                'label' => __( 'Hover', 'megamenu' ),
            ]
        );

        $this->add_control(
            'submenu_text_color_hover',
            [
                'label' => __( 'Color del Texto al Hover', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .sub-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_item_bg_color_hover',
            [
                'label' => __( 'Color de Fondo del Item al Hover', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .sub-menu a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Sección de Espaciado
        $this->start_controls_section(
            'spacing_section',
            [
                'label' => __( 'Espaciado', 'megamenu' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'menu_item_padding',
            [
                'label' => __( 'Padding del Menú Principal', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_padding',
            [
                'label' => __( 'Padding del Submenú', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%','rem' ],
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megawrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_gap',
            [
                'label' => __( 'Espaciado entre Items', 'megamenu' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em','rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .megamenu-widget .megamenu-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Renderizar el widget
     */
    protected function render() { 
        
        // Asegurar que los estilos y scripts estén cargados
        wp_enqueue_script( 'megamenu-script' );
        
        ?>

        <div class="megamenu-widget">
            <?php
            // Verificar si hay un menú asignado a la ubicación 'megamenu'
            if ( has_nav_menu( 'megamenu' ) ) {
                
                // Configurar argumentos del menú
                $menu_args = array(
                    'theme_location' => 'megamenu',
                    'menu_class'     => 'megamenu-list',
                    'container'      => 'nav',
                    'container_class' => 'megamenu-nav',
                    'fallback_cb'    => false,
                    'depth'          => 0,
                );

                // Si existe el Custom Walker, usarlo
                if ( class_exists( 'Custom_Walker_Nav_Menu' ) ) {
                    $menu_args['walker'] = new Custom_Walker_Nav_Menu();
                }
                
                // Mostrar el menú
                wp_nav_menu( $menu_args );
                
            } else {
                // Mensaje si no hay menú configurado
                echo '<div class="megamenu-notice">';
                echo '<p>' . __( 'No hay menú asignado a la ubicación "Mega Menu".', 'megamenu' ) . '</p>';
                echo '<p><a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . __( 'Configurar menús', 'megamenu' ) . '</a></p>';
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
}

// Las funciones de registro están en functions-megamenu.php para evitar duplicados
