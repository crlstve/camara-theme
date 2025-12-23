<?php 


get_header(); 

// Preparar variables PHP
global $wpdb;


// Obtener filtros de GET (para mantener estado en URL)
$filtro_programa = !empty($_GET['filter_programa']) ? $_GET['filter_programa'] : null;
$filtro_lugar = !empty($_GET['filter_lugar']) ? $_GET['filter_lugar'] : null;
$filtro_fecha = !empty($_GET['filter_fecha']) ? $_GET['filter_fecha'] : null;
$filtro_tipojornada = !empty($_GET['filter_tipojornada']) ? $_GET['filter_tipojornada'] : null;
$filtro_area = !empty($_GET['filter_area']) ? $_GET['filter_area'] : null;


// Obtener IDs filtrados usando la función del plugin
$ids_query = get_posts_ids($filtro_programa, $filtro_lugar, $filtro_fecha);

// Paginación
$pagina = get_query_var('paged') ? get_query_var('paged') : 1;

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

// URL base para paginación
$direccion = get_post_type_archive_link('jornadas');

$terms = get_terms(array('taxonomy' => 'area'));
    foreach ($terms as $term):
        if (in_array($term->slug, (array) $filtro_area)) {
            $title = $term->name;
        }
    endforeach;

?>

<main class="archive-jornadas">
    <?php if(!$filtro_area): ?>
        <!-- Sección cabecera con Hero Slider y Calendario -->
        <section class="hero-slider-section bg-gray-50 pt-8 pb-12 elementor-section elementor-section-boxed">
            <div class="container mx-auto px-4 elementor-container">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
                    
                    <!-- Columna Izquierda: Info del Evento Actual + Slider -->
                    <div class="lg:col-span-9">
                        <!-- Splide Slider -->
                        <div class="splide hero-events-splide flex" id="hero-events-slider">
                            <div class="splide__track">
                                <ul class="splide__list max-h-104" id="hero-slider-wrapper">
                                    <!-- Los slides se cargarán dinámicamente via AJAX -->
                                    <li class="splide__slide flex items-center justify-center h-96">
                                        <div class="text-center text-gray-400">
                                            <p><?php _e('Cargando eventos...', 'camaravalencia'); ?></p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Paginación del Splide -->
                            <ul class="splide__pagination mt-4"></ul>
                        </div>
                        
                    </div>
                    
                    <!-- Columna Derecha: Calendario -->
                    <div class="lg:col-span-4 absolute right-0 bottom-0 translate-y-3 min-w-4/12">
                        <div class="bg-white rounded-2xl shadow-md p-6">
                            <!-- Header del calendario con navegación -->
                            <header id="calendar-header" class="flex items-center justify-between mb-3">
                                <button id="calendar-prev" class="group">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3 h-3">
                                        <path class="group-hover:stroke-(--megamenu-bg-color)" stroke-linecap="round" stroke="#404248" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                
                                <h3 id="calendar-month-year" class="calendar-month-title"></h3>
                                
                                <button id="calendar-next" class="group">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3 h-3">
                                        <path class="group-hover:stroke-(--megamenu-bg-color)" stroke-linecap="round" stroke="#404248" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </header>
                            
                            <!-- Días de la semana -->
                            <div class="grid grid-cols-7 gap-2 mb-2">
                                <div class="text-center text-xs font-medium text-gray-500">L</div>
                                <div class="text-center text-xs font-medium text-gray-500">M</div>
                                <div class="text-center text-xs font-medium text-gray-500">M</div>
                                <div class="text-center text-xs font-medium text-gray-500">J</div>
                                <div class="text-center text-xs font-medium text-gray-500">V</div>
                                <div class="text-center text-xs font-medium text-gray-500">S</div>
                                <div class="text-center text-xs font-medium text-gray-500">D</div>
                            </div>
                            
                            <!-- Grid de días -->
                            <div id="calendar-days" class="grid grid-cols-7 gap-1">
                                <!-- Los días se generarán dinámicamente -->
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

    <?php else: ?>

        <section class="hero-slider-section elementor-section elementor-section-boxed h-24 md:h-72 flex justify-center items-center" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= get_stylesheet_directory_uri(); ?>/images/jornadas.webp'); background-size: cover; background-repeat:no-repeat; background-position: center;">
            <div class="container mx-auto elementor-container flex flex-col text-center">
                <h1 style="color:white;"><?php _e('Jornadas en', 'camaravalencia'); ?> <span><?php esc_attr_e($title); ?></span></h1>
            </div>
        </section>

    <?php endif; ?>
    <!-- Sección de filtros -->
    <section class="filtros-actividades pt-20 pb-8 elementor-section elementor-section-boxed">
        <div class="mx-auto px-4 elementor-container">
            <div id="filtros-actividades-form" class="filtros grid md:grid-cols-7 gap-4 justify-center">
                <!-- Filtro: Tipo de Evento -->
                <div class="filtro-columna">
                    <select id="filter_programa" name="filter_programa" class="filter_selector_select w-full px-4 py-2 border rounded-lg">
                        <option value=""><?php _e('Tipo de evento', 'camaravalencia'); ?></option>
                        <?php
                            $programas = get_terms(array('taxonomy' => 'programa', 'hide_empty' => true, 'orderby' => 'name', 'parent' => 0));
                            foreach ($programas as $programa) :
                                $selected = ($filtro_programa == $programa->slug) ? 'selected' : '';
                        ?>
                                <option value="<?= esc_attr($programa->slug); ?>" <?= $selected; ?>>
                                    <?= esc_html($programa->name); ?>
                                </option>
                        <?php endforeach; ?>
                        <option value="jornadas" <?= ($filtro_programa == 'jornadas') ? 'selected' : ''; ?>>
                            <?php _e('Jornadas y Seminarios', 'camaravalencia'); ?>
                        </option>
                    </select>
                </div>
                <!-- Filtro: Lugar -->
                <div class="filtro-columna">
                    <select id="filter_lugar" name="filter_lugar" class="filter_selector_select w-full px-4 py-2 border rounded-lg">
                        <option value=""><?php _e('Lugar', 'camaravalencia'); ?></option>
                        <?php
                        $fecha_actual = date('Ymd');
                        $lugares = get_terms(array('taxonomy' => 'lugar', 'hide_empty' => false, 'orderby' => 'name', 'parent' => 0));
                        $lugares_con_eventos = array();
                        
                        foreach ($lugares as $lugar) {
                            $tiene_eventos = get_posts(array(
                                'post_type' => array('jornadas'/*, 'ediciones'*/),
                                'post_status' => 'publish',
                                'numberposts' => 1,
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array('key' => 'jornadas_fechainicio', 'value' => $fecha_actual, 'compare' => '>='),
                                    //array('key' => 'ediciones_fechainicio', 'value' => $fecha_actual, 'compare' => '>=')
                                ),
                                'tax_query' => array(array('taxonomy' => 'lugar', 'field' => 'term_id', 'terms' => $lugar->term_id)),
                                'fields' => 'ids'
                            ));
                            if (!empty($tiene_eventos)) {
                                $lugares_con_eventos[] = $lugar;
                            }
                        }
                        
                        foreach ($lugares_con_eventos as $lugar) :
                            $selected = ($filtro_lugar == $lugar->slug) ? 'selected' : '';
                        ?>
                            <option value="<?= esc_attr($lugar->slug); ?>" <?= $selected; ?>>
                                <?= esc_html($lugar->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Filtro: Cuándo -->
                <div class="filtro-columna">
                    <select id="filter_fecha" name="filter_fecha" class="filter_selector_select w-full px-4 py-2 border rounded-lg">
                        <option value=""><?php _e('Fecha', 'camaravalencia'); ?></option>
                        <option value="1" <?= ($filtro_fecha == 1) ? 'selected' : ''; ?>><?php _e('Hoy', 'camaravalencia'); ?></option>
                        <option value="2" <?= ($filtro_fecha == 2) ? 'selected' : ''; ?>><?php _e('Esta semana', 'camaravalencia'); ?></option>
                        <option value="3" <?= ($filtro_fecha == 3) ? 'selected' : ''; ?>><?php _e('Próxima semana', 'camaravalencia'); ?></option>
                        <option value="4" <?= ($filtro_fecha == 4) ? 'selected' : ''; ?>><?php _e('Este mes', 'camaravalencia'); ?></option>
                        <option value="5" <?= ($filtro_fecha == 5) ? 'selected' : ''; ?>><?php _e('Mes que viene', 'camaravalencia'); ?></option>
                    </select>
                </div>
                <!-- Filtro: Tipo de Jornada -->
                <div class="filtro-columna">
                    <select id="filter_tipojornada" name="filter_tipojornada" class="filter_selector_select w-full px-4 py-2 border rounded-lg">
                        <option value=""><?php _e('Tipo de jornada', 'camaravalencia'); ?></option>
                        <?php
                        $tipojornadas = get_terms(array('taxonomy' => 'tipojornada', 'hide_empty' => true, 'orderby' => 'name', 'parent' => 0));
                        $filtro_tipojornada = !empty($_GET['filter_tipojornada']) ? $_GET['filter_tipojornada'] : null;
                        foreach ($tipojornadas as $tipo) :
                            $selected = ($filtro_tipojornada == $tipo->slug) ? 'selected' : '';
                        ?>
                            <option value="<?= esc_attr($tipo->slug); ?>" <?= $selected; ?>>
                                <?= esc_html($tipo->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Filtro: Área -->
                <div class="filtro-columna">
                    <select id="filter_area" name="filter_area" class="filter_selector_select w-full px-4 py-2 border rounded-lg">
                        <option value=""><?php _e('Área', 'camaravalencia'); ?></option>
                        <?php
                        $areas = get_terms(array('taxonomy' => 'area', 'hide_empty' => true, 'orderby' => 'name', 'parent' => 0));
                        foreach ($areas as $area) :
                            $selected = ($filtro_area == $area->slug) ? 'selected' : '';
                        ?>
                            <option value="<?= esc_attr($area->slug); ?>" <?= $selected; ?>>
                                <?= esc_html($area->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filtro-columna flex justify-end gap-5 col-span-2">
                    <button type="button" id="reset-filtros" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors"><?php _e('Limpiar filtros', 'camaravalencia'); ?></button>
                </div>
            </div>
        </div>
    </section>
    <!-- Listado de actividades -->
    <section class="listado-actividades pt-6 pb-12 md:pb-36 elementor-section elementor-section-boxed">
        <div id="listado_actividades" class="container elementor-container mx-auto px-4 flex flex-col">
            
            <?php if ($query->have_posts()) : ?>
                
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <?php while ($query->have_posts()) : $query->the_post();
                        $post_id = get_the_ID();
                        $meta = get_post_meta($post_id);
                        $post_type = get_post_type();
                        $titulo = get_the_title();
                            $tipo_jornada = get_field('field_jornadas_tipojornada', $post_id );
                            $get_term = get_term( $tipo_jornada ); 
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
                        
                        // Lógica específica por tipo de post
                        $curso = $wpdb->get_var("SELECT post_id FROM cv_postmeta AS a LEFT JOIN cv_posts AS b ON a.post_id = b.ID WHERE a.meta_value='$post_id' AND b.post_status = 'publish'");
                        

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
                        
                        // Generar subtítulo según el tipo de post
                        $subtitulo = '';
                        if ($post_type == 'ediciones' && isset($duracion)) {
                            $subtitulo = $duracion . ($area ? ' - ' . $area : '');
                        } else {
                            $subtitulo = ucfirst($post_type) . ($area ? ' - ' . $area : '');
                        }
                        
                        // Colores para el label   
                            if($get_term->name == 'Jornada'){
                                $color_label = '#AF9343';
                                $bg_label = '#F8EABF';
                            } elseif($get_term->name == 'Webinar' || $get_term->name == 'Curso'){
                                $color_label = '#2EA5DA';
                                $bg_label = '#D6ECF5';
                            } elseif($get_term->name == 'Charla'){
                                $color_label = '#D67F2A';
                                $bg_label = '#FAE3D4';
                            } elseif($get_term->name == 'Taller'){
                                $color_label = '#046244';
                                $bg_label = '#C9EDE1';
                            } elseif($get_term->name == 'Encuentro Empresarial'){
                                $color_label = '#E28996';
                                $bg_label = '#720F1C';
                            } else{
                                $color_label = '#404247';
                                $bg_label = '#F4F5FC';
                            }
                        
                        // Preparar variables compatibles con el template del widget
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
                    <!-- Card de actividad vertical -->
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
                                    <span class="evento-clase rounded-full py-1 px-3 text-xs font-medium" style="color:<?= $color_label; ?>;background-color:<?= $bg_label; ?>;">
                                        <?= esc_html($get_term->name); ?>
                                    </span>
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
                            $pagination = paginate_links(array(
                                'base'      => trailingslashit(get_post_type_archive_link('jornadas')) . 'page/%#%/',
                                'format'    => '?paged=%#%',
                                'current'   => max(1, $pagina),
                                'total'     => $query->max_num_pages,
                                'prev_text' => '‹',
                                'next_text' => '›',
                                'type'      => 'array',
                                'add_args'  => array(
                                    'filter_programa' => $filtro_programa,
                                    'filter_lugar'    => $filtro_lugar,
                                    'filter_fecha'    => $filtro_fecha,
                                    'filter_tipojornada' => $filtro_tipojornada,
                                    'filter_area'     => $filtro_area,
                                ),
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
                
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
            
        </div>
    </section>
</main>
<script>
jQuery(document).ready(function($) {
    let isLoading = false;
    let isUpdatingFilters = false;
    
    // Función para actualizar opciones de filtros disponibles
    function actualizarFiltrosDisponibles(cambioDesde = null) {
        if (isUpdatingFilters) return;
        isUpdatingFilters = true;
        
        var filtros = {
            action: 'get_filtros_disponibles',
            filtro_programa: $('#filter_programa').val(),
            filtro_lugar: $('#filter_lugar').val(),
            filtro_fecha: $('#filter_fecha').val(),
            filtro_tipojornada: $('#filter_tipojornada').val(),
            filtro_area: $('#filter_area').val()
        };
        
        $.ajax({
            url: MyAjax.url,
            type: 'POST',
            data: filtros,
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    
                    // Actualizar selector de programas
                    if (!filtros.filtro_programa && cambioDesde !== 'programa') {
                        var programaActual = $('#filter_programa').val();
                        var $programa = $('#filter_programa');
                        var primerOption = $programa.find('option:first');
                        
                        $programa.empty().append(primerOption);
                        
                        $.each(data.programas, function(index, item) {
                            $programa.append($('<option>', {
                                value: item.slug,
                                text: item.name
                            }));
                        });
                        
                        if (programaActual) {
                            $programa.val(programaActual);
                        }
                    }
                    
                    // Actualizar selector de lugares
                    if (!filtros.filtro_lugar && cambioDesde !== 'lugar') {
                        var lugarActual = $('#filter_lugar').val();
                        var $lugar = $('#filter_lugar');
                        var primerOption = $lugar.find('option:first');
                        
                        $lugar.empty().append(primerOption);
                        
                        $.each(data.lugares, function(index, item) {
                            $lugar.append($('<option>', {
                                value: item.slug,
                                text: item.name
                            }));
                        });
                        
                        if (lugarActual) {
                            $lugar.val(lugarActual);
                        }
                    }
                    
                    // Actualizar selector de tipo de jornada
                    if (!filtros.filtro_tipojornada && cambioDesde !== 'tipojornada') {
                        var tipoActual = $('#filter_tipojornada').val();
                        var $tipo = $('#filter_tipojornada');
                        var primerOption = $tipo.find('option:first');
                        
                        $tipo.empty().append(primerOption);
                        
                        $.each(data.tipojornadas, function(index, item) {
                            $tipo.append($('<option>', {
                                value: item.slug,
                                text: item.name
                            }));
                        });
                        
                        if (tipoActual) {
                            $tipo.val(tipoActual);
                        }
                    }
                    
                    // Actualizar selector de área
                    if (!filtros.filtro_area && cambioDesde !== 'area') {
                        var areaActual = $('#filter_area').val();
                        var $area = $('#filter_area');
                        var primerOption = $area.find('option:first');
                        
                        $area.empty().append(primerOption);
                        
                        $.each(data.areas, function(index, item) {
                            $area.append($('<option>', {
                                value: item.slug,
                                text: item.name
                            }));
                        });
                        
                        if (areaActual) {
                            $area.val(areaActual);
                        }
                    }
                }
                isUpdatingFilters = false;
            },
            error: function() {
                console.error('Error al actualizar filtros disponibles');
                isUpdatingFilters = false;
            }
        });
    }
    
    // Función para cargar eventos con AJAX
    function cargarEventos(pagina = 1, actualizarFiltros = true) {
        if (isLoading) return;
        isLoading = true;
        
        // Mostrar indicador de carga
        $('#listado_actividades').css('opacity', '0.5');
        
        var filtros = {
            action: 'filtrar_agenda',
            filtro_programa: $('#filter_programa').val(),
            filtro_lugar: $('#filter_lugar').val(),
            filtro_fecha: $('#filter_fecha').val(),
            filtro_tipojornada: $('#filter_tipojornada').val(),
            filtro_area: $('#filter_area').val(),
            paged: pagina
        };
        
        // Actualizar URL sin recargar
        var params = new URLSearchParams();
        if (filtros.filtro_programa) params.set('filter_programa', filtros.filtro_programa);
        if (filtros.filtro_lugar) params.set('filter_lugar', filtros.filtro_lugar);
        if (filtros.filtro_fecha) params.set('filter_fecha', filtros.filtro_fecha);
        if (filtros.filtro_tipojornada) params.set('filter_tipojornada', filtros.filtro_tipojornada);
        if (filtros.filtro_area) params.set('filter_area', filtros.filtro_area);
        if (pagina > 1) params.set('paged', pagina);
        
        var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);
        
        $.ajax({
            url: MyAjax.url,
            type: 'POST',
            data: filtros,
            success: function(response) {
                if (response.success) {
                    $('#listado_actividades').html(response.data.html);
                    $('#listado_actividades').css('opacity', '1');
                    
                    // Scroll suave al inicio del listado
                    $('html, body').animate({
                        scrollTop: $('#listado_actividades').offset().top - 100
                    }, 500);
                    
                    // Actualizar filtros disponibles después de cargar eventos
                    if (actualizarFiltros) {
                        actualizarFiltrosDisponibles();
                    }
                }
                isLoading = false;
            },
            error: function() {
                console.error('Error al cargar eventos');
                $('#listado_actividades').css('opacity', '1');
                isLoading = false;
            }
        });
    }
    
    // Evento change en selector de programa
    $('#filter_programa').on('change', function() {
        actualizarFiltrosDisponibles('programa');
        cargarEventos(1, false);
    });
    
    // Evento change en selector de lugar
    $('#filter_lugar').on('change', function() {
        actualizarFiltrosDisponibles('lugar');
        cargarEventos(1, false);
    });
    
    // Evento change en selector de fecha (no actualiza otros filtros)
    $('#filter_fecha').on('change', function() {
        cargarEventos(1, true);
    });
    
    // Evento change en selector de tipo de jornada
    $('#filter_tipojornada').on('change', function() {
        actualizarFiltrosDisponibles('tipojornada');
        cargarEventos(1, false);
    });
    
    // Evento change en selector de área
    $('#filter_area').on('change', function() {
        actualizarFiltrosDisponibles('area');
        cargarEventos(1, false);
    });
    
    // Botón limpiar filtros
    $('#reset-filtros').on('click', function() {
        // Limpiar todos los selectores
        $('.filter_selector_select').val('');
        
        // Limpiar URL
        window.history.pushState({}, '', window.location.pathname);
        
        // Actualizar filtros disponibles y cargar eventos
        actualizarFiltrosDisponibles();
        cargarEventos(1, false);
    });
    
    // Delegación de eventos para paginación
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        var pagina = $(this).data('page');
        if (pagina) {
            cargarEventos(pagina, false);
        }
    });
    
    // Cargar filtros disponibles al iniciar
    actualizarFiltrosDisponibles();
});
</script>

<?php get_footer(); ?>