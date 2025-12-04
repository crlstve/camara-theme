<?php
ini_set("error_reporting",E_ALL);
ini_set("display_errors","off");

setlocale(LC_TIME, 'es_ES.UTF-8');

/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',get_stylesheet_directory_uri() . '/style.css',['hello-elementor-theme-style',],'1.0.0'

        
	);	
	
	wp_enqueue_style( 'mytheme-responsive-style', get_stylesheet_directory_uri() . '/css/style_responsive.css', '1.0.0' );

    if (is_singular('jornadas') || is_post_type_archive('jornadas') || is_tax('tipo_jornada')) {
        wp_enqueue_style( 'curso-custom-style', get_stylesheet_directory_uri() . '/css/style.css', array(), '1.0.0' );
    }
    
    $filtro_area = !empty($_GET['filter_area']) ? $_GET['filter_area'] : null;
    if((is_post_type_archive('cursos') || is_post_type_archive('jornadas')) && !$filtro_area) {
        
        wp_enqueue_script( 'calendar-slider', get_stylesheet_directory_uri() . '/js/calendar-slider.js', array(), '1.0.0' );
        wp_enqueue_script( 'splide', get_stylesheet_directory_uri() . '/js/splide.min.js', array(), '1.0.0' );
        wp_enqueue_style( 'splide-style', get_stylesheet_directory_uri() . '/css/splide.min.css', array(), '1.0.0' );

    }


	wp_enqueue_script( 'mi-script-ajax',get_bloginfo('stylesheet_directory') . '/js/ajax.js', array( 'jquery' ),uniqid() );	
	wp_localize_script( 'mi-script-ajax', 'MyAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

add_theme_support( 'custom-header' );

require_once("conf.php");
/*
function custom_skin_posts_widget( $widget ) {
	
	if ($_SERVER["REMOTE_ADDR"] == "185.74.242.12") {

	include_once( 'skin-custom.php' );

	
	$widget->add_skin( new Skin_Custom( $widget ) );
	
	}
	

}
add_action( 'elementor/widget/posts/skins_init', 'custom_skin_posts_widget' );
*/

function custom_icon() {
    global $post;
	
	$template_name = basename(get_page_template_slug(get_the_ID()));
	
	if ($template_name == "template_formacion.php" or get_post_type(get_the_ID()) == "cursos"){
		
        echo '<link rel="shortcut icon" href="/images/favicon.ico" />';
        echo '
        <style type="text/css" media="screen">
        a:link {
            color:#003a5c;
            font-weight: 600;
        }
        a:visited {
            color:#003a5c;
            font-weight: 600;
        }
        a:hover {
            color:#012236;
            font-weight: 600;
        }
        a:active {
            color:#012236; text-decoration: underline;
            font-weight: 600;
        }
        </style>';
    }
}
add_action('wp_head', 'custom_icon');


/*function custom_blog_permalink( $permalink, $post, $leavename ) {
    if ( $post->post_type == 'post' ) { // Verifica si el tipo de contenido es una entrada de blog
        $permalink = home_url( '/blog/' . $post->post_name . '/' );
    }
    return $permalink;
}
add_filter( 'post_link', 'custom_blog_permalink', 10, 3 );
add_filter( 'post_type_link', 'custom_blog_permalink', 10, 3 );*/


/** Registra categoría de Widgets para Elementor**/
function add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'camara',
		[
			'title' => esc_html__( 'Cámara Widgets', 'textdomain' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

// AJAX Handlers para el calendario de eventos
add_action('wp_ajax_get_events_by_date', 'ajax_get_events_by_date');
add_action('wp_ajax_nopriv_get_events_by_date', 'ajax_get_events_by_date');

function ajax_get_events_by_date() {
    global $wpdb;
    $date = sanitize_text_field($_POST['date']);
    
    // Cache de 1 hora
    $cache_key = 'events_by_date_' . $date;
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        wp_send_json_success(array('events' => $cached));
        return;
    }
    
    // Query optimizada para obtener SOLO eventos del día específico
    $query = new WP_Query(array(
        'post_type' => array('cursos', 'ediciones', 'jornadas'),
        'posts_per_page' => -1, // Sin límite, traer todos los eventos del día
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array('key' => 'cursos_fechainicio', 'value' => $date, 'compare' => '=', 'type' => 'NUMERIC'),
            array('key' => 'ediciones_fechainicio', 'value' => $date, 'compare' => '=', 'type' => 'NUMERIC'),
            array('key' => 'jornadas_fechainicio', 'value' => $date, 'compare' => '=', 'type' => 'NUMERIC'),
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => 'jornadas_fechainicio',
        'order' => 'ASC',
        'fields' => 'ids'
    ));
    
    $events = array();
    
    if ($query->posts) {
        foreach ($query->posts as $post_id) {
            $post_type = get_post_type($post_id);
            
            $image_id = get_post_thumbnail_id($post_id);
            if ($post_type == 'ediciones') {
                $curso_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT post_id FROM {$wpdb->prefix}postmeta AS a 
                    LEFT JOIN {$wpdb->prefix}posts AS b ON a.post_id = b.ID 
                    WHERE a.meta_value=%s AND b.post_status = 'publish' LIMIT 1",
                    $post_id
                ));
                if ($curso_id) {
                    $image_id = get_post_thumbnail_id($curso_id);
                }
            }
            
            $image_url = wp_get_attachment_image_src($image_id, 'large');
            if (!$image_url) {
                $image_url = array(get_field('field_jornadas_imagenbanner', $post_id));
            }
            
            $id_lugar = get_post_meta($post_id, $post_type . "_lugar", true);
            $tlugar = get_term($id_lugar, "lugar");
            $date_value = get_post_meta($post_id, $post_type . '_fechainicio', true);
            $fechadia = date("d", strtotime($date_value));
            $fechames = date("M", strtotime($date_value));
            $final_date = $fechadia . ' ' . $fechames;
            $events[] = array(
                'title' => get_the_title($post_id),
                'date' => $final_date,
                'description' => wp_trim_words(wp_strip_all_tags(get_field('field_jornadas_objetivos', $post_id)), 15),
                'image' => $image_url ? $image_url[0] : get_stylesheet_directory_uri() . '/images/default-event.jpg',
                'url' => get_permalink($post_id),
                'type' => ucfirst($post_type),
                'hours' => get_field('field_jornadas_horainicio', $post_id),
                'price' => get_field('field_jornadas_preciodescrip', $post_id),
                'location' => $tlugar ? $tlugar->name : ''
            );
        }
    }
    
    // Cache por 1 hora
    set_transient($cache_key, $events, HOUR_IN_SECONDS);
    
    wp_send_json_success(array('events' => $events));
}

