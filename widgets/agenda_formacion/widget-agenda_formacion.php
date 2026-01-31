<?php
/**
 * Widget de Formación para Elementor - Cámara Valencia
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
 * Clase del Widget Formación
 */
class Camara_Agenda_Formacion_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'agenda_formacion_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Formación', 'agenda_formacion_widget' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-post-list';
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
        return [ 'agenda', 'noticias', 'calendario', 'eventos', 'agenda_formacion' ];
    }

    /**
     * Cargar estilos del widget
     */
    public function get_style_depends() {
        return [ 'agenda_formacion-splide' ];
    }
    /**
     * Cargar scripts del widget
     */
    public function get_script_depends() {
        return [ 'agenda_formacion-splide' ];
    }
    
    /**
     * Registrar estilos del widget
     */
    protected function register_styles() {
        wp_register_style( 'agenda_formacion-splide', get_stylesheet_directory_uri() . '/assets/css/splide.min.css', array(), '4.1.4' );
    }

    protected function register_scripts() {
        wp_register_script( 'agenda_formacion-splide', get_stylesheet_directory_uri() . '/assets/js/splide.min.js', array(), '4.1.4', true );
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
                        'label' => __( 'Título', 'agenda_formacion' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
            );



            $this->end_controls_section();

            // Agenda
            $this->start_controls_section(
                'agenda_section',
                [
                    'label' => __( 'Agenda', 'agenda_formacion' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
 
                // Botón Ver Más de la Agenda
                $this->add_control(
                    'agenda_button_text',
                    [
                        'label' => __( 'Texto del Botón de la Agenda', 'agenda_formacion' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Ver Más Eventos', 'agenda_formacion' ),
                        'placeholder' => __( 'Escribe el texto del botón...', 'agenda_formacion' ),
                        'label_block' => true,
                    ],
                );
                // Enlace del Botón de la Agenda
                $this->add_control(
                    'agenda_button_link',
                    [
                        'label' => __( 'Enlace', 'agenda_formacion' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => __( 'https://tu-enlace.com', 'agenda_formacion' ),
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
                'label' => __( 'Tipografía', 'agenda_formacion' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Tipografía del Título Principal', 'agenda_formacion' ),
                'selector' => '{{WRAPPER}} .agenda_formacion-widget .main-title',
            ]
        );

        $this->end_controls_section();

        // Sección de Colores Principales
        $this->start_controls_section(
            'main_colors_section',
            [
                'label' => __( 'Colores Principales', 'agenda_formacion' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color del Título Principal', 'agenda_formacion' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agenda_formacion-widget .main-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Color del Subtítulo', 'agenda_formacion' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .agenda_formacion-widget .main-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        global $wpdb;
        
        // Asegurar que los estilos y scripts estén cargados
        wp_enqueue_style( 'agenda_formacion-splide' );
        wp_enqueue_script( 'agenda_formacion-splide' );
    

        // Verificar qué valores existen en la BD
        $check_meta = $wpdb->get_results("
            SELECT p.ID, p.post_title, pm.meta_key, pm.meta_value, p.post_type
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key LIKE '%agendaformacion%'
            AND p.post_type IN ('cursos', 'jornadas')
            AND p.post_status = 'publish'
            LIMIT 10
        ");
        error_log('Verificación de campo agendaformacion: ' . print_r($check_meta, true));
        
        // Query para obtener las jornadas que tienen el campo agendaformacion activado
        // Ordenadas por fecha de inicio (campo ACF)
        // Solo muestra eventos cuya fecha de fin no haya pasado
        $fecha_actual = date('Y-m-d');
        
        $agenda = new WP_Query( array(
            'post_type' => array('cursos','jornadas'),
            'posts_per_page' => 4,
            'meta_key' => 'jornadas_fechainicio',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'jornadas_agendaformacion',
                        'value' => 'on',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => 'jornadas_agendaformacion',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'jornadas_agendaformacion',
                        'value' => serialize(array('on')),
                        'compare' => '='
                    )
                ),
                array(
                    'key' => 'jornadas_fechainicio',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'jornadas_fechafin',
                    'value' => $fecha_actual,
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            )
        ));
        $settings = $this->get_settings_for_display();
    ?>
        <div class="agenda_formacion-widget areas-widget w-full ">
            
            <div class="agenda_formacion-header text-center mb-5">
  
                <div class="agenda_formacion-content">

                    <div id="agenda-slide" class="tab-content active" role="group" aria-label="Contenedor Agenda">

                            <ul class="agenda-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                <?php 
                                if ( $agenda->have_posts() ) :
                                    while ( $agenda->have_posts() ) : $agenda->the_post();
                                        $post_id = get_the_ID();
                                        $post_type = get_post_type();
                                        $meta = get_post_meta($post_id);
                                        
                                        // Obtener área temática
                                        $areas = wp_get_post_terms($post_id, 'area');
                                        $area_nombre = '';
                                        if (!is_wp_error($areas) && !empty($areas)) {
                                            $area_nombre = $areas[0]->name;
                                        }
                                        
                                        // Datos básicos que se muestran en la card
                                        $title = get_the_title();
                                        $hora = get_field('field_jornadas_horainicio', $post_id );
                                        $precio = get_field('field_jornadas_preciodescrip', $post_id ) ? get_field('field_jornadas_preciodescrip', $post_id ) : 'Gratis';
                                        $content = wp_strip_all_tags( get_field('field_jornadas_objetivos', $post_id ) );
                                        $max_text = (strlen($content) > 64) ? substr($content, 0, 68) . '...' : $content;
                                        
                                        // Tipo de jornada y colores
                                        $tipo_jornada = get_field('field_jornadas_tipojornada', $post_id );
                                        $get_term = get_term( $tipo_jornada );
                                        
                                        // Fechas
                                        $fechainicio = date("d", strtotime($meta[$post_type."_fechainicio"][0]));
                                        $fechafin = date("M", strtotime($meta[$post_type."_fechainicio"][0]));
                                        
                                        // URLs
                                        $url = get_the_permalink();
                                        $url_externa = $meta[$post_type.'_urleventomanual'][0] ?? '';
                                        
                                        // Si es un curso, obtener datos de la edición activa
                                        if ($post_type == 'cursos') {
                                            $edicion = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id='$post_id' AND meta_key='cursos_edicionactiva' ");
                                            if ($edicion) {
                                                $meta_edicion = get_post_meta($edicion);
                                                $fechainicio = date("d", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                                                $fechafin = date("M", strtotime($meta_edicion["ediciones_fechainicio"][0]));
                                                $url = get_the_permalink($edicion);
                                                $url_externa = get_post_meta($post_id, "ediciones_urlexterna", true);
                                            }
                                        }
                                        
                                        // Imagen
                                        $image_id = get_post_thumbnail_id($post_id);
                                        if($image_id){
                                            $img = esc_url( wp_get_attachment_url( $image_id ));
                                        } else{
                                            $img = esc_url( get_field('field_jornadas_imagenbanner', $post_id ));
                                        }
                                        
                                        // Colores según tipo de evento
                                        $color_label = '#404247';
                                        $bg_label = '#F4F5FC';
                                        
                                        if($get_term && $get_term->name == 'Jornada'){
                                            $color_label = '#AF9343';
                                            $bg_label = '#F8EABF';
                                        } elseif($get_term && ($get_term->name == 'Webinar' || $get_term->name == 'Curso')){
                                            $color_label = '#2EA5DA';
                                            $bg_label = '#D6ECF5';
                                        } elseif($get_term && $get_term->name == 'Charla'){
                                            $color_label = '#D67F2A';
                                            $bg_label = '#FAE3D4';
                                        } elseif($get_term && $get_term->name == 'Taller'){
                                            $color_label = '#046244';
                                            $bg_label = '#C9EDE1';
                                        } elseif($get_term && $get_term->name == 'Encuentro Empresarial'){
                                            $color_label = '#E28996';
                                            $bg_label = '#720F1C';
                                        }
                                        
                                        
                                        // Variables finales para el HTML
                                        $evento_fecha = $fechainicio . ' ' . $fechafin;
                                        $final_url = !empty($url_externa) ? $url_externa : $url;
                                ?>
                                    <li class="rounded-md shadow-sm !bg-white border border-[#e0e0e0]">
                                        <a class="agenda-item flex flex-col p-0 rounded-md h-full" href="<?= esc_url( $final_url ); ?>" <?php if(!empty($url_externa)){ echo 'target="_blank" rel="nofollow noopener noreferrer"'; } ?>>
                                            <div class="rounded-t-md p-3 m-0 h-48 bg-cover bg-center flex items-end" style="background-image: url('<?= $img ?>');">
                                                <?php if ( $area_nombre ) : ?>
                                                    <span class="agenda-area-badge bg-[rgba(0,0,0,0.5)] backdrop-blur-sm px-4 py-1.5 rounded-full text-white text-xs font-semibold uppercase tracking-wide">
                                                        <?= esc_html($area_nombre) ?>
                                                    </span>
                                                <?php endif; ?>
                                                </div>
                                            <div class="agenda-item-content w-full p-4 flex flex-col justify-between flex-grow">
                                                <header>
                                                    <h4 class="text-left"><?= esc_html( $title ); ?></h4>
                                                </header>
                                                <p class="agenda-item-body gap-1 text-left pb-2">
                                                    <?php if($hora): ?>
                                                        <span class="agenda-meta font-semibold"><?= $hora ?> |</span>
                                                    <?php endif; ?>
                                                    <?php if($precio): ?>
                                                        <span class="agenda-meta font-semibold"><?= $precio ?> | </span>
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
                        <?php if ( ! empty( $settings['agenda_button_text'] ) && ! empty( $settings['agenda_button_link']['url'] ) ) : 
                            $agenda_button_url = $settings['agenda_button_link']['url'];
                            $agenda_button_target = $settings['agenda_button_link']['is_external'] ? ' target="_blank"' : '';
                            $agenda_button_nofollow = $settings['agenda_button_link']['nofollow'] ? ' rel="nofollow"' : '';
                        ?>
                            <div class="tab-button-container mt-6 text-center">
                                <a href="<?= esc_url( $agenda_button_url ); ?>" class="agenda-button" <?= $agenda_button_target . $agenda_button_nofollow; ?>>
                                    <?= esc_html( $settings['agenda_button_text'] ); ?>
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

// El registro del widget se maneja en functions-agenda_formacion.php
