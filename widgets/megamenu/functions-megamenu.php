<?php 

/** menu **/

    // Registra Opción de Mega Menú
        function menu() {
            $locations = array(
                'megamenu' => __('Mega Menu', 'amanepal'),
                'mobile'   => __( 'Mobile Menu', 'amanepal' ),
                'footer'   => __( 'Footer Menu', 'amanepal' ),
                'social'   => __( 'Social Menu', 'amanepal' ),
            );
            register_nav_menus( $locations );
        }
        add_action( 'init', 'menu' );
    
		// Personalización del Wawlker para el Menú
            class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
                // Inicializar la propiedad para controlar la inserción del SVG
                private $svg_inserted = false;
                
                // Submenú personalizado
                function start_lvl( &$output, $depth = 0, $args = array() ) {
                    $indent = str_repeat( "\t", $depth );

                    $output .= "\n$indent<div class=\"megawrap\">\n<ul class=\"sub-menu flex flex-col gap-4 text-balance\">\n";
                }
            
                function end_lvl( &$output, $depth = 0, $args = array() ) {
                    $indent = str_repeat( "\t", $depth );
                    $output .= "$indent</ul>\n</div>\n";
                }
      
                // Elemento de menú personalizado con icono ACF
                function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
                    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
                    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
                    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

                        // === INSERTAR SVG SOLO UNA VEZ al inicio del primer nivel ===
                            if ( $depth === 0 && ! $this->svg_inserted ) {
                                $output .= '
                                <li id="mega-responsive" aria-hidden="true">
                                    <div id="mega-nav-icon">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </li>';
                                $this->svg_inserted = true;
                            }
                        // === FIN SVG ===

                    $output .= '<li' . $class_names . '>';
            
                    // Obtener el campo menu_icon de ACF para este item
                    $icon_id = get_field('mega_icon', $item);
                    $icon_html = '';
                    if ($icon_id) {
                        $icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
                        if ($icon_url) {
                            $icon_html = '<figure><img src="' . esc_url($icon_url) . '" alt=""/></figure>';
                        }
                    }

                        //Obtener el campo del color
                            $bg_color = get_field('mega_bg_color', $item);
                        if($bg_color){
                            $output .= '<style>
                                            .mega-bg-color > .megawrap > ul.sub-menu > li:last-child  { 
                                                background-color:' . esc_attr($bg_color) . '; 
                                                color:white; 
                                                border-radius: 0 1.9rem 1.9rem 0;
                                            }
                                        </style>';
                        }
            
                    $atts = array();
                    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
                    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
                    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
                    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
            
                    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
            
                    $attributes = '';
                    foreach ( $atts as $attr => $value ) {
                        if ( ! empty( $value ) ) {
                            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                            $attributes .= ' ' . $attr . '="' . $value . '"';
                        }
                    }
            
                    $title = apply_filters( 'the_title', $item->title, $item->ID );
            
                    // Obtener la imagen de fondo y el texto para este ítem
                    $img_bg = get_field('bg_column', $item);
                    $mega_text = get_field('mega_text', $item);
                    
                    $item_output = $args->before;
                    $item_output .= '<a'. $attributes .'>';
                    
                    // Si tiene imagen de fondo, crear estructura especial
                    if ($img_bg) {
                        $bg_url = wp_get_attachment_image_url($img_bg, 'full');
                        if ($bg_url) {
                            // Estructura original: div con clase mega-bg-image y el texto dentro
                            $item_output .= '<div class="mega-bg-image" style="background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.5)),url(' . esc_url($bg_url) . ');">';
                            $item_output .= '<p>' . esc_html($mega_text ? $mega_text : $title) . '</p>';
                            $item_output .= '</div>';
                        } else {
                            // Si no hay URL de imagen, mostrar como normal
                            $item_output .= $icon_html;
                            $item_output .= $args->link_before . $title . $args->link_after;
                        }
                    } else {
                        // Comportamiento normal: icono + título
                        $item_output .= $icon_html;
                        $item_output .= $args->link_before . $title . $args->link_after;
                    }
                    
                    $item_output .= '</a>';
                    $item_output .= $args->after;
                    if(in_array('menu-item-has-children', $classes)){
                        $item_output .= '<button class="accordion-toggle-btn" aria-label="Expandir menú">
                            <div class="arrow-css">
                                <span></span>
                                <span></span>
                            </div>
                        </button>';
                    }
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                }

                function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
                    // Ya no generamos la imagen aquí, se genera dentro del enlace en start_el
                    $output .= '</li>';
                }
            }

/** Elementor **/

    // Variable global para evitar registros duplicados
        global $camara_elementor_category_registered;

        /**
         * Registrar categoría de Cámara Valencia
         */
        if ( ! function_exists( 'register_camara_megamenu_elementor_category' ) ) {
            function register_camara_megamenu_elementor_category( $elements_manager ) {
                global $camara_elementor_category_registered;
                
                // Si ya se registró, salir
                if ( $camara_elementor_category_registered ) {
                    return;
                }
                
                // Verificar si la categoría ya existe
                $existing_categories = $elements_manager->get_categories();
                
                // Si ya existe la categoría 'camara', marcar como registrada y salir
                if ( isset( $existing_categories['camara'] ) ) {
                    $camara_elementor_category_registered = 'camara';
                    return;
                }
                
                // Si ya existe 'camara-megamenu', marcar como registrada y salir
                if ( isset( $existing_categories['camara-megamenu'] ) ) {
                    $camara_elementor_category_registered = 'camara-megamenu';
                    return;
                }
                
                // Si no existe ninguna, registrar la nuestra
                $elements_manager->add_category(
                    'camara-megamenu',
                    [
                        'title' => __( 'Cámara Valencia - Navegación', 'megamenu' ),
                        'icon' => 'fa fa-bars',
                    ]
                );
                
                // Marcar como registrada
                $camara_elementor_category_registered = 'camara-megamenu';
            }
            add_action( 'elementor/elements/categories_registered', 'register_camara_megamenu_elementor_category', 20 );
        }

        /**
         * Registrar el widget en Elementor
         */
        function register_camara_megamenu_elementor_widgets( $widgets_manager ) {
            
            // Verificar que Elementor esté completamente cargado
            if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
                return;
            }
            
            // Cargar el archivo del widget si no se ha cargado
            $widget_file = dirname(__FILE__) . '/widget-megamenu.php';
            if ( file_exists( $widget_file ) ) {
                require_once( $widget_file );
            }
            
            // Verificar que la clase existe antes de registrarla
            if ( class_exists( 'Camara_Megamenu_Widget' ) && method_exists( $widgets_manager, 'register' ) ) {
                try {
                    $widgets_manager->register( new \Camara_Megamenu_Widget() );
                    error_log( 'Widget Camara_Megamenu_Widget registrado correctamente' );
                } catch ( Exception $e ) {
                    error_log( 'Error registrando widget Camara_Megamenu_Widget: ' . $e->getMessage() );
                }
            } else {
                error_log( 'No se pudo registrar widget: clase no existe o widgets_manager no válido' );
            }
        }
        add_action( 'elementor/widgets/register', 'register_camara_megamenu_elementor_widgets', 20 );