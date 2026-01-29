<?php
/**
 * Widget de Areas para Elementor - Cámara Valencia
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
 * Clase del Widget Areas
 */
class Camara_Areas_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'areas';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Áreas', 'areas' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-apps';
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
        return [ 'external links', 'images', 'areas' ];
    }
    /**
     * Cargar scripts del widget
     */
    public function get_script_depends() {
        return [ 'areas-script' ];
    }
    /**
     * Registrar scripts del widget
     */
    protected function register_scripts() {
        wp_register_script( 'areas-script', get_stylesheet_directory_uri() . '/assets/js/areas.js', array(),'1.0.1' );
    }
    /**
     * Constructor del widget
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->register_scripts();
    }

    /**
     * Configuración de controles
     */
    protected function _register_controls() {
        
        // Sección de Contenido Principal
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Contenido Principal', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'main_title',
            [
                'label' => __( 'Título Principal', 'areas' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Nuestras Áreas', 'areas' ),
                'placeholder' => __( 'Escribe el título principal...', 'areas' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'main_subtitle',
            [
                'label' => __( 'Subtítulo', 'areas' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Descubre todas las áreas de actividad en las que trabajamos', 'areas' ),
                'placeholder' => __( 'Escribe el subtítulo...', 'areas' ),
                'rows' => 3,
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Sección de Áreas (Repeater)
        $this->start_controls_section(
            'areas_section',
            [
                'label' => __( 'Áreas de Actividad', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'area_title',
            [
                'label' => __( 'Título del Área', 'areas' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Título del Área', 'areas' ),
                'placeholder' => __( 'Escribe el título del área...', 'areas' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'area_link',
            [
                'label' => __( 'Enlace', 'areas' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://tu-enlace.com', 'areas' ),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'area_text',
            [
                'label' => __( 'Texto Descriptivo', 'areas' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Descripción del área de actividad...', 'areas' ),
                'placeholder' => __( 'Escribe la descripción...', 'areas' ),
                'rows' => 4,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'area_logo',
            [
                'label' => __( 'Logo/Icono', 'areas' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'media_types' => ['image'],
            ]
        );

        $repeater->add_control(
            'area_background_image',
            [
                'label' => __( 'Imagen de Fondo', 'areas' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'media_types' => ['image'],
            ]
        );

        $repeater->add_control(
            'area_color',
            [
                'label' => __( 'Color Principal', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
            ]
        );

        $this->add_control(
            'areas_list',
            [
                'label' => __( 'Lista de Áreas', 'areas' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'area_title' => __( 'Área 1', 'areas' ),
                        'area_text' => __( 'Descripción del área 1', 'areas' ),
                        'area_color' => '#007cba',
                    ],
                    [
                        'area_title' => __( 'Área 2', 'areas' ),
                        'area_text' => __( 'Descripción del área 2', 'areas' ),
                        'area_color' => '#28a745',
                    ],
                    [
                        'area_title' => __( 'Área 3', 'areas' ),
                        'area_text' => __( 'Descripción del área 3', 'areas' ),
                        'area_color' => '#dc3545',
                    ],
                ],
                'title_field' => '{{{ area_title }}}',
            ]
        );

        $this->end_controls_section();

        // Sección de Tipografía
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __( 'Tipografía', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Tipografía del Título Principal', 'areas' ),
                'selector' => '{{WRAPPER}} .areas-widget .main-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => __( 'Tipografía del Subtítulo', 'areas' ),
                'selector' => '{{WRAPPER}} .areas-widget .main-subtitle',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'area_title_typography',
                'label' => __( 'Tipografía de Títulos de Área', 'areas' ),
                'selector' => '{{WRAPPER}} .areas-widget .area-item .area-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'area_text_typography',
                'label' => __( 'Tipografía del Texto de Área', 'areas' ),
                'selector' => '{{WRAPPER}} .areas-widget .area-item .area-text',
            ]
        );

        $this->end_controls_section();

        // Sección de Colores Principales
        $this->start_controls_section(
            'main_colors_section',
            [
                'label' => __( 'Colores Principales', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color del Título Principal', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .main-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __( 'Color del Subtítulo', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .main-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Sección de Colores de Áreas
        $this->start_controls_section(
            'areas_colors_section',
            [
                'label' => __( 'Colores de Áreas', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'areas_tabs' );

        $this->start_controls_tab(
            'areas_normal_tab',
            [
                'label' => __( 'Normal', 'areas' ),
            ]
        );

        $this->add_control(
            'area_title_color',
            [
                'label' => __( 'Color del Título del Área', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item .area-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'area_text_color',
            [
                'label' => __( 'Color del Texto del Área', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item .area-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'area_bg_color',
            [
                'label' => __( 'Color de Fondo del Área', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'areas_hover_tab',
            [
                'label' => __( 'Hover', 'areas' ),
            ]
        );

        $this->add_control(
            'area_title_color_hover',
            [
                'label' => __( 'Color del Título al Hover', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item:hover .area-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'area_text_color_hover',
            [
                'label' => __( 'Color del Texto al Hover', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item:hover .area-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'area_bg_color_hover',
            [
                'label' => __( 'Color de Fondo al Hover', 'areas' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Sección de Espaciado y Diseño
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __( 'Diseño y Espaciado', 'areas' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Número de Columnas', 'areas' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .areas-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => __( 'Espaciado entre Áreas', 'areas' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .areas-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'area_padding',
            [
                'label' => __( 'Padding de Área', 'areas' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'default' => [
                    'top' => 30,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item .area-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __( 'Margen del Título Principal', 'areas' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .main-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'area_skewed',
            [
                'label' => __( 'Inclinación de las Áreas', 'areas' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 18,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item' => 'transform: skew(-{{SIZE}}{{UNIT}}); position: relative; overflow: hidden;',
                    '{{WRAPPER}} .areas-widget .area-item .area-inner' => 'transform: skew({{SIZE}}{{UNIT}}); position: relative; z-index: 2;',
                    '{{WRAPPER}} .areas-widget .area-item::before' => 'content: ""; position: absolute; top: 0; left: -12.5%; width: 125%; height: 100%; background-image: var(--area-bg-image); background-size: cover; background-position: center; transform: skew({{SIZE}}{{UNIT}}); background-repeat: no-repeat; transform-origin: center; z-index: 1;',
                ],
            ]
        );
        $this->add_responsive_control(
            'area_min_height',
            [
                'label' => __( 'Altura mínima:', 'areas' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'area_border_radius',
            [
                'label' => __( 'Radio del Borde', 'areas' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .areas-widget .area-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'area_box_shadow',
                'label' => __( 'Sombra del Área', 'areas' ),
                'selector' => '{{WRAPPER}} .areas-widget .area-item',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        
        // Asegurar que los estilos estén cargados
        wp_enqueue_script( 'areas-script' );

        $settings = $this->get_settings_for_display();

        ?>
        <div class="areas-widget">
            
            <?php if ( ! empty( $settings['main_title'] ) || ! empty( $settings['main_subtitle'] ) ) : ?>
            <div class="areas-header">
                <?php if ( ! empty( $settings['main_title'] ) ) : ?>
                    <div class="areas-title-wrapper">
                        <h2 class="main-title"><?php echo esc_html( $settings['main_title'] ); ?></h2>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $settings['main_subtitle'] ) ) : ?>
                    <p class="main-subtitle"><?php echo esc_html( $settings['main_subtitle'] ); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $settings['areas_list'] ) ) : ?>
            <div class="areas-grid">
                <?php foreach ( $settings['areas_list'] as $index => $area ) : 
                    $area_key = $this->get_repeater_setting_key( 'area_title', 'areas_list', $index );
                    $this->add_render_attribute( $area_key, 'class', 'area-item' );
                    
                    // Estilos inline para el color personalizado
                    $area_styles = [];
                    if ( ! empty( $area['area_color'] ) ) {
                        $area_styles[] = '--area-accent-color: ' . $area['area_color'];
                    }
                    
                    if ( ! empty( $area['area_background_image']['url'] ) ) {
                        $area_styles[] = '--area-bg-image: url(' . esc_url( $area['area_background_image']['url'] ) . ')';
                    }
                    
                    if ( ! empty( $area_styles ) ) {
                        $this->add_render_attribute( $area_key, 'style', implode( '; ', $area_styles ) );
                    }
                ?>
                    <div <?php echo $this->get_render_attribute_string( $area_key ); ?>>
                        <?php if ( ! empty( $area['area_link']['url'] ) ) : 
                            $link_key = $this->get_repeater_setting_key( 'area_link', 'areas_list', $index );
                            $this->add_link_attributes( $link_key, $area['area_link'] );
                        ?>
                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?> class="area-link">
                            <div class="area-inner">

                                    <?php if ( ! empty( $area['area_logo']['url'] ) ) : ?>
                                        <figure class="agenda-figure bg-[rgba(0,0,0,0.2)] backdrop-blur-xs px-5 py-2 rounded-full h-fit w-fit items-end justify-center area-logo absolute top-3 mx-auto p-3">
                                            <img src="<?php echo esc_url( $area['area_logo']['url'] ); ?>"  alt="<?php echo esc_attr( $area['area_title'] ); ?>">
                                        </figure>
                                    <?php endif; ?>


                                <div class="area-content">

                                    <?php if ( ! empty( $area['area_title'] ) ) : ?>
                                        
                                        <h3 class="area-title"><?php echo esc_html( $area['area_title'] ); ?></h3>
                                        
                                    <?php else : ?>

                                        <h3 class="area-title"><?php echo esc_html( $area['area_title'] ); ?></h3>

                                    <?php endif; ?>
                                    
                                    <?php endif; ?>
                                    <?php if ( ! empty( $area['area_text'] ) ) : ?>
                                        <p class="area-text h-0"><?php echo wp_kses_post( $area['area_text'] ); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php //color de fondo ?>
                                <?php if ( ! empty( $area['area_color'] ) ) : ?>
                                    <div class="area-background" style="background:linear-gradient(180deg, transparent 70%, <?php echo esc_attr( $area['area_color'] ); ?> 100%);"></div>
                                <?php endif; ?>


                                <?php if ( ! empty( $area['area_background_image']['url'] ) ) : ?>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
                <div class="areas-notice">
                    <p><?php echo esc_html__( 'No hay áreas configuradas. Agrega áreas desde el panel de Elementor.', 'areas' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

// El registro del widget se maneja en functions-areas.php