// AJAX Handler para obtener días con eventos del mes
add_action('wp_ajax_get_month_events', 'ajax_get_month_events');
add_action('wp_ajax_nopriv_get_month_events', 'ajax_get_month_events');

function ajax_get_month_events() {
    $year = intval($_POST['year']);
    $month = str_pad(intval($_POST['month']), 2, '0', STR_PAD_LEFT);
    
    // Cache de 1 hora
    $cache_key = 'month_events_' . $year . '_' . $month;
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        wp_send_json_success($cached);
        return;
    }
    
    $start_date = $year . $month . '01';
    $end_date = $year . $month . '31';
    
    // Query optimizada
    $query = new WP_Query(array(
        'post_type' => array('cursos', 'ediciones', 'jornadas'),
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array('key' => 'cursos_fechainicio', 'value' => array($start_date, $end_date), 'compare' => 'BETWEEN', 'type' => 'NUMERIC'),
            array('key' => 'ediciones_fechainicio', 'value' => array($start_date, $end_date), 'compare' => 'BETWEEN', 'type' => 'NUMERIC'),
            array('key' => 'jornadas_fechainicio', 'value' => array($start_date, $end_date), 'compare' => 'BETWEEN', 'type' => 'NUMERIC'),
        ),
        'fields' => 'ids'
    ));
    
    $events_by_date = array();
    
    if ($query->posts) {
        foreach ($query->posts as $post_id) {
            $post_type = get_post_type($post_id);
            $fecha = get_post_meta($post_id, $post_type . '_fechainicio', true);
            
            if ($fecha) {
                if (!isset($events_by_date[$fecha])) {
                    $events_by_date[$fecha] = 0;
                }
                $events_by_date[$fecha]++;
            }
        }
    }
    
    // Cache por 1 hora
    set_transient($cache_key, $events_by_date, HOUR_IN_SECONDS);
    
    wp_send_json_success($events_by_date);
}

