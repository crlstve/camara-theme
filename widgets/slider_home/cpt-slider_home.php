<?php
/**
 * Custom Post Type para Slider - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registrar Custom Post Type para Revistas
 */


function camara_register_slider_home_post_type() {
    
    $labels = array(
        'name'                  => _x( 'Slider Home', 'Post Type General Name', 'camara-valencia' ),
        'singular_name'         => _x( 'Slider Home', 'Post Type Singular Name', 'camara-valencia' ),
        'menu_name'             => __( 'Slider Home', 'camara-valencia' ),
        'name_admin_bar'        => __( 'Slider Home', 'camara-valencia' ),
        'archives'              => __( 'Archivo de Slider Home', 'camara-valencia' ),
        'attributes'            => __( 'Atributos de Slider Home', 'camara-valencia' ),
        'parent_item_colon'     => __( 'Slider Home Padre:', 'camara-valencia' ),
        'all_items'             => __( 'Todas las Slider Home', 'camara-valencia' ),
        'add_new_item'          => __( 'Agregar Nueva Slide', 'camara-valencia' ),
        'add_new'               => __( 'Agregar Nueva', 'camara-valencia' ),
        'new_item'              => __( 'Nueva Slide', 'camara-valencia' ),
        'edit_item'             => __( 'Editar Slide', 'camara-valencia' ),
        'update_item'           => __( 'Actualizar Slide', 'camara-valencia' ),
        'view_item'             => __( 'Ver Slide', 'camara-valencia' ),
        'view_items'            => __( 'Ver Slide', 'camara-valencia' ),
        'search_items'          => __( 'Buscar Slide', 'camara-valencia' ),
        'not_found'             => __( 'No se encontraron Slide', 'camara-valencia' ),
        'not_found_in_trash'    => __( 'No se encontraron Slide en la papelera', 'camara-valencia' ),
        'featured_image'        => __( 'Imagen Destacada', 'camara-valencia' ),
        'set_featured_image'    => __( 'Establecer imagen destacada', 'camara-valencia' ),
        'remove_featured_image' => __( 'Quitar imagen destacada', 'camara-valencia' ),
        'use_featured_image'    => __( 'Usar como imagen destacada', 'camara-valencia' ),
        'insert_into_item'      => __( 'Insertar en Slide', 'camara-valencia' ),
        'uploaded_to_this_item' => __( 'Subido a esta Slide', 'camara-valencia' ),
        'items_list'            => __( 'Lista de Slides', 'camara-valencia' ),
        'items_list_navigation' => __( 'Navegación de lista de Slides', 'camara-valencia' ),
        'filter_items_list'     => __( 'Filtrar lista de Slides', 'camara-valencia' ),
    );

    $args = array(
        'label'                 => __( 'Slider Home', 'camara-valencia' ),
        'description'           => __( 'Slider Home de la Cámara de Valencia', 'camara-valencia' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'revisions', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'has_archive'           => 'slider-home-camara',
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-slides',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => false,
        'rest_base'             => 'slider_home',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'capability_type'       => 'post',
        'rewrite'               => array(
            'slug'                  => 'slider_home',
            'with_front'            => false,
            'pages'                 => true,
            'feeds'                 => true,
        ),
    );

    register_post_type( 'slider_home', $args );
}
add_action( 'init', 'camara_register_slider_home_post_type', 0 );






?>