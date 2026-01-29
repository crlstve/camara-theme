<?php
    // ACF para el Slider de la home
    // https://www.advancedcustomfields.com/resources/register-fields-via-php/
    add_action('acf/init', 'my_acf_init_slider_home');
    function my_acf_init_slider_home() {

        acf_add_local_field_group(array(
            'key' => 'slider_home',
            'title' => 'Slider Home',
            'fields' => array(
                array(
                    'key' => 'field_slider_title_logo',
                    'label' => 'Logo Título',
                    'name' => 'slider_title_logo',
                    'type' => 'image',
                    'return_format' => 'id',
                    'preview_size' => 'full',
                    'library' => 'all',
                    'required' => 0,
                ),
                // Imagen
                array(
                    'key' => 'field_slider_imagen',
                    'label' => 'Imagen',
                    'name' => 'slider_imagen',
                    'type' => 'image',
                    'required' => 1,
                    'return_format' => 'url',
                    'preview_size' => 'full',
                    'library' => 'all',
                ),
                
                // Grupo Overlay
                array(
                    'key' => 'field_slider_overlay',
                    'label' => 'Overlay',
                    'name' => 'slider_overlay',
                    'type' => 'group',
                    'required' => 0,
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_slider_overlay_color1',
                            'label' => 'Color 1',
                            'name' => 'color1',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => 'rgba(22,22,22,0.42)',
                            'enable_opacity' => 1,
                            'wrapper' => array('width' => '50'),
                        ),
                        array(
                            'key' => 'field_slider_overlay_color2',
                            'label' => 'Color 2',
                            'name' => 'color2',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => 'rgba(22,22,22,0.42)',
                            'enable_opacity' => 1,
                            'wrapper' => array('width' => '50'),
                        ),
                        array(
                            'key' => 'field_slider_overlay_grados',
                            'label' => 'Grados (0-360)',
                            'name' => 'grados',
                            'type' => 'number',
                            'required' => 0,
                            'default_value' => 90,
                            'min' => 0,
                            'max' => 360,
                            'step' => 1,
                            'wrapper' => array('width' => '33.3'),
                        ),
                        array(
                            'key' => 'field_slider_overlay_posicion_color1',
                            'label' => 'Posición Color 1 (%)',
                            'name' => 'posicion_color1',
                            'type' => 'number',
                            'required' => 0,
                            'default_value' => 0,
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'wrapper' => array('width' => '33.3'),
                        ),
                        array(
                            'key' => 'field_slider_overlay_posicion_color2',
                            'label' => 'Posición Color 2 (%)',
                            'name' => 'posicion_color2',
                            'type' => 'number',
                            'required' => 0,
                            'default_value' => 100,
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                            'wrapper' => array('width' => '33.3'),
                        ),
                    ),
                ),
                
                // Subtítulo
                array(
                    'key' => 'field_slider_subtitulo',
                    'label' => 'Subtítulo',
                    'name' => 'slider_subtitulo',
                    'type' => 'text',
                    'required' => 0,
                ),
                
                // Texto
                array(
                    'key' => 'field_slider_texto',
                    'label' => 'Texto',
                    'name' => 'slider_texto',
                    'type' => 'textarea',
                    'required' => 0,
                    'rows' => 4,
                ),
                
                // Color título
                array(
                    'key' => 'field_slider_color_titulo',
                    'label' => 'Color Título',
                    'name' => 'slider_color_titulo',
                    'type' => 'color_picker',
                    'required' => 0,
                    'default_value' => '#ffffff',
                    'wrapper' => array('width' => '33.3'),
                ),
                
                // Color subtítulo
                array(
                    'key' => 'field_slider_color_subtitulo',
                    'label' => 'Color Subtítulo',
                    'name' => 'slider_color_subtitulo',
                    'type' => 'color_picker',
                    'required' => 0,
                    'default_value' => '#ffffff',
                    'wrapper' => array('width' => '33.3'),
                ),
                
                // Color texto
                array(
                    'key' => 'field_slider_color_texto',
                    'label' => 'Color Texto',
                    'name' => 'slider_color_texto',
                    'type' => 'color_picker',
                    'required' => 0,
                    'default_value' => '#ffffff',
                    'wrapper' => array('width' => '33.3'),
                ),
                
                // Grupo Botón
                array(
                    'key' => 'field_slider_boton',
                    'label' => 'Botón',
                    'name' => 'slider_boton',
                    'type' => 'group',
                    'required' => 0,
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_slider_boton_texto',
                            'label' => 'Texto Botón',
                            'name' => 'texto',
                            'type' => 'text',
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_slider_boton_enlace',
                            'label' => 'Enlace Botón',
                            'name' => 'enlace',
                            'type' => 'url',
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_slider_boton_color_borde',
                            'label' => 'Color Borde',
                            'name' => 'color_borde',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => '#ffffff',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_slider_boton_color_texto',
                            'label' => 'Color Texto',
                            'name' => 'color_texto',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => '#ffffff',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_slider_boton_color_fondo',
                            'label' => 'Color Fondo',
                            'name' => 'color_fondo',
                            'type' => 'color_picker',
                            'required' => 0,
                            'wrapper' => array('width' => '25'),
                        ),
                    ),
                ),
                
                // Grupo Botón 2
                array(
                    'key' => 'field_slider_boton_2',
                    'label' => 'Botón 2',
                    'name' => 'slider_boton_2',
                    'type' => 'group',
                    'required' => 0,
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_slider_boton_2_texto',
                            'label' => 'Texto Botón',
                            'name' => 'texto',
                            'type' => 'text',
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_slider_boton_2_enlace',
                            'label' => 'Enlace Botón',
                            'name' => 'enlace',
                            'type' => 'url',
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_slider_boton_2_color_borde',
                            'label' => 'Color Borde',
                            'name' => 'color_borde',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => '#ffffff',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_slider_boton_2_color_texto',
                            'label' => 'Color Texto',
                            'name' => 'color_texto',
                            'type' => 'color_picker',
                            'required' => 0,
                            'default_value' => '#ffffff',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_slider_boton_2_color_fondo',
                            'label' => 'Color Fondo',
                            'name' => 'color_fondo',
                            'type' => 'color_picker',
                            'required' => 0,
                            'wrapper' => array('width' => '25'),
                        ),
                    ),
                ),
                // Repetidor Logos
                array(
                    'key' => 'field_slider_logos',
                    'label' => 'Logos',
                    'name' => 'slider_logos',
                    'type' => 'repeater',
                    'required' => 0,
                    'layout' => 'table',
                    'min' => 0,
                    'max' => 8,
                    'button_label' => 'Agregar Logo',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_slider_logos_imagen',
                            'label' => 'Imagen Logo',
                            'name' => 'imagen',
                            'type' => 'image',
                            'required' => 0,
                            'return_format' => 'id',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                        ),
                    ),
                ),
                
                // Grupo Fechas
                array(
                    'key' => 'field_slider_fechas',
                    'label' => 'Fechas',
                    'name' => 'slider_fechas',
                    'type' => 'group',
                    'required' => 0,
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_slider_fecha_inicio',
                            'label' => 'Fecha Inicio',
                            'name' => 'fecha_inicio',
                            'type' => 'date_picker',
                            'required' => 1,
                            'display_format' => 'd/m/Y',
                            'return_format' => 'Ymd',
                            'default_value' => '20251201',
                            'first_day' => 1,
                            'wrapper' => array('width' => '50'),
                        ),
                        array(
                            'key' => 'field_slider_fecha_fin',
                            'label' => 'Fecha Fin',
                            'name' => 'fecha_fin',
                            'type' => 'date_picker',
                            'required' => 1,
                            'display_format' => 'd/m/Y',
                            'return_format' => 'Ymd',
                            'default_value' => '20271231',
                            'first_day' => 1,
                            'wrapper' => array('width' => '50'),
                        ),
                    ),
                ),
                
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'slider_home',
                    ),
                ),
            ),
            'label_placement' => 'top'
        ));

                
    }
?>