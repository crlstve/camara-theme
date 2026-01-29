<?php 
/**
 * Video Hero| Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

/** Menú de video_hero **/

    // Registra ubicación del menú de video_hero
        function video_hero_menu_locations() {
            $locations = array(
                'video_hero' => __('Video Hero', 'video_hero'),
            );
            register_nav_menus( $locations );
        }
        add_action( 'init', 'video_hero_menu_locations' );

/** Scripts y Estilos **/

    /**
     * Registrar scripts y estilos para el efecto de scroll
     */
    function video_hero_enqueue_scripts() {
        $plugin_url = plugins_url( '/', dirname( dirname( __FILE__ ) ) );
        $version = '1.0.0';
        
        // Registrar CSS
        wp_register_style(
            'video-hero-scroll-effect',
            $plugin_url . 'assets/css/video-hero-scroll-effect.css',
            [],
            $version
        );
        
        // Registrar JS
        wp_register_script(
            'video-hero-scroll-effect',
            $plugin_url . 'assets/js/video-hero-scroll-effect.js',
            ['jquery', 'elementor-frontend'],
            $version,
            true
        );
    }
    add_action( 'wp_enqueue_scripts', 'video_hero_enqueue_scripts' );

/** Elementor **/

    // Variable global para evitar registros duplicados
        global $camara_elementor_category_registered;

        /**
         * Registrar categoría de Cámara Valencia para Áreas
         */
        if ( ! function_exists( 'register_camara_video_hero_elementor_category' ) ) {
            function register_camara_video_hero_elementor_category( $elements_manager ) {
                global $camara_elementor_category_registered;
                
                // Si ya se registró una categoría, usar la existente
                if ( $camara_elementor_category_registered ) {
                    return;
                }
                
                // Verificar si la categoría ya existe
                $existing_categories = $elements_manager->get_categories();
                
                // Si ya existe la categoría 'camara', marcar como registrada y salir
                if ( isset( $existing_categories['camara'] ) ) {
                    $camara_elementor_category_registered = 'camara';
                    return;
                }
                
                // Si ya existe 'camara-video_hero', usar esa
                if ( isset( $existing_categories['camara-video_hero'] ) ) {
                    $camara_elementor_category_registered = 'camara-video_hero';
                    return;
                }
                
                // Si no existe ninguna, registrar una nueva para áreas
                $elements_manager->add_category(
                    'camara-video_hero',
                    [
                        'title' => __( 'Cámara Valencia | Video Hero', 'video_hero' ),
                        'icon' => 'eicon-slider-video',
                    ]
                );
                
                // Marcar como registrada
                $camara_elementor_category_registered = 'camara-video_hero';
            }
            add_action( 'elementor/elements/categories_registered', 'register_camara_video_hero_elementor_category', 20 );
        }

        /**
         * Registrar el widget de Áreas en Elementor
         */
        function register_camara_video_hero_elementor_widgets( $widgets_manager ) {
            
            // Verificar que Elementor esté completamente cargado
            if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
                return;
            }
            
            // Cargar el archivo del widget si no se ha cargado
            $widget_file = dirname(__FILE__) . '/widget-video_hero.php';
            if ( file_exists( $widget_file ) ) {
                require_once( $widget_file );
            }
            
            // Verificar que la clase existe antes de registrarla
            if ( class_exists( 'Camara_video_hero_Widget' ) && method_exists( $widgets_manager, 'register' ) ) {
                try {
                    $widgets_manager->register( new \Camara_video_hero_Widget() );
                    error_log( 'Widget Camara_video_hero_Widget registrado correctamente' );
                } catch ( Exception $e ) {
                    error_log( 'Error registrando widget Camara_video_hero_Widget: ' . $e->getMessage() );
                }
            } else {
                error_log( 'No se pudo registrar widget Camara_video_hero_Widget: clase no existe o widgets_manager no válido' );
            }
        }
        add_action( 'elementor/widgets/register', 'register_camara_video_hero_elementor_widgets', 20 );