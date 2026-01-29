<?php
/**
 * Funciones para el módulo de Revistas - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registrar estilos y scripts específicos para revistas
 */

/**
 * Registrar categoría de Elementor para Revistas (si no existe ya)
 */
if ( ! function_exists( 'register_camara_revistas_elementor_category' ) ) {
    function register_camara_revistas_elementor_category( $elements_manager ) {
        global $camara_elementor_category_registered;
        
        // Si ya se registró una categoría, usar la existente
        if ( $camara_elementor_category_registered ) {
            return;
        }
        
        // Verificar si la categoría ya existe
        $existing_categories = $elements_manager->get_categories();
        
        // Buscar categorías existentes de Cámara Valencia
        foreach ( ['camara', 'camara-valencia', 'camara-megamenu', 'camara-areas', 'camara-agenda_noticias'] as $cat ) {
            if ( isset( $existing_categories[$cat] ) ) {
                $camara_elementor_category_registered = $cat;
                return;
            }
        }
        
        // Si no existe ninguna, registrar una nueva
        $elements_manager->add_category(
            'camara-valencia',
            [
                'title' => __( 'Cámara Valencia', 'camara-valencia' ),
                'icon' => 'eicon-gallery-grid',
            ]
        );
        
        // Marcar como registrada
        $camara_elementor_category_registered = 'camara-valencia';
    }
    add_action( 'elementor/elements/categories_registered', 'register_camara_revistas_elementor_category', 20 );
}

/**
 * Registrar el widget de revistas en Elementor
 */
if ( ! function_exists( 'register_camara_revistas_elementor_widgets' ) ) {
    
    function register_camara_revistas_elementor_widgets( $widgets_manager ) {
        
        // Verificar que Elementor esté cargado y el widgets_manager sea válido
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Verificar que el archivo del widget exista antes de intentar registrarlo
        $widget_file = dirname(__FILE__) . '/widget-revistas.php';
        if ( ! file_exists( $widget_file ) ) {
            error_log( 'Archivo del widget de revistas no encontrado: ' . $widget_file );
            return;
        }

        // Registrar el widget si la clase existe
        if ( class_exists( 'Camara_Revistas_Widget' ) && method_exists( $widgets_manager, 'register' ) ) {
            try {
                $widgets_manager->register( new \Camara_Revistas_Widget() );
                error_log( 'Widget de revistas registrado exitosamente' );
            } catch ( Exception $e ) {
                error_log( 'Error al registrar widget de revistas: ' . $e->getMessage() );
            }
        } else {
            error_log( 'No se pudo registrar widget Camara_Revistas_Widget: clase no existe o widgets_manager no válido' );
        }
    }
    
    add_action( 'elementor/widgets/register', 'register_camara_revistas_elementor_widgets', 20 );
}
