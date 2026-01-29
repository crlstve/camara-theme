<?php 
/**
 * Agenda / Noticias - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

/** Menú de agenda_noticias **/

    // Registra ubicación del menú de agenda_noticias
        function agenda_noticias_menu_locations() {
            $locations = array(
                'agenda_noticias' => __('Agenda / Noticias', 'agenda_noticias'),
            );
            register_nav_menus( $locations );
        }
        add_action( 'init', 'agenda_noticias_menu_locations' );

/** Elementor **/

    // Variable global para evitar registros duplicados
        global $camara_elementor_category_registered;

        /**
         * Registrar categoría de Cámara Valencia para Áreas
         */
        if ( ! function_exists( 'register_camara_agenda_noticias_elementor_category' ) ) {
            function register_camara_agenda_noticias_elementor_category( $elements_manager ) {
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
                
                // Si ya existe 'camara-agenda_noticias', usar esa
                if ( isset( $existing_categories['camara-agenda_noticias'] ) ) {
                    $camara_elementor_category_registered = 'camara-agenda_noticias';
                    return;
                }
                
                // Si no existe ninguna, registrar una nueva para áreas
                $elements_manager->add_category(
                    'camara-agenda_noticias',
                    [
                        'title' => __( 'Cámara Valencia - Agenda / Noticias', 'agenda_noticias' ),
                        'icon' => 'icon-dual-button',
                    ]
                );
                
                // Marcar como registrada
                $camara_elementor_category_registered = 'camara-agenda_noticias';
            }
            add_action( 'elementor/elements/categories_registered', 'register_camara_agenda_noticias_elementor_category', 20 );
        }

        /**
         * Registrar el widget de Áreas en Elementor
         */
        function register_camara_agenda_noticias_elementor_widgets( $widgets_manager ) {
            
            // Verificar que Elementor esté completamente cargado
            if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
                return;
            }
            
            // Cargar el archivo del widget si no se ha cargado
            $widget_file = dirname(__FILE__) . '/widget-agenda_noticias.php';
            if ( file_exists( $widget_file ) ) {
                require_once( $widget_file );
            }
            
            // Verificar que la clase existe antes de registrarla
            if ( class_exists( 'Camara_agenda_noticias_Widget' ) && method_exists( $widgets_manager, 'register' ) ) {
                try {
                    $widgets_manager->register( new \Camara_agenda_noticias_Widget() );
                    error_log( 'Widget Camara_agenda_noticias_Widget registrado correctamente' );
                } catch ( Exception $e ) {
                    error_log( 'Error registrando widget Camara_agenda_noticias_Widget: ' . $e->getMessage() );
                }
            } else {
                error_log( 'No se pudo registrar widget Camara_agenda_noticias_Widget: clase no existe o widgets_manager no válido' );
            }
        }
        add_action( 'elementor/widgets/register', 'register_camara_agenda_noticias_elementor_widgets', 20 );