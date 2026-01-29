<?php
/**
 * Widget Video Hero para Elementor - Cámara Valencia
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
 * Clase del Widget   Video Hero
 */
class Camara_video_hero_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'video_hero';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Vídeo Hero', 'video_hero' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-slider-video';
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
        return [ 'title', 'media', 'video', 'image', 'titulo' ];
    }

    /**
     * Registrar scripts
     */
    public function get_script_depends() {
        return [ 'video-hero-scroll-effect' ];
    }

    /**
     * Registrar estilos
     */
    public function get_style_depends() {
        return [ 'video-hero-scroll-effect' ];
    }

    /**
     * Configuración de controles
     */
    protected function _register_controls() {
        
        // ======================
        // Sección de Contenido
        // ======================
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Contenido', 'video_hero' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control de Título
        $this->add_control(
            'title',
            [
                'label' => __( 'Título', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Mi Título', 'video_hero' ),
                'placeholder' => __( 'Escribe tu título...', 'video_hero' ),
                'label_block' => true,
            ]
        );

        // Control de Media (Imagen o Video)
        $this->add_control(
            'media',
            [
                'label' => __( 'Imagen o Video', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'media_types' => ['image', 'video'],
            ]
        );

        // Control de Autoreproducción
        $this->add_control(
            'video_autoplay',
            [
                'label' => __( 'Autoreproducción', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Sí', 'video_hero' ),
                'label_off' => __( 'No', 'video_hero' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        // Control de Mostrar Controles
        $this->add_control(
            'video_controls',
            [
                'label' => __( 'Mostrar Controles', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Sí', 'video_hero' ),
                'label_off' => __( 'No', 'video_hero' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Control de Bucle de Reproducción
        $this->add_control(
            'video_loop',
            [
                'label' => __( 'Bucle de Reproducción', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Sí', 'video_hero' ),
                'label_off' => __( 'No', 'video_hero' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        // Separador
        $this->add_control(
            'scroll_effect_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Control de Efecto de Scroll
        $this->add_control(
            'enable_scroll_effect',
            [
                'label' => __( 'Activar Efecto de Scroll', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Sí', 'video_hero' ),
                'label_off' => __( 'No', 'video_hero' ),
                'return_value' => 'yes',
                'default' => '',
                'description' => __( 'El título aparecerá como máscara del video al hacer scroll', 'video_hero' ),
            ]
        );

        // Control de Grosor de Línea
        $this->add_control(
            'line_height',
            [
                'label' => __( 'Grosor de Línea', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'condition' => [
                    'enable_scroll_effect' => 'yes',
                ],
            ]
        );

        // Control de Color de Línea
        $this->add_control(
            'line_color',
            [
                'label' => __( 'Color de Línea', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => [
                    'enable_scroll_effect' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ======================
        // Sección de Estilo del Título
        // ======================
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __( 'Estilo del Título', 'video_hero' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Tipografía del Título
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Tipografía', 'video_hero' ),
                'selector' => '{{WRAPPER}} .title-media-heading',
                'fields_options' => [
                    'typography' => [
                        'default' => 'yes',
                    ],
                    'font_size' => [
                        'default' => [
                            'size' => 32,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '700',
                    ],
                ],
            ]
        );

        // Color del Título
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .title-media-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Alineación del Título
        $this->add_responsive_control(
            'title_align',
            [
                'label' => __( 'Alineación', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Izquierda', 'video_hero' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Centro', 'video_hero' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Derecha', 'video_hero' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .title-media-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Espaciado del Título
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margen', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .title-media-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ======================
        // Sección de Estilo del Media
        // ======================
        $this->start_controls_section(
            'media_style_section',
            [
                'label' => __( 'Estilo del Media', 'video_hero' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Ancho del Media
        $this->add_responsive_control(
            'media_width',
            [
                'label' => __( 'Ancho', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .title-media-content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Altura del Media
        $this->add_responsive_control(
            'media_height',
            [
                'label' => __( 'Altura', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .title-media-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'media_border_radius',
            [
                'label' => __( 'Radio de Borde', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .title-media-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .title-media-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .title-media-content video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margen del Media
        $this->add_responsive_control(
            'media_margin',
            [
                'label' => __( 'Margen', 'video_hero' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .title-media-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $enable_scroll = 'yes' === $settings['enable_scroll_effect'];
        
        ?>
        <div class="title-media-widget <?php echo $enable_scroll ? 'has-scroll-effect' : ''; ?>">
            <?php if ( $enable_scroll && ! empty( $settings['media']['url'] ) ) : ?>
                <?php
                $media_url = $settings['media']['url'];
                $media_type = wp_check_filetype( $media_url )['type'];
                $is_video = strpos( $media_type, 'video' ) !== false;
                
                if ( $is_video ) :
                    // Preparar atributos del video para el efecto de scroll
                    $video_attrs = ['muted', 'playsinline']; // Siempre muted para el efecto
                    
                    if ( 'yes' === $settings['video_loop'] ) {
                        $video_attrs[] = 'loop';
                    }
                    
                    $video_attrs_string = implode( ' ', $video_attrs );
                    
                    // Preparar datos para el JavaScript
                    $line_height = isset( $settings['line_height']['size'] ) ? $settings['line_height']['size'] : 3;
                    $line_color = isset( $settings['line_color'] ) ? $settings['line_color'] : '#000000';
                    ?>
                    <div class="title-media-scroll-container" 
                         data-line-height="<?php echo esc_attr( $line_height ); ?>" 
                         data-line-color="<?php echo esc_attr( $line_color ); ?>">
                        <video class="title-media-video-background" <?php echo $video_attrs_string; ?> crossorigin="anonymous">
                            <source src="<?php echo esc_url( $media_url ); ?>" type="<?php echo esc_attr( $media_type ); ?>">
                        </video>
                        <?php if ( ! empty( $settings['title'] ) ) : ?>
                            <h1 class="title-media-heading">
                                <?php echo esc_html( $settings['title'] ); ?>
                                <div class="title-media-line"></div>
                            </h1>
                        <?php endif; ?>
                    </div>
                    <?php
                endif;
                ?>
            <?php else : ?>
                <?php // Modo normal sin efecto de scroll ?>
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h1 class="title-media-heading"><?php echo esc_html( $settings['title'] ); ?></h1>
                <?php endif; ?>

                <?php if ( ! empty( $settings['media']['url'] ) ) : ?>
                    <div class="title-media-content">
                        <?php
                        $media_url = $settings['media']['url'];
                        $media_type = wp_check_filetype( $media_url )['type'];
                        
                        // Verificar si es video
                        if ( strpos( $media_type, 'video' ) !== false ) :
                            // Preparar atributos del video
                            $video_attrs = [];
                            
                            if ( 'yes' === $settings['video_autoplay'] ) {
                                $video_attrs[] = 'autoplay';
                                $video_attrs[] = 'muted'; // Necesario para autoplay en la mayoría de navegadores
                            }
                            
                            if ( 'yes' === $settings['video_controls'] ) {
                                $video_attrs[] = 'controls';
                            }
                            
                            if ( 'yes' === $settings['video_loop'] ) {
                                $video_attrs[] = 'loop';
                            }
                            
                            $video_attrs_string = implode( ' ', $video_attrs );
                            ?>
                            <video class="title-media-video" <?php echo $video_attrs_string; ?>>
                                <source src="<?php echo esc_url( $media_url ); ?>" type="<?php echo esc_attr( $media_type ); ?>">
                                <?php _e( 'Tu navegador no soporta el elemento de video.', 'video_hero' ); ?>
                            </video>
                            <?php
                        else :
                            // Es una imagen
                            ?>
                            <img class="title-media-image" src="<?php echo esc_url( $media_url ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Renderizar el widget en modo editor (opcional)
     */
    protected function content_template() {
        ?>
        <#
        var mediaUrl = settings.media.url;
        var mediaType = mediaUrl.split('.').pop().toLowerCase();
        var isVideo = ['mp4', 'webm', 'ogg', 'mov'].includes(mediaType);
        var enableScroll = 'yes' === settings.enable_scroll_effect;
        
        // Preparar atributos del video
        var videoAttrs = [];
        if ( 'yes' === settings.video_autoplay ) {
            videoAttrs.push('autoplay');
            videoAttrs.push('muted'); // Necesario para autoplay
        }
        if ( 'yes' === settings.video_controls ) {
            videoAttrs.push('controls');
        }
        if ( 'yes' === settings.video_loop ) {
            videoAttrs.push('loop');
        }
        var videoAttrsString = videoAttrs.join(' ');
        
        // Atributos para efecto de scroll
        var scrollVideoAttrs = ['muted', 'playsinline'];
        if ( 'yes' === settings.video_loop ) {
            scrollVideoAttrs.push('loop');
        }
        var scrollVideoAttrsString = scrollVideoAttrs.join(' ');
        
        // Datos para la línea
        var lineHeight = settings.line_height && settings.line_height.size ? settings.line_height.size : 3;
        var lineColor = settings.line_color ? settings.line_color : '#000000';
        #>
        
        <div class="title-media-widget <# if ( enableScroll ) { #>has-scroll-effect<# } #>">
            <# if ( enableScroll && settings.media.url && isVideo ) { #>
                <div class="title-media-scroll-container" data-line-height="{{ lineHeight }}" data-line-color="{{ lineColor }}">
                    <video class="title-media-video-background" {{{ scrollVideoAttrsString }}} crossorigin="anonymous">
                        <source src="{{ settings.media.url }}">
                    </video>
                    <# if ( settings.title ) { #>
                        <h1 class="title-media-heading">
                            {{{ settings.title }}}
                            <div class="title-media-line"></div>
                        </h1>
                    <# } #>
                </div>
            <# } else { #>
                <# if ( settings.title ) { #>
                    <h1 class="title-media-heading">{{{ settings.title }}}</h1>
                <# } #>

                <# if ( settings.media.url ) { #>
                    <div class="title-media-content">
                        <# if ( isVideo ) { #>
                            <video class="title-media-video" {{{ videoAttrsString }}}>
                                <source src="{{ settings.media.url }}">
                            </video>
                        <# } else { #>
                            <img class="title-media-image" src="{{ settings.media.url }}" alt="{{ settings.title }}">
                        <# } #>
                    </div>
                <# } #>
            <# } #>
        </div>
        <?php
    }
}
