<?php
/**
 * Custom Post Type para Revistas - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Registrar Custom Post Type para Revistas
 */
function camara_register_revista_post_type() {
    
    $labels = array(
        'name'                  => _x( 'Revistas', 'Post Type General Name', 'camara-valencia' ),
        'singular_name'         => _x( 'Revista', 'Post Type Singular Name', 'camara-valencia' ),
        'menu_name'             => __( 'Revistas', 'camara-valencia' ),
        'name_admin_bar'        => __( 'Revista', 'camara-valencia' ),
        'archives'              => __( 'Archivo de Revistas', 'camara-valencia' ),
        'attributes'            => __( 'Atributos de Revista', 'camara-valencia' ),
        'parent_item_colon'     => __( 'Revista Padre:', 'camara-valencia' ),
        'all_items'             => __( 'Todas las Revistas', 'camara-valencia' ),
        'add_new_item'          => __( 'Agregar Nueva Revista', 'camara-valencia' ),
        'add_new'               => __( 'Agregar Nueva', 'camara-valencia' ),
        'new_item'              => __( 'Nueva Revista', 'camara-valencia' ),
        'edit_item'             => __( 'Editar Revista', 'camara-valencia' ),
        'update_item'           => __( 'Actualizar Revista', 'camara-valencia' ),
        'view_item'             => __( 'Ver Revista', 'camara-valencia' ),
        'view_items'            => __( 'Ver Revistas', 'camara-valencia' ),
        'search_items'          => __( 'Buscar Revista', 'camara-valencia' ),
        'not_found'             => __( 'No se encontraron revistas', 'camara-valencia' ),
        'not_found_in_trash'    => __( 'No se encontraron revistas en la papelera', 'camara-valencia' ),
        'featured_image'        => __( 'Imagen Destacada', 'camara-valencia' ),
        'set_featured_image'    => __( 'Establecer imagen destacada', 'camara-valencia' ),
        'remove_featured_image' => __( 'Quitar imagen destacada', 'camara-valencia' ),
        'use_featured_image'    => __( 'Usar como imagen destacada', 'camara-valencia' ),
        'insert_into_item'      => __( 'Insertar en revista', 'camara-valencia' ),
        'uploaded_to_this_item' => __( 'Subido a esta revista', 'camara-valencia' ),
        'items_list'            => __( 'Lista de revistas', 'camara-valencia' ),
        'items_list_navigation' => __( 'Navegación de lista de revistas', 'camara-valencia' ),
        'filter_items_list'     => __( 'Filtrar lista de revistas', 'camara-valencia' ),
    );

    $args = array(
        'label'                 => __( 'Revista', 'camara-valencia' ),
        'description'           => __( 'Revistas de la Cámara de Valencia', 'camara-valencia' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'has_archive'           => 'revistas-camara',
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-book-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'rest_base'             => 'revistas',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'capability_type'       => 'post',
        'rewrite'               => array(
            'slug'                  => 'revista',
            'with_front'            => false,
            'pages'                 => true,
            'feeds'                 => true,
        ),
    );

    register_post_type( 'revistas', $args );
}
add_action( 'init', 'camara_register_revista_post_type', 0 );

/**
 * Forzar el uso del template correcto para el archivo de revistas
 */
function camara_revistas_archive_template( $template ) {
    if ( is_post_type_archive( 'revistas' ) ) {
        $theme_template = locate_template( 'archive-revistas-camara.php' );
        if ( $theme_template ) {
            return $theme_template;
        }
    }
    return $template;
}
add_filter( 'archive_template', 'camara_revistas_archive_template' );
