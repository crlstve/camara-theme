<?php
/**
 * Campos ACF para Revistas - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registrar campos ACF para el post type Revistas
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_revistas_campos',
    'title' => 'Campos de Revista',
    'fields' => array(
        
      // Url
         array(
                    'key' => 'field_enlace',
                    'label' => 'Enlace',
                    'name' => 'enlace',
                    'type' => 'link',
                    'instructions' => 'URL de la revista',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
         ),     
                
        // Campo Repeater
        array(
            'key' => 'field_revista_contenidos',
            'label' => 'Contenidos de la Revista',
            'name' => 'revista_contenidos',
            'type' => 'repeater',
            'instructions' => 'Añade los diferentes contenidos o secciones de la revista',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => 'field_contenido_titulo',
            'min' => 0,
            'max' => 20,
            'layout' => 'table',
            'button_label' => 'Añadir Contenido',
            'sub_fields' => array(
                array(
                    'key' => 'field_contenido_titulo',
                    'label' => 'Título del Contenido',
                    'name' => 'contenido_titulo',
                    'type' => 'text',
                    'instructions' => 'Título de la sección o artículo',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Ej: Artículo sobre exportaciones',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
            ),
        ),
        
     
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'revistas',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => 'Campos personalizados para las revistas de Cámara Valencia',
));

endif;