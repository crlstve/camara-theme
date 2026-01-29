<?php
/**
 * Widget de Agenda / Noticias para Elementor - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Verificar que Elementor esté cargado
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

/**
 * Clase del Widget Agenda / Noticias
 */
class Camara_Agenda_Noticias_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'agenda_noticias_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Agenda / Noticias', 'agenda_noticias_widget' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-dual-button';
    }

    /**
     * Categoría del widget
     */
    public function get_categories() {
        global $camara_elementor_category_registered;
        
        // Usar la categoría registrada globalmente, o 'general' como fallback
        $category = $camara_elementor_category_registered ?: 'general';
        
        return [ $category ];
    }

    /**
     * Palabras clave
     */
    public function get_keywords() {
        return [ 'agenda', 'noticias', 'calendario', 'eventos', 'agenda_noticias' ];
    }

    /**
     * Cargar estilos del widget
     */
    public function get_style_depends() {
        return [ 'agenda_noticias-splide' ];
    }
    /**
     * Cargar scripts del widget
     */
    public function get_script_depends() {
        return [ 'agenda_noticias-splide', 'agenda_noticias-script' ];
    }
    
    /**
     * Registrar estilos del widget
     */
    protected function register_styles() {
        wp_register_style( 'agenda_noticias-splide', get_stylesheet_directory_uri() . '/assets/css/splide.min.css', array(), '4.1.4' );
    }

    protected function register_scripts() {
        wp_register_script( 'agenda_noticias-splide', get_stylesheet_directory_uri() . '/assets/js/splide.min.js', array(), '4.1.4', true );
        wp_register_script( 'agenda_noticias-script', get_stylesheet_directory_uri() . '/assets/js/agenda_noticias.js', array('agenda_noticias-splide'), '1.0.2', true );
    }
    /**
     * Constructor del widget
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->register_styles();
        $this->register_scripts();
    }

    /**
     * Configuración de controles
     */
    protected function _register_controls() {
        
        // Sección de Contenido Principal
            // Título Principal     
            $this->start_controls_section(
                    'title_section',
                    [
                        'label' => __( 'Título', 'agenda_noticias' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
            );

                $this->add_control(
                    'main_title',
                    [
                        'label' => __( 'Título Principal', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Nuestra actualidad', 'agenda_noticias' ),
                        'placeholder' => __( 'Escribe el título principal...', 'agenda_noticias' ),
                        'label_block' => true,
                    ]
                );

            $this->end_controls_section();

            // Agenda
            $this->start_controls_section(
                'agenda_section',
                [
                    'label' => __( 'Agenda', 'agenda_noticias' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
                // Subtítulo Agenda
                $this->add_control(
                    'agenda_subtitle',
                    [
                        'label' => __( 'Subtítulo Agenda', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => __( 'Próximos eventos', 'agenda_noticias' ),
                        'placeholder' => __( 'Escribe el subtítulo...', 'agenda_noticias' ),
                        'rows' => 1,
                        'label_block' => true,
                    ]
                );
                // Botón Ver Más de la Agenda
                $this->add_control(
                    'agenda_button_text',
                    [
                        'label' => __( 'Texto del Botón de la Agenda', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Ver Más Eventos', 'agenda_noticias' ),
                        'placeholder' => __( 'Escribe el texto del botón...', 'agenda_noticias' ),
                        'label_block' => true,
                    ],
                );
                // Enlace del Botón de la Agenda
                $this->add_control(
                    'agenda_button_link',
                    [
                        'label' => __( 'Enlace', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => __( 'https://tu-enlace.com', 'agenda_noticias' ),
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                        'label_block' => true,
                    ]
                );

            $this->end_controls_section();

            // Noticias 
            $this->start_controls_section(
                'news_section',
                [
                    'label' => __( 'Noticias', 'agenda_noticias' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
                // Subtítulo Noticias
                $this->add_control(
                    'noticias_subtitle',
                    [
                        'label' => __( 'Subtítulo Noticias', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => __( 'Últimas noticias', 'agenda_noticias' ),
                        'placeholder' => __( 'Escribe el subtítulo...', 'agenda_noticias' ),
                        'rows' => 1,
                        'label_block' => true,
                    ]
                );

                // Botón Ver Más de las Noticias
                $this->add_control(
                    'noticias_button_text',
                    [
                        'label' => __( 'Texto del Botón de las Noticias', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Ver Más Noticias', 'agenda_noticias' ),
                        'placeholder' => __( 'Escribe el texto del botón...', 'agenda_noticias' ),
                        'label_block' => true,
                    ]
                );

                // Enlace del Botón de las Noticias
                $this->add_control(
                    'noticias_button_link',
                    [
                        'label' => __( 'Enlace', 'agenda_noticias' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => __( 'https://tu-enlace.com', 'agenda_noticias' ),
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                        ],
                        'label_block' => true,
                    ]
                );

            $this->end_controls_section();


        // Sección de Tipografía
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __( 'Tipografía', 'agenda_noticias' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Tipografía del Título Principal', 'agenda_noticias' ),
                'selector' => '{{WRAPPER}} .agenda_noticias-widget .main-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => __( 'Tipografía del Subtítulo', 'agenda_noticias' ),
                'selector' => '{{WRAPPER}} .agenda_noticias-widget .main-subtitle',
            ]
        );
        $this->end_controls_section();

        // Sección de Colores Principales
        $this->start_controls_section(
            'main_colors_section',
            [
                'label' => __( 'Colores Principales', 'agenda_noticias' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color del Título Principal', 'agenda_noticias' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agenda_noticias-widget .main-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Color del Subtítulo', 'agenda_noticias' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agenda_noticias-widget .main-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        
        // Asegurar que los estilos y scripts estén cargados
        wp_enqueue_style( 'agenda_noticias-splide' );
        wp_enqueue_script( 'agenda_noticias-splide' );
        wp_enqueue_script( 'agenda_noticias-script' );

        // Obtener los IDs de los posts usando la misma función que el shortcode
        if ( ! function_exists( 'get_posts_ids' ) ) {
            // Si la función no existe, incluir el archivo que la contiene
            if ( file_exists( ABSPATH . 'wp-content/plugins/camara_jornadas/inc/shortcodes.php' ) ) {
                include_once ABSPATH . 'wp-content/plugins/camara_jornadas/inc/shortcodes.php';
            }
        }
        
        global $wpdb;
        $ids_query = function_exists( 'get_posts_ids' ) ? get_posts_ids() : array();
        
        $agenda = new WP_Query( array(
            'post_type' => array('cursos','jornadas'),
            'posts_per_page' => 6,
            'post__in' => !empty($ids_query) ? $ids_query : array(0), // Si no hay IDs, usar 0 para no mostrar nada
            'orderby' => 'post__in',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'jornadas_exclusivoclub',
                        'compare' => 'NOT EXISTS' // Si el campo no existe
                    ),
                    array(
                        'key' => 'jornadas_exclusivoclub',
                        'value' => '',
                        'compare' => '=' // Si el campo está vacío
                    )
                ),
                array(
                    'key' => 'jornadas_exclusivoclub',
                    'value' => 'on',
                    'compare' => '!=' // Excluir los posts con el valor 'on'
                )
            )
        ) );

        // Wordpress Query para obtener las noticias
            global $wpdb;
            $param_tipo = '';	
            $args = array(
                'post_type' => 'noticias',
                'posts_per_page' => 6,
                'post_status'       => 'publish',
                'orderby'           => 'date',
                'orderby'           => 'meta_value_num',
                'meta_key'          => 'noticias_fecha',
                'order'             => 'DESC',
                'suppress_filters'  => false,
                'meta_query'        => array(
                    'relation'      => 'AND',
                    array(
                        'key'       => 'noticias_eshome',
                        'compare'   => 'LIKE',
                        'value'     => 'on',
                    ),
                ),      
            );
            if ($param_tipo == "formacion") {    
                $args['meta_query'] = array(
                    'relation'      => 'AND',
                    array(
                        'key'       => 'noticias_esformacion',
                        'compare'   => 'LIKE',
                        'value'     => 'on',
                    ),
                );
            }    
            $noticias = new WP_Query(  $args );      

            // Filtrar noticias por idioma WPML si está activo
            if (defined('ICL_LANGUAGE_CODE') && !empty($noticias->posts)) {
                $filtered_posts = array();
                foreach ($noticias->posts as $post) {
                    $post_language = apply_filters('wpml_post_language_details', null, $post->ID);
                    if ($post_language && $post_language['language_code'] == ICL_LANGUAGE_CODE) {
                        $filtered_posts[] = $post;
                    }
                }
                $noticias->posts = $filtered_posts;
                $noticias->post_count = count($filtered_posts);
                $noticias->found_posts = count($filtered_posts);
            }

                if ( ! empty( $settings['agenda_button_text'] ) && ! empty( $settings['agenda_button_link']['url'] ) ){ 
                    $agenda_button_url = $settings['agenda_button_link']['url'];
                    $agenda_button_target = $settings['agenda_button_link']['is_external'] ? ' target="_blank"' : '';
                    $agenda_button_nofollow = $settings['agenda_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                } 

        $settings = $this->get_settings_for_display();
    ?>
        <div class="agenda_noticias-widget areas-widget w-full ">
            
            <div class="agenda_noticias-header text-center mb-5">
                <?php if ( ! empty( $settings['main_title'] ) ) : ?>
                    <div class="agenda_noticias-title-wrapper areas-title-wrapper">
                        <h2 class="main-title"><?= esc_html( $settings['main_title'] ); ?></h2>
                    </div>
                <?php endif; ?>
                
                <div class="agenda_noticias-content">

                    <header class="section-header pl-4 md:pl-0 flex flex-col md:flex-row justify-between relative text-center mb-6">

                        <div class="tab-title pl-6 md:pl-0">
                            <?php if ( ! empty( $settings['agenda_subtitle'] ) ) : ?>
                                <h3 class="section-subtitle active md:translate-y-4"><?= esc_html( $settings['agenda_subtitle'] ); ?></h3>
                            <?php endif; ?>                        
                            
                            <?php if ( ! empty( $settings['noticias_subtitle'] ) ) : ?>
                                <h3 class="section-subtitle md:translate-y-4"><?= esc_html( $settings['noticias_subtitle'] ); ?></h3>
                            <?php endif; ?>
                        </div>

                        <div class="tab-switcher relative flex justify-center mb-2 mt-12 md:mt-0 border bg-transparent border-[#404248] rounded-full w-fit p-2">
                            <div class="tab-slider"></div>
                            <button class="tab-btn active z-10" data-tab="agenda-slide" type="button">
                                Agenda
                            </button>
                            <button class="tab-btn z-10" data-tab="noticias-slide" type="button">
                                Noticias
                            </button>
                        </div>

                    </header>

                    <div id="agenda-slide" class="splide tab-content active" role="group" aria-label="Contenedor Agenda">

                        <div class="splide__track">

                            <ul class="agenda-grid splide__list">

                                <?php 
                                if ( $agenda->have_posts() ) :
                                    while ( $agenda->have_posts() ) : $agenda->the_post();
                                        $post_id = get_the_ID();
                                        $curso = '';
                                        $meta = get_post_meta($post_id);
                                        $post_type = get_post_type();
                                        $mostrar_exclusivo_logo = '';
                                        $title = get_the_title();
                                        $tipo_jornada = get_field('field_jornadas_tipojornada', $post_id );

                                            $get_term = get_term( $tipo_jornada );
                                        //contenido
                                        $content = wp_strip_all_tags( get_field('field_jornadas_objetivos', $post_id ) );

                                        $hora = get_field('field_jornadas_horainicio', $post_id );
                                        $precio = get_field('field_jornadas_preciodescrip', $post_id );
                                        // Obtener fecha de inicio como lo hace el shortcode
                                        $fechainicio = date("d", strtotime($meta[$post_type."_fechainicio"][0]));
                                        $fechafin = date("M", strtotime($meta[$post_type."_fechainicio"][0]));
                                        
                                        // Obtener lugar
                                        $id_lugar = get_post_meta($post_id, $post_type."_lugar", true);
                                        $tlugar = get_term($id_lugar, "lugar");
                                        $lugar = (!is_wp_error($tlugar) && $tlugar) ? $tlugar->name : '';
                                        
                                        $url = get_the_permalink();
                                        $url_externa = $meta[$post_type.'_urleventomanual'][0];

                                        //Logo
                                            $logo_tic = get_field('field_jornadas_agendatic', $post_id );
                                            $logo_sostenibilidad = get_field('field_jornadas_agendasostenibilidad', $post_id );
                                            $logo_internacional = get_field('field_jornadas_agendainternacional', $post_id );
                                        
                                        // Verificar si es una edición y obtener datos del curso padre
                                        $curso = $wpdb->get_var("SELECT post_id FROM {$wpdb->prefix}postmeta AS a LEFT JOIN {$wpdb->prefix}posts AS b ON a.post_id = b.ID WHERE a.meta_value='$post_id' AND b.post_status = 'publish' ");
                                        

                                            $image_id = get_post_thumbnail_id($post_id);
                                            // Área
                                            $id_area = get_post_meta($post_id, "_yoast_wpseo_primary_area", true);
                                            $tarea = get_term($id_area, "area");
                                            $area = $tarea ? $tarea->name : '';
                                            $titulo = get_the_title();

                                            //Logo
                                                $area = get_field('field_cursos_area', $post_id );
                                                if($area == 'Tics y Digitalización'){
                                                    $logo_tic = true;
                                                }
                                                if($area == 'Sostenibilidad'){
                                                    $logo_sostenibilidad = true;
                                                }
                                                if($area == 'Internacional'){
                                                    $logo_internacional = true;
                                                }

                                            if ($post_type == 'cursos') {
                                                // La fecha inicio y fin es sobre la edición
                                                $edicion = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id='$post_id' AND meta_key='cursos_edicionactiva' ");
                                                if ($edicion) {
                                                    $meta_edicion = get_post_meta($edicion);
                                                    $fechainicio = date("d", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                                                    $fechafin = date("M", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                                                    // Área
                                                    $id_area = get_post_meta($post_id, "cursos_area", true);
                                                    $tarea = get_term($id_area, "area");
                                                    $area = $tarea ? $tarea->name : '';
                                                    // Duración
                                                    $id_duracion = get_post_meta($post_id, "cursos_duracion", true);
                                                    $tduracion = get_term($id_duracion, "duracion");
                                                    $duracion = $tduracion ? $tduracion->name : '';
                                                    // Lugar
                                                    $id_lugar = get_post_meta($edicion, "ediciones_lugar", true);
                                                    $tlugar = get_term($id_lugar, "lugar");
                                                    $lugar = $tlugar ? $tlugar->name : '';
                                                    // URL
                                                    $url = get_the_permalink($edicion);
                                                    $url_externa = get_post_meta($post_id, "ediciones_urlexterna", true);
                                                }
                                            }
                                        
                                        $image_url = wp_get_attachment_image_src($image_id, 'img_instalaciones');
 
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

                                        // Compatibilidad: crear variables para el HTML existente
                                        $evento_fecha = $fechainicio . ' ' . $fechafin;
                                        $max_text = (strlen($content) > 180) ? substr($content, 0, 180) . '...' : $content;
                                        $img = $image_id;

                                        if($img){
                                            $img = esc_url( wp_get_attachment_url( $img ));
                                        } else{
                                            $img = esc_url( get_field('field_jornadas_imagenbanner', $post_id ));
                                        }

                                        $logo = '';
                                        if($logo_tic){
                                            $logo = '<img class="agenda-logo" src="' . get_stylesheet_directory_uri() . '/assets/img/tic.png' . '" alt="Tic Negocios" />';
                                        }
                                        if($logo_sostenibilidad){
                                            $logo = '<img class="agenda-logo" src="' . get_stylesheet_directory_uri() . '/assets/img/sostenibilidad.png' . '" alt="Sostenibilidad" />';
                                        }
                                        if($logo_internacional){
                                            $logo = '<img class="agenda-logo" src="' . get_stylesheet_directory_uri() . '/assets/img/internacional.png' . '" alt="Internacional" />';
                                        }                                

                                        $final_url = !empty($url_externa) ? $url_externa : $url;
                                ?>
                                    <li class="splide__slide gap-4 rounded-md shadow-sm bg-white border border-[#e0e0e0]">
                                        <a class="agenda-item grid md:grid-cols-6 p-0 rounded-md h-full" href="<?= esc_url( $final_url ); ?>" <?php if(!empty($url_externa)){ echo 'target="_blank" rel="nofollow noopener noreferrer"'; } ?>>
                                            <span class="col-span-1 md:col-span-2 h-full rounded-t-md md:rounded-t-none md:rounded-l-md p-3 m-0 min-h-40 bg-cover bg-center flex justify-center items-end" style="background-image: url('<?= $img ?>');">
                                                <?php if ( $logo ) : ?>
                                                    <figure class="agenda-figure bg-[rgba(0,0,0,0.2)] backdrop-blur-xs px-5 py-2 rounded-full h-fit w-fit items-end justify-center">
                                                        <?= $logo ?>
                                                    </figure>
                                                <?php endif; ?>
                                            </span>
                                            <div class="col-span-1 md:col-span-4 agenda-item-content w-full p-4 flex flex-col justify-between ">
                                                <header>
                                                    <h4 class="text-left"><?= esc_html( $title ); ?></h4>
                                                </header>
                                                <p class="agenda-item-body gap-1 text-left pb-2">
                                                    <?php if($hora): ?>
                                                        <span class="agenda-meta"><?= $hora ?> |</span>
                                                    <?php endif; ?>
                                                    <?php if($precio): ?>
                                                        <span class="agenda-meta"><?= $precio ?> | </span>
                                                    <?php endif; ?>
                                                    <?php if ( $max_text ): ?>
                                                        <?= esc_html( $max_text ); ?>
                                                    <?php endif; ?>
                                                </p>
                                                <footer class="evento-meta flex justify-between items-center mt-2 pt-3 border-t border-[#e0e0e0]">
                                                    <time class="evento-fecha"><?= esc_html( $evento_fecha ); ?></time>
                                                    <span class="evento-clase rounded-full py-1 px-2.5" style="color:<?= $color_label?>;background-color:<?= $bg_label ?>;font-size:0.85rem;"><?= esc_html( $get_term->name ); ?></span>      
                                                </footer>
                                            </div>
                                        </a>
                                         
                                    </li>
                                <?php 
                                    endwhile;
                                endif; ?>
                            </ul>
                        </div>
                        <?php if ( ! empty( $settings['agenda_button_text'] ) && ! empty( $settings['agenda_button_link']['url'] ) ) : 
                            $agenda_button_url = $settings['agenda_button_link']['url'];
                            $agenda_button_target = $settings['agenda_button_link']['is_external'] ? ' target="_blank"' : '';
                            $agenda_button_nofollow = $settings['agenda_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                        ?>
                            <ul class="splide__pagination agenda_noticias_pagination"></ul>
                            <div class="splide__arrows agenda_noticias_arrows"></div>
                            <div class="tab-button-container">
                                <a href="<?= esc_url( $agenda_button_url ); ?>" class="agenda-button" <?= $agenda_button_target . $agenda_button_nofollow; ?>>
                                    <?= esc_html( $settings['agenda_button_text'] ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="noticias-slide" class="splide tab-content" role="group" aria-label="Contenedor noticias">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php foreach ( $noticias->posts as $post ) : 
                                    $evento_fecha = get_post_meta( $post->ID, 'noticias_fecha', true );
                                    $evento_fecha_formateada = date_i18n( 'd M Y', strtotime( $evento_fecha ) );
                                    $image = get_post_thumbnail_id( $post->ID );
                                    
                                ?>
                                    <li class="splide__slide gap-4 rounded-md shadow-sm bg-white border border-[#e0e0e0]">
                                        <a class="noticias-item" href="<?= esc_url( get_permalink( $post->ID ) ); ?>">                                          
                                            <?= wp_get_attachment_image( $image, 'full', false, ['class' => 'noticias-image rounded-t-md object-cover object-top w-full !h-32 !rounded-t-md'] ); ?>
                                            <div class="p-5">
                                                <h4 class="noticias-title text-left"><?= esc_html( $post->post_title ); ?></h4>
                                                <span class="evento-fecha mt-2 pt-3 border-t border-[#e0e0e0] text-left"><?= esc_html( $evento_fecha_formateada ); ?></span>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            
                        </div>
                        <?php if ( ! empty( $settings['noticias_button_text'] ) && ! empty( $settings['noticias_button_link']['url'] ) ) : 
                            $noticias_button_url = $settings['noticias_button_link']['url'];
                            $noticias_button_target = $settings['noticias_button_link']['is_external'] ? ' target="_blank"' : '';
                            $noticias_button_nofollow = $settings['noticias_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                        ?>  <ul class="splide__pagination agenda_noticias_pagination"></ul>
                        <div class="splide__arrows agenda_noticias_arrows"></div>
                            <div class="tab-button-container">
                                <a href="<?= esc_url( $noticias_button_url ); ?>" class="noticias-button" <?= $noticias_button_target . $noticias_button_nofollow; ?>>
                                    <?= esc_html( $settings['noticias_button_text'] ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

            </div>

        </div>
    <?php
    }
}

// El registro del widget se maneja en functions-agenda_noticias.php
