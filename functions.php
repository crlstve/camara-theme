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
	
	wp_enqueue_style( 'mytheme-responsive-style', get_stylesheet_directory_uri() . '/assets/css/style_responsive.css', '1.0.0' );

        wp_enqueue_style( 'curso-custom-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), '1.0.0' );

    
    $filtro_area = !empty($_GET['filter_area']) ? $_GET['filter_area'] : null;
    if((is_post_type_archive('cursos') || is_post_type_archive('jornadas') || is_page_template('page-agenda.php')) && !$filtro_area) {
        
        wp_enqueue_script( 'calendar-slider', get_stylesheet_directory_uri() . '/assets/js/calendar-slider.js', array(), '1.0.0' );
        wp_enqueue_script( 'splide', get_stylesheet_directory_uri() . '/assets/js/splide.min.js', array(), '1.0.0' );
        wp_enqueue_style( 'splide-style', get_stylesheet_directory_uri() . '/assets/css/splide.min.css', array(), '1.0.0' );

    }


	wp_enqueue_script( 'mi-script-ajax',get_bloginfo('stylesheet_directory') . '/assets/js/ajax.js', array( 'jquery' ),uniqid() );	
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

// AJAX para filtrar eventos en la agenda
add_action('wp_ajax_filtrar_agenda', 'filtrar_agenda_ajax');
add_action('wp_ajax_nopriv_filtrar_agenda', 'filtrar_agenda_ajax');

function filtrar_agenda_ajax() {
    global $wpdb;
    
    // Obtener filtros
    $filtro_programa = !empty($_POST['filtro_programa']) ? sanitize_text_field($_POST['filtro_programa']) : null;
    $filtro_lugar = !empty($_POST['filtro_lugar']) ? sanitize_text_field($_POST['filtro_lugar']) : null;
    $filtro_fecha = !empty($_POST['filtro_fecha']) ? sanitize_text_field($_POST['filtro_fecha']) : null;
    $filtro_tipojornada = !empty($_POST['filtro_tipojornada']) ? sanitize_text_field($_POST['filtro_tipojornada']) : null;
    $filtro_area = !empty($_POST['filtro_area']) ? sanitize_text_field($_POST['filtro_area']) : null;
    $pagina = !empty($_POST['paged']) ? intval($_POST['paged']) : 1;
    
    // Obtener IDs filtrados usando la función del plugin
    $ids_query = function_exists('get_posts_ids') ? get_posts_ids($filtro_programa, $filtro_lugar, $filtro_fecha) : array();
    
    // Preparar tax_query para tipojornada y area
    $tax_query = array();
    if ($filtro_tipojornada) {
        $tax_query[] = array(
            'taxonomy' => 'tipojornada',
            'field'    => 'slug',
            'terms'    => $filtro_tipojornada,
        );
    }
    if ($filtro_area) {
        $tax_query[] = array(
            'taxonomy' => 'area',
            'field'    => 'slug',
            'terms'    => $filtro_area,
        );
    }
    
    // Query principal
    $query_args = array(
        'post_type'       => array('cursos', 'jornadas'),
        'posts_per_page'  => 6,
        'paged'           => $pagina,
        'post__in'        => $ids_query,
        'orderby'         => 'post__in',
    );
    if (!empty($tax_query)) {
        $query_args['tax_query'] = $tax_query;
    }
    $query = new WP_Query($query_args);
    
    ob_start();
    
    if ($query->have_posts()) :
        ?>
        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                $meta = get_post_meta($post_id);
                $post_type = get_post_type();
                $titulo = get_the_title();
                $tipo_jornada = get_field('field_jornadas_tipojornada', $post_id);
                $get_term = get_term($tipo_jornada);
                
                // Preparar datos
                $fechainicio = date("d", strtotime($meta[$post_type . "_fechainicio"][0]));
                $fechafin = date("M", strtotime($meta[$post_type . "_fechainicio"][0]));
                
                $id_lugar = get_post_meta($post_id, $post_type . "_lugar", true);
                $tlugar = get_term($id_lugar, "lugar");
                $lugar = $tlugar ? $tlugar->name : '';
                
                $url = get_the_permalink();
                $url_externa = "";
                
                // Contenido / descripción
                $content = wp_strip_all_tags(get_field('field_jornadas_objetivos', $post_id));
                $hora = get_field('field_jornadas_horainicio', $post_id);
                $precio = get_field('field_jornadas_preciodescrip', $post_id);
                
                // Check jornada club
                $id_jornada_crm_club = function_exists('es_jornada_club') ? es_jornada_club($post_id) : false;
                if ($id_jornada_crm_club) {
                    $url = "https://club.camaravalencia.com/evento/" . $id_jornada_crm_club . "/";
                }
                
                // Evento manual
                if ($post_type == 'jornadas') {
                    $url_evento_manual = $meta["jornadas_urleventomanual"][0];
                    if (!empty($url_evento_manual)) $url_externa = $url_evento_manual;
                }
                
                // Logos de agenda
                $logo_tic = get_field('field_jornadas_agendatic', $post_id);
                $logo_sostenibilidad = get_field('field_jornadas_agendasostenibilidad', $post_id);
                $logo_internacional = get_field('field_jornadas_agendainternacional', $post_id);
                
                $image_id = get_post_thumbnail_id($post_id);
                $id_area = get_post_meta($post_id, "_yoast_wpseo_primary_area", true);
                $tarea = get_term($id_area, "area");
                $area = $tarea ? $tarea->name : '';
                
                // Logos según área
                if ($area == 'Tics y Digitalización') {
                    $logo_tic = true;
                }
                if ($area == 'Sostenibilidad') {
                    $logo_sostenibilidad = true;
                }
                if ($area == 'Internacional') {
                    $logo_internacional = true;
                }
                
                if ($post_type == 'cursos') {
                    $edicion = $wpdb->get_var("SELECT meta_value FROM cv_postmeta WHERE post_id='$post_id' AND meta_key='cursos_edicionactiva'");
                    if ($edicion) {
                        $meta_edicion = get_post_meta($edicion);
                        $fechainicio = date("d", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                        $fechafin = date("M", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                        $id_area = get_post_meta($post_id, "cursos_area", true);
                        $tarea = get_term($id_area, "area");
                        $area = $tarea ? $tarea->name : '';
                        $id_duracion = get_post_meta($post_id, "cursos_duracion", true);
                        $tduracion = get_term($id_duracion, "duracion");
                        $duracion = $tduracion ? $tduracion->name : '';
                        $id_lugar = get_post_meta($edicion, "ediciones_lugar", true);
                        $tlugar = get_term($id_lugar, "lugar");
                        $lugar = $tlugar ? $tlugar->name : '';
                    }
                }
                
                $image_url = wp_get_attachment_image_src($image_id, 'img_instalaciones');
                $descrip_lugar = get_post_meta($post_id, "jornadas_lugardescrip", true);
                if ($descrip_lugar) $lugar = wp_strip_all_tags($descrip_lugar);
                
                // Colores para el label
                if ($get_term && $get_term->name == 'Jornada') {
                    $color_label = '#AF9343';
                    $bg_label = '#F8EABF';
                } elseif ($get_term && ($get_term->name == 'Webinar' || $get_term->name == 'Curso')) {
                    $color_label = '#2EA5DA';
                    $bg_label = '#D6ECF5';
                } elseif ($get_term && $get_term->name == 'Charla') {
                    $color_label = '#D67F2A';
                    $bg_label = '#FAE3D4';
                } elseif ($get_term && $get_term->name == 'Taller') {
                    $color_label = '#046244';
                    $bg_label = '#C9EDE1';
                } elseif ($get_term && $get_term->name == 'Encuentro Empresarial') {
                    $color_label = '#E28996';
                    $bg_label = '#720F1C';
                } else {
                    $color_label = '#404247';
                    $bg_label = '#F4F5FC';
                }
                
                $evento_fecha = $fechainicio . ' ' . $fechafin;
                $max_text = (strlen($content) > 80) ? substr($content, 0, 80) . '...' : $content;
                
                $img = $image_url ? $image_url[0] : get_field('field_jornadas_imagenbanner', $post_id);
                
                // Logo según tipo
                $logo = '';
                if ($logo_tic) {
                    $logo = '<img class="agenda-logo " src="' . get_stylesheet_directory_uri() . '/images/logos/tic.png" alt="Tic Negocios" />';
                }
                if ($logo_sostenibilidad) {
                    $logo = '<img class="agenda-logo " src="' . get_stylesheet_directory_uri() . '/images/logos/sostenibilidad.png" alt="Sostenibilidad" />';
                }
                if ($logo_internacional) {
                    $logo = '<img class="agenda-logo " src="' . get_stylesheet_directory_uri() . '/images/logos/internacional.png" alt="Internacional" />';
                }
                
                $final_url = !empty($url_externa) ? $url_externa : $url;
            ?>
            <li class="rounded-lg shadow-md bg-white border border-[#e0e0e0] overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
                <a class="agenda-item flex flex-col h-full" href="<?= esc_url($final_url); ?>">
                    <figure class="w-full h-48 bg-cover bg-center flex items-end p-4" style="background-image: url('<?= esc_url($img); ?>');">
                        <?php if ($logo) : ?>
                            <div class="agenda-figure bg-[rgba(0,0,0,0.2)] backdrop-blur-xs px-5 py-2 rounded-full h-fit w-fit">
                                <?= $logo; ?>
                            </div>
                        <?php endif; ?>
                    </figure>
                    <div class="agenda-item-content w-full px-5 py-3 flex flex-col justify-between grow">
                        <header>
                            <h4 class="text-left text-lg font-semibold"><?= esc_html($titulo); ?></h4>
                        </header>
                        <p class="agenda-item-body text-left pb-3 text-sm text-gray-600 grow">
                            <?php if ($hora) : ?>
                                <span class="agenda-meta font-medium"><?= esc_html($hora); ?> |</span>
                            <?php endif; ?>
                            <?php if ($precio) : ?>
                                <span class="agenda-meta font-medium"><?= esc_html($precio); ?> | </span>
                            <?php endif; ?>
                            <?php if ($lugar) : ?>
                                <span class="agenda-meta font-medium"><?= esc_html($lugar); ?></span>
                            <?php endif; ?>
                            <?php if ($max_text) : ?>
                                <span class="block mt-2"><?= esc_html($max_text); ?></span>
                            <?php endif; ?>
                        </p>
                        <footer class="evento-meta flex justify-between items-center pt-4 border-t border-[#e0e0e0]">
                            <time class="evento-fecha text-sm font-medium"><?= esc_html($evento_fecha); ?></time>
                            <?php if ($get_term) : ?>
                                <span class="evento-clase rounded-full py-1 px-3 text-xs font-medium" style="color:<?= $color_label; ?>;background-color:<?= $bg_label; ?>;">
                                    <?= esc_html($get_term->name); ?>
                                </span>
                            <?php endif; ?>
                        </footer>
                    </div>
                </a>
            </li>
            <?php endwhile; ?>
        </ul>
        
        
        <!-- Paginación -->
        <div class="paginacion mt-12 flex justify-center">
            <div class="flex items-center gap-2">
                <?php

                //forzar la barra que Permalink Manager quita
                function fix_jornadas_pagination_base() {
                    global $wp_rewrite;
                    $wp_rewrite->pagination_base = 'page';
                    $wp_rewrite->flush_rules();
                }
                add_action('init', 'fix_jornadas_pagination_base');

                    $direccion = get_post_type_archive_link('jornadas');
                    $pagination = paginate_links(array(
                        'base'      => trailingslashit(get_post_type_archive_link('jornadas')) . 'page/%#%/',
                        'format'    => '?paged=%#%',
                        'current'   => max(1, $pagina),
                        'total'     => $query->max_num_pages,
                        'prev_text' => '‹',
                        'next_text' => '›',
                        'type'      => 'array',
                    ));
                    if ($pagination) {
                        foreach ($pagination as $page) {
                            // Detectar si es el enlace actual
                            if (strpos($page, 'current') !== false) {
                                echo '<span class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1a1a1a] text-white text-sm font-medium">' . strip_tags($page) . '</span>';
                            } else {
                                // Añadir las clases de Tailwind a los enlaces
                                echo str_replace('<a ', '<a class="w-10 h-10 flex items-center justify-center rounded-full border border-[#d0d0d0] text-[#666] text-sm font-medium hover:border-[#1a1a1a] hover:text-[#1a1a1a] transition-colors" ', $page);
                            }
                        }
                    }
                ?>
            </div>
        </div>
    <?php else : ?>
        <div class="sin-resultados text-center py-12">
            <p class="text-xl text-gray-600"><?php _e('No existe ningún evento con estos filtros', 'camaravalencia'); ?></p>
        </div>
    <?php endif;
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'total_pages' => $query->max_num_pages,
        'found_posts' => $query->found_posts
    ));
}

// AJAX para obtener opciones disponibles de filtros
add_action('wp_ajax_get_filtros_disponibles', 'get_filtros_disponibles_ajax');
add_action('wp_ajax_nopriv_get_filtros_disponibles', 'get_filtros_disponibles_ajax');

function get_filtros_disponibles_ajax() {
    global $wpdb;
    
    // Obtener filtros actuales
    $filtro_programa = !empty($_POST['filtro_programa']) ? sanitize_text_field($_POST['filtro_programa']) : null;
    $filtro_lugar = !empty($_POST['filtro_lugar']) ? sanitize_text_field($_POST['filtro_lugar']) : null;
    $filtro_fecha = !empty($_POST['filtro_fecha']) ? sanitize_text_field($_POST['filtro_fecha']) : null;
    $filtro_tipojornada = !empty($_POST['filtro_tipojornada']) ? sanitize_text_field($_POST['filtro_tipojornada']) : null;
    $filtro_area = !empty($_POST['filtro_area']) ? sanitize_text_field($_POST['filtro_area']) : null;
    
    // Obtener IDs base según filtros actuales
    $ids_query = function_exists('get_posts_ids') ? get_posts_ids($filtro_programa, $filtro_lugar, $filtro_fecha) : array();
    
    if (empty($ids_query)) {
        $ids_query = array(0); // Evitar errores en query
    }
    
    // Preparar tax_query base
    $base_tax_query = array('relation' => 'AND');
    if ($filtro_tipojornada) {
        $base_tax_query[] = array(
            'taxonomy' => 'tipojornada',
            'field'    => 'slug',
            'terms'    => $filtro_tipojornada,
        );
    }
    if ($filtro_area) {
        $base_tax_query[] = array(
            'taxonomy' => 'area',
            'field'    => 'slug',
            'terms'    => $filtro_area,
        );
    }
    
    $response = array(
        'programas' => array(),
        'lugares' => array(),
        'tipojornadas' => array(),
        'areas' => array()
    );
    
    // Obtener programas disponibles
    if (!$filtro_programa) {
        $fecha_actual = date('Ymd');
        
        // Obtener términos de programa con eventos
        $programas = get_terms(array(
            'taxonomy' => 'programa',
            'hide_empty' => false,
            'parent' => 0
        ));
        
        foreach ($programas as $programa) {
            $temp_ids = function_exists('get_posts_ids') ? get_posts_ids($programa->slug, $filtro_lugar, $filtro_fecha) : array();
            
            if (empty($temp_ids)) continue;
            
            $temp_tax_query = $base_tax_query;
            
            $args = array(
                'post_type' => array('cursos', 'jornadas'),
                'posts_per_page' => 1,
                'post__in' => $temp_ids,
                'fields' => 'ids',
                'tax_query' => count($temp_tax_query) > 1 ? $temp_tax_query : array()
            );
            
            $posts = get_posts($args);
            
            if (!empty($posts)) {
                $response['programas'][] = array(
                    'slug' => $programa->slug,
                    'name' => $programa->name
                );
            }
        }
        
        // Agregar opción "Jornadas y Seminarios"
        $temp_ids = function_exists('get_posts_ids') ? get_posts_ids('jornadas', $filtro_lugar, $filtro_fecha) : array();
        if (!empty($temp_ids)) {
            $temp_tax_query = $base_tax_query;
            $args = array(
                'post_type' => array('jornadas'),
                'posts_per_page' => 1,
                'post__in' => $temp_ids,
                'fields' => 'ids',
                'tax_query' => count($temp_tax_query) > 1 ? $temp_tax_query : array()
            );
            $posts = get_posts($args);
            if (!empty($posts)) {
                $response['programas'][] = array(
                    'slug' => 'jornadas',
                    'name' => __('Jornadas y Seminarios', 'camaravalencia')
                );
            }
        }
    }
    
    // Obtener lugares disponibles
    if (!$filtro_lugar) {
        $lugares = get_terms(array(
            'taxonomy' => 'lugar',
            'hide_empty' => false,
            'parent' => 0
        ));
        
        foreach ($lugares as $lugar) {
            $temp_ids = function_exists('get_posts_ids') ? get_posts_ids($filtro_programa, $lugar->slug, $filtro_fecha) : array();
            
            if (empty($temp_ids)) continue;
            
            $temp_tax_query = $base_tax_query;
            
            $args = array(
                'post_type' => array('cursos', 'jornadas'),
                'posts_per_page' => 1,
                'post__in' => $temp_ids,
                'fields' => 'ids',
                'tax_query' => count($temp_tax_query) > 1 ? $temp_tax_query : array()
            );
            
            $posts = get_posts($args);
            
            if (!empty($posts)) {
                $response['lugares'][] = array(
                    'slug' => $lugar->slug,
                    'name' => $lugar->name
                );
            }
        }
    }
    
    // Obtener tipos de jornada disponibles
    if (!$filtro_tipojornada) {
        $tipojornadas = get_terms(array(
            'taxonomy' => 'tipojornada',
            'hide_empty' => false,
            'parent' => 0
        ));
        
        foreach ($tipojornadas as $tipo) {
            $temp_tax_query = array('relation' => 'AND');
            $temp_tax_query[] = array(
                'taxonomy' => 'tipojornada',
                'field'    => 'slug',
                'terms'    => $tipo->slug,
            );
            if ($filtro_area) {
                $temp_tax_query[] = array(
                    'taxonomy' => 'area',
                    'field'    => 'slug',
                    'terms'    => $filtro_area,
                );
            }
            
            $args = array(
                'post_type' => array('cursos', 'jornadas'),
                'posts_per_page' => 1,
                'post__in' => $ids_query,
                'fields' => 'ids',
                'tax_query' => $temp_tax_query
            );
            
            $posts = get_posts($args);
            
            if (!empty($posts)) {
                $response['tipojornadas'][] = array(
                    'slug' => $tipo->slug,
                    'name' => $tipo->name
                );
            }
        }
    }
    
    // Obtener áreas disponibles
    if (!$filtro_area) {
        $areas = get_terms(array(
            'taxonomy' => 'area',
            'hide_empty' => false,
            'parent' => 0
        ));
        
        foreach ($areas as $area) {
            $temp_tax_query = array('relation' => 'AND');
            if ($filtro_tipojornada) {
                $temp_tax_query[] = array(
                    'taxonomy' => 'tipojornada',
                    'field'    => 'slug',
                    'terms'    => $filtro_tipojornada,
                );
            }
            $temp_tax_query[] = array(
                'taxonomy' => 'area',
                'field'    => 'slug',
                'terms'    => $area->slug,
            );
            
            $args = array(
                'post_type' => array('cursos', 'jornadas'),
                'posts_per_page' => 1,
                'post__in' => $ids_query,
                'fields' => 'ids',
                'tax_query' => $temp_tax_query
            );
            
            $posts = get_posts($args);
            
            if (!empty($posts)) {
                $response['areas'][] = array(
                    'slug' => $area->slug,
                    'name' => $area->name
                );
            }
        }
    }
    
    wp_send_json_success($response);
}

/**
 * Registrar widgets personalizados de Elementor
 */
function register_camara_elementor_widgets( $widgets_manager ) {
    
    // Lista de widgets a registrar
    $widgets = array(
        'agenda_formacion' => array(
            'class' => 'Camara_Agenda_Formacion_Widget',
            'file'  => 'widget-agenda_formacion.php'
        ),
        'agenda_noticias' => array(
            'class' => 'Camara_Agenda_Noticias_Widget',
            'file'  => 'widget-agenda_noticias.php'
        ),
        'archive' => array(
            'class' => 'Camara_Archive_Widget',
            'file'  => 'widget-archive.php'
        ),
        'areas' => array(
            'class' => 'Camara_Areas_Widget',
            'file'  => 'widget-areas.php'
        ),
        'megamenu' => array(
            'class' => 'Camara_Megamenu_Widget',
            'file'  => 'widget-megamenu.php'
        ),
        'revistas' => array(
            'class' => 'Camara_Revistas_Widget',
            'file'  => 'widget-revistas.php'
        ),
        'slider_home' => array(
            'class' => 'Camara_Slider_Home_Widget',
            'file'  => 'widget-slider_home.php'
        ),
        'video_hero' => array(
            'class' => 'Camara_Video_Hero_Widget',
            'file'  => 'widget-video_hero.php'
        ),
    );
    
    // Registrar cada widget
    foreach ( $widgets as $widget_dir => $widget_data ) {
        $widget_file = get_stylesheet_directory() . '/widgets/' . $widget_dir . '/' . $widget_data['file'];
        
        if ( file_exists( $widget_file ) ) {
            require_once( $widget_file );
            
            if ( class_exists( $widget_data['class'] ) ) {
                $widgets_manager->register( new $widget_data['class']() );
            }
        }
    }
}
add_action( 'elementor/widgets/register', 'register_camara_elementor_widgets' );

/**
 * Cargar archivos de funciones auxiliares de los widgets
 */
function load_camara_widget_functions() {
    $widget_functions = array(
        'widgets/agenda_formacion/functions-agenda_formacion.php',
        'widgets/agenda_noticias/functions-agenda_noticias.php',
        'widgets/archive/functions-archive.php',
        'widgets/areas/functions-areas.php',
        'widgets/megamenu/functions-megamenu.php',
        'widgets/megamenu/custom-nav-fields.php',
        'widgets/megamenu/acf-megamenu.php',
        'widgets/revistas/functions-revistas.php',
        'widgets/revistas/cpt-revistas.php',
        'widgets/revistas/acf-revistas.php',
        'widgets/slider_home/functions-slider_home.php',
        'widgets/slider_home/cpt-slider_home.php',
        'widgets/slider_home/acf-slider_home.php',
        'widgets/video_hero/functions-video_hero.php',
    );
    
    foreach ( $widget_functions as $file ) {
        $file_path = get_stylesheet_directory() . '/' . $file;
        if ( file_exists( $file_path ) ) {
            require_once( $file_path );
        }
    }
}
add_action( 'after_setup_theme', 'load_camara_widget_functions' );

