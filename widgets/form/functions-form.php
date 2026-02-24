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

if ( ! function_exists( 'register_form' ) ) {
    
    function register_form( $widgets_manager ) {
        
        // Verificar que Elementor esté cargado y el widgets_manager sea válido
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Verificar que el archivo del widget exista antes de intentar registrarlo
        $widget_file = dirname(__FILE__) . '/widget-form.php';
        if ( ! file_exists( $widget_file ) ) {
            error_log( 'Archivo del widget de formulario no encontrado: ' . $widget_file );
            return;
        }

        // Registrar el widget si la clase existe
        if ( class_exists( 'Camara_Form_Widget' ) && method_exists( $widgets_manager, 'register' ) ) {
            try {
                $widgets_manager->register( new \Camara_Form_Widget() );
                error_log( 'Widget de formulario registrado exitosamente' );
            } catch ( Exception $e ) {
                error_log( 'Error al registrar widget de formulario: ' . $e->getMessage() );
            }
        } else {
            error_log( 'No se pudo registrar widget Camara_Form_Widget: clase no existe o widgets_manager no válido' );
        }
    }
    
    add_action( 'elementor/widgets/register', 'register_form', 20 );
}
