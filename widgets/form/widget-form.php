<?php
/**
 * Widget de Formulario para Elementor - Cámara Valencia
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
 * Clase del Widget de Formulario
 */
class Camara_Form_Widget extends \Elementor\Widget_Base {

    /**
     * Nombre del widget
     */
    public function get_name() {
        return 'form_widget';
    }

    /**
     * Título del widget
     */
    public function get_title() {
        return __( 'Formulario', 'form_widget' );
    }

    /**
     * Icono del widget
     */
    public function get_icon() {
        return 'eicon-parallax';
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
        return [ 'formulario' ];
    }


    public function get_script_depends() {
        return [ 'formulario-script' ];
    }
    
    protected function register_scripts() {
        wp_register_script( 'formulario-script', get_stylesheet_directory_uri() . '/assets/js/formulario.js', array('jquery'), '1.0.3', true );
    }
    
        
    public function get_style_depends() {
        return [ 'formulario-style' ];
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
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Configuración', 'form_widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => __( 'Altura del Slider', 'form_widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .camara-form' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_max_width',
            [
                'label' => __( 'Ancho Máximo', 'form_widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 15,
                        'max' => 60,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .camara-form' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Renderizar el widget
     */
    protected function render() {
        $url_power_automate = 'https://defaultf60c2793e92a439fbca89eea0072e6.56.environment.api.powerplatform.com:443/powerautomate/automations/direct/workflows/ffa25f8e7b9e47dd8cb5515c39cf2afa/triggers/manual/paths/invoke?api-version=1&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=8oV0GCrvPntschQHv7K46E60M5YMe-SEUPgWUjPnguk';
    ?>
        <form action="" id="camara-form-solicitud" class="flex flex-col gap-3" data-power-automate-url="<?php echo esc_url( $url_power_automate ); ?>">
            <input type="text" name="empresa" placeholder="<?php _e("Empresa*", "camaravalencia"); ?>" required>
            <input type="text" name="cif" placeholder="<?php _e("CIF*", "camaravalencia"); ?>" required>
            <input type="text" name="direccion" placeholder="<?php _e("Dirección*", "camaravalencia"); ?>" required>
            <input type="text" name="web" placeholder="<?php _e("Sitio web*", "camaravalencia"); ?>" required>
            <input type="text" name="contacto" placeholder="<?php _e("Persona de contacto*", "camaravalencia"); ?>" required>
            <input type="text" name="cargo" placeholder="<?php _e("Cargo*", "camaravalencia"); ?>" required>
            <input type="email" name="email" placeholder="<?php _e("Correo electrónico*", "camaravalencia"); ?>" required>
                <select name="sector" id="sector-1">
                    <option value="null" selected disabled><?php _e("Seleccione su sector de actividad...","camaravalencia"); ?></option>
                    <option value="superyates"><?php _e("Agentes de superyates","camaravalencia"); ?></option>
                    <option value="bebidas_alcoholicas"><?php _e("Bebidas alcohólicas","camaravalencia"); ?></option>
                    <option value="alojamiento"><?php _e("Alojamiento","camaravalencia"); ?></option>
                    <option value="ambulancias_privadas"><?php _e("Ambulancias (privadas)","camaravalencia"); ?></option>
                    <option value="amplificadores_cobertura_movil"><?php _e("Amplificadores de cobertura móvil","camaravalencia"); ?></option>
                    <option value="contratistas_proveedores_instalaciones_electricas"><?php _e("Contratistas y proveedores de instalaciones eléctricas","camaravalencia"); ?></option>
                    <option value="aparejos_graperia_barcos"><?php _e("Aparejos/grapería de barcos","camaravalencia"); ?></option>
                    <option value="muebles_oficina"><?php _e("Muebles de oficina","camaravalencia"); ?></option>
                    <option value="arte_pintores_escultores"><?php _e("Arte: pintores, escultores","camaravalencia"); ?></option>
                    <option value="ascensores"><?php _e("Ascensores","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="equipamiento_nautico"><?php _e("Equipamiento náutico","camaravalencia"); ?></option>
                    <option value="equipamiento_gimnasio_fitness"><?php _e("Equipamiento de gimnasio y fitness","camaravalencia"); ?></option>
                    <option value="conductores_privados"><?php _e("Conductores privados: coches y conductores","camaravalencia"); ?></option>
                    <option value="fabricacion_escenarios_eventos_privados"><?php _e("Empresa de fabricación de escenarios para eventos privados","camaravalencia"); ?></option>
                    <option value="banos"><?php _e("Baños","camaravalencia"); ?></option>
                    <option value="alquiler_embarcaciones"><?php _e("Alquiler de embarcaciones","camaravalencia"); ?></option>
                    <option value="embarcaciones_apoyo_inflables"><?php _e("Embarcaciones de apoyo, embarcaciones inflables","camaravalencia"); ?></option>
                    <option value="embarcaciones_ver_regata"><?php _e("Embarcaciones para ver la regata desde el agua","camaravalencia"); ?></option>
                    <option value="bebidas"><?php _e("Bebidas","camaravalencia"); ?></option>
                    <option value="branding"><?php _e("Branding","camaravalencia"); ?></option>
                    <option value="branding_aeropuertos_espacios_comerciales"><?php _e("Branding en aeropuertos y espacios comerciales","camaravalencia"); ?></option>
                    <option value="camiones"><?php _e("Camiones","camaravalencia"); ?></option>
                    <option value="catering_privado"><?php _e("Catering (privado)","camaravalencia"); ?></option>
                    <option value="cables_distribucion_datos"><?php _e("Cables para la distribución de datos","camaravalencia"); ?></option>
                    <option value="companias_aereas_privadas"><?php _e("Compañías aéreas privadas/alquiler de aviones privados","camaravalencia"); ?></option>
                    <option value="ordenadores_portatiles_pantallas_escritorio"><?php _e("Ordenadores portátiles y pantallas de escritorio (monitores)","camaravalencia"); ?></option>
                    <option value="comunicaciones_moviles_telecomunicaciones_tarjetas_sim_datos"><?php _e("Comunicaciones móviles/telecomunicaciones/tarjetas sim y tarjetas de datos","camaravalencia"); ?></option>
                    <option value="concesiones_alimentacion_bares"><?php _e("Concesiones para alimentación y bares (alquiler privado)","camaravalencia"); ?></option>
                    <option value="mensajeria"><?php _e("Mensajería","camaravalencia"); ?></option>
                    <option value="equipos_proteccion_individual"><?php _e("Equipos de protección individual","camaravalencia"); ?></option>
                    <option value="uniformes_personal_evento"><?php _e("Uniformes para el personal del evento","camaravalencia"); ?></option>
                    <option value="drones_equipos_drones"><?php _e("Drones y equipos para drones","camaravalencia"); ?></option>
                    <option value="bicicletas_electricas"><?php _e("Bicicletas eléctricas","camaravalencia"); ?></option>
                    <option value="energia_servicios_suministro_privados"><?php _e("Energía (servicios de suministro privados)","camaravalencia"); ?></option>
                    <option value="extintores"><?php _e("Extintores","camaravalencia"); ?></option>
                    <option value="ferreteria_madera_generica"><?php _e("Ferretería y madera genérica","camaravalencia"); ?></option>
                    <option value="fisioterapeutas"><?php _e("Fisioterapeutas","camaravalencia"); ?></option>
                    <option value="suministrador_combustible_embarcaciones"><?php _e("Suministrador de combustible (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedor_hidrogeno_estaciones_repostaje"><?php _e("Proveedor de hidrógeno y montaje de estaciones de repostaje de hidrógeno (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedores_automoviles"><?php _e("Proveedores de automóviles","camaravalencia"); ?></option>
                    <option value="proveedores_alimentos_productos_alimenticios"><?php _e("Proveedores de alimentos y productos alimenticios","camaravalencia"); ?></option>
                    <option value="proveedores_internet_wifi"><?php _e("Proveedores de internet y wifi – proveedores privados","camaravalencia"); ?></option>
                    <option value="proveedores_escenarios_plataformas"><?php _e("Proveedores de escenarios y plataformas","camaravalencia"); ?></option>
                    <option value="proveedores_estructuras"><?php _e("Proveedores de estructuras","camaravalencia"); ?></option>
                    <option value="proveedores_carpas"><?php _e("Proveedores de carpas","camaravalencia"); ?></option>
                    <option value="generadores"><?php _e("Generadores","camaravalencia"); ?></option>
                    <option value="joyerias_locales"><?php _e("Joyerías locales","camaravalencia"); ?></option>
                    <option value="carros_golf"><?php _e("Carros de golf","camaravalencia"); ?></option>
                    <option value="gruas"><?php _e("Grúas","camaravalencia"); ?></option>
                    <option value="hardware_telefonos_moviles"><?php _e("Hardware para teléfonos móviles","camaravalencia"); ?></option>
                    <option value="iluminacion"><?php _e("Iluminación","camaravalencia"); ?></option>
                    <option value="iluminacion_instalaciones_electricas"><?php _e("Iluminación: instalaciones eléctricas para iluminación","camaravalencia"); ?></option>
                    <option value="entretenimiento_alquiler_privado_musica_cultura_artes"><?php _e("Entretenimiento de alquiler privado: música, cultura, artes (entretenimiento local)","camaravalencia"); ?></option>
                    <option value="mantenimiento_motores_marinos_barcos"><?php _e("Mantenimiento de motores marinos/de barcos","camaravalencia"); ?></option>
                    <option value="marina_asociada_privada"><?php _e("Marina asociada (privada)","camaravalencia"); ?></option>
                    <option value="medicos_privados"><?php _e("Médicos (privados)","camaravalencia"); ?></option>
                    <option value="muebles"><?php _e("Muebles","camaravalencia"); ?></option>
                    <option value="muebles_exterior"><?php _e("Muebles de exterior","camaravalencia"); ?></option>
                    <option value="scooters_motos_patinetes"><?php _e("Scooters/motos/patinetes","camaravalencia"); ?></option>
                    <option value="lanchas_transporte"><?php _e("Lanchas de transporte","camaravalencia"); ?></option>
                    <option value="alquiler_equipos_vehiculos_trabajo"><?php _e("Alquiler de equipos y vehículos de trabajo – alquiler de equipos y medios de trabajo – carretillas elevadoras, plataformas elevadoras de cizalla, elevadores de torre","camaravalencia"); ?></option>
                    <option value="alquiler_contenedores"><?php _e("Alquiler de contenedores","camaravalencia"); ?></option>
                    <option value="alquiler_televisores"><?php _e("Alquiler de televisores","camaravalencia"); ?></option>
                    <option value="talleres_mecanicos_acero_titanio"><?php _e("Talleres mecánicos (acero/titanio)","camaravalencia"); ?></option>
                    <option value="gimnasios_privados"><?php _e("Gimnasios (privados)","camaravalencia"); ?></option>
                    <option value="aparcamientos_privados"><?php _e("Aparcamientos (privados)","camaravalencia"); ?></option>
                    <option value="personal_evento"><?php _e("Personal del evento","camaravalencia"); ?></option>
                    <option value="primeros_auxilios_tiendas_medicas"><?php _e("Personal de primeros auxilios y tiendas médicas","camaravalencia"); ?></option>
                    <option value="gestion_trafico_privado"><?php _e("Personal para la gestión del tráfico (privado)","camaravalencia"); ?></option>
                    <option value="plantas_flores"><?php _e("Plantas y flores","camaravalencia"); ?></option>
                    <option value="plataformas_accesibilidad"><?php _e("Plataformas de accesibilidad","camaravalencia"); ?></option>
                    <option value="muelles_rampas"><?php _e("Muelles y rampas para muelles","camaravalencia"); ?></option>
                    <option value="asientos"><?php _e("Asientos y poufs","camaravalencia"); ?></option>
                    <option value="amarraduras"><?php _e("Amarraduras/aqueradas (en marinas, clubes náuticos/circulos, puertos)","camaravalencia"); ?></option>
                    <option value="prefabricados"><?php _e("Prefabricados","camaravalencia"); ?></option>
                    <option value="disenadores_cad"><?php _e("Diseñadores cad","camaravalencia"); ?></option>
                    <option value="puntos_recarga_electrica"><?php _e("Puntos de recarga eléctrica (para barcos y coches)","camaravalencia"); ?></option>
                    <option value="vallas"><?php _e("Vallas","camaravalencia"); ?></option>
                    <option value="restaurantes_bares"><?php _e("Restaurantes y bares (para alquiler privado)","camaravalencia"); ?></option>
                    <option value="escaleras"><?php _e("Escaleras (exteriores e interiores)","camaravalencia"); ?></option>
                    <option value="escaneres_control_multitudes"><?php _e("Escáneres para el control de multitudes","camaravalencia"); ?></option>
                    <option value="pantallas"><?php _e("Pantallas","camaravalencia"); ?></option>
                    <option value="sedes_hospitalidad"><?php _e("Sedes de hospitalidad (terrazas con vistas a las sedes de hospitalidad (terrazas con vistas al campo de regatas con sedes para eventos, hospitalidad de alquiler ","camaravalencia"); ?></option>Privado)
                    <option value="sedes_ubicaciones_eventos"><?php _e("Sedes/ubicaciones para eventos (alquiler privado)","camaravalencia"); ?></option>
                    <option value="servicios_aeroportuarios_privados"><?php _e("Servicios aeroportuarios privados","camaravalencia"); ?></option>
                    <option value="servicios_medioambientales_privados"><?php _e("Servicios medioambientales privados","camaravalencia"); ?></option>
                    <option value="servicios_prevencion_incendios_apoyo_bomberos"><?php _e("Servicios de prevención de incendios y apoyo/bomberos","camaravalencia"); ?></option>
                    <option value="servicios_ciberseguridad_seguridad_informatica"><?php _e("Servicios de ciberseguridad/seguridad informática","camaravalencia"); ?></option>
                    <option value="servicios_helicoptero_alquiler_privado_pilotos_tv"><?php _e("Servicios de helicóptero (alquiler privado), pilotos (para tv)","camaravalencia"); ?></option>
                    <option value="servicios_hidraulica_montaje_banos"><?php _e("Servicios de hidráulica y montaje de baños","camaravalencia"); ?></option>
                    <option value="servicios_diseno_grafico"><?php _e("Servicios de diseño gráfico","camaravalencia"); ?></option>
                    <option value="servicios_limpieza"><?php _e("Servicios de limpieza","camaravalencia"); ?></option>
                    <option value="servicios_contratacion"><?php _e("Servicios de contratación","camaravalencia"); ?></option>
                    <option value="servicios_traduccion"><?php _e("Servicios de traducción","camaravalencia"); ?></option>
                    <option value="servicios_voluntariado"><?php _e("Servicios de voluntariado","camaravalencia"); ?></option>
                    <option value="servicios_guardia_costera"><?php _e("Servicios y asistencia de la guardia costera","camaravalencia"); ?></option>
                    <option value="servicios_medicos"><?php _e("Servicios médicos (hospitales privados, clínicas, médicos)","camaravalencia"); ?></option>
                    <option value="servicios_gestion_invitados_vip"><?php _e("Servicios privados de gestión de invitados vip","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_audiovisuales"><?php _e("Sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas de audio","camaravalencia"); ?></option>
                    <option value="sistemas_cctv"><?php _e("Sistemas de cctv y cámaras de seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_control_acceso"><?php _e("Sistemas de control de acceso y recuento","camaravalencia"); ?></option>
                    <option value="sistemas_filtrado_agua"><?php _e("Sistemas de filtrado de agua de lluvia y almacenamiento de agua de lluvia","camaravalencia"); ?></option>
                    <option value="sistemas_paneles_solares"><?php _e("Sistemas de paneles solares/estaciones sistemas de paneles solares/estaciones de recarga solar/pod de recarga solar para teléfonos/paneles solares para las bases de ","camaravalencia"); ?></option>Los Equipos
                    <option value="sistemas_senalizacion"><?php _e("Sistemas de señalización","camaravalencia"); ?></option>
                    <option value="sistemas_aire_acondicionado"><?php _e("Sistemas y unidades de aire acondicionado","camaravalencia"); ?></option>
                    <option value="sistemas_acreditacion"><?php _e("Sistemas de acreditación","camaravalencia"); ?></option>
                    <option value="especialista_materiales_compuestos"><?php _e("Especialista en materiales compuestos","camaravalencia"); ?></option>
                    <option value="especialistas_hidraulica"><?php _e("Especialistas en hidráulica","camaravalencia"); ?></option>
                    <option value="especialistas_despacho_aduanas"><?php _e("Especialistas en despacho de aduanas","camaravalencia"); ?></option>
                    <option value="envios"><?php _e("Envíos","camaravalencia"); ?></option>
                    <option value="impresora_metalica_3d"><?php _e("Impresora metálica 3d","camaravalencia"); ?></option>
                    <option value="almacenamiento_contenedores"><?php _e("Almacenamiento de contenedores","camaravalencia"); ?></option>
                    <option value="estructuras_sombra"><?php _e("Estructuras para crear sombra (grandes y pequeñas)","camaravalencia"); ?></option>
                    <option value="estructuras_temporales"><?php _e("Estructuras temporales","camaravalencia"); ?></option>
                    <option value="soporte_informatico"><?php _e("Soporte informático - proveedores privados","camaravalencia"); ?></option>
                    <option value="alfombras"><?php _e("Alfombras","camaravalencia"); ?></option>
                    <option value="tecnicos_electronica"><?php _e("Técnicos especializados en electrónica","camaravalencia"); ?></option>
                    <option value="ferries_aliscafos"><?php _e("Ferries/aliscafos","camaravalencia"); ?></option>
                    <option value="transfers_helicoptero"><?php _e("Transfers privados vip en helicóptero (del aeropuerto al lugar del evento)","camaravalencia"); ?></option>
                    <option value="transporte_privado"><?php _e("Transporte privado de alquiler","camaravalencia"); ?></option>
                    <option value="transporte_logistica"><?php _e("Transporte de mercancías y logística","camaravalencia"); ?></option>
                    <option value="tribunas"><?php _e("Tribunas","camaravalencia"); ?></option>
                    <option value="unidades_control_radio"><?php _e("Unidades de control de radio/radio","camaravalencia"); ?></option>
                    <option value="unidades_exposicion"><?php _e("Unidades de exposición","camaravalencia"); ?></option>
                    <option value="veleria"><?php _e("Velería","camaravalencia"); ?></option>
                    <option value="videojuegos_gaming"><?php _e("Videojuegos-gaming","camaravalencia"); ?></option>
                    <option value="vino"><?php _e("Vino","camaravalencia"); ?></option>
                    <option value="otros_sectores"><?php _e("Otros sectores","camaravalencia"); ?></option>
                    <option value="servicios_recepcion_hostess"><?php _e("Servicios de recepción hostess","camaravalencia"); ?></option>
                </select>
                <select name="sector" id="sector-2">
                    <option value="null" selected disabled><?php _e("Seleccione su sector de actividad...","camaravalencia"); ?></option>
                    <option value="superyates"><?php _e("Agentes de superyates","camaravalencia"); ?></option>
                    <option value="bebidas_alcoholicas"><?php _e("Bebidas alcohólicas","camaravalencia"); ?></option>
                    <option value="alojamiento"><?php _e("Alojamiento","camaravalencia"); ?></option>
                    <option value="ambulancias_privadas"><?php _e("Ambulancias (privadas)","camaravalencia"); ?></option>
                    <option value="amplificadores_cobertura_movil"><?php _e("Amplificadores de cobertura móvil","camaravalencia"); ?></option>
                    <option value="contratistas_proveedores_instalaciones_electricas"><?php _e("Contratistas y proveedores de instalaciones eléctricas","camaravalencia"); ?></option>
                    <option value="aparejos_graperia_barcos"><?php _e("Aparejos/grapería de barcos","camaravalencia"); ?></option>
                    <option value="muebles_oficina"><?php _e("Muebles de oficina","camaravalencia"); ?></option>
                    <option value="arte_pintores_escultores"><?php _e("Arte: pintores, escultores","camaravalencia"); ?></option>
                    <option value="ascensores"><?php _e("Ascensores","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="equipamiento_nautico"><?php _e("Equipamiento náutico","camaravalencia"); ?></option>
                    <option value="equipamiento_gimnasio_fitness"><?php _e("Equipamiento de gimnasio y fitness","camaravalencia"); ?></option>
                    <option value="conductores_privados"><?php _e("Conductores privados: coches y conductores","camaravalencia"); ?></option>
                    <option value="fabricacion_escenarios_eventos_privados"><?php _e("Empresa de fabricación de escenarios para eventos privados","camaravalencia"); ?></option>
                    <option value="banos"><?php _e("Baños","camaravalencia"); ?></option>
                    <option value="alquiler_embarcaciones"><?php _e("Alquiler de embarcaciones","camaravalencia"); ?></option>
                    <option value="embarcaciones_apoyo_inflables"><?php _e("Embarcaciones de apoyo, embarcaciones inflables","camaravalencia"); ?></option>
                    <option value="embarcaciones_ver_regata"><?php _e("Embarcaciones para ver la regata desde el agua","camaravalencia"); ?></option>
                    <option value="bebidas"><?php _e("Bebidas","camaravalencia"); ?></option>
                    <option value="branding"><?php _e("Branding","camaravalencia"); ?></option>
                    <option value="branding_aeropuertos_espacios_comerciales"><?php _e("Branding en aeropuertos y espacios comerciales","camaravalencia"); ?></option>
                    <option value="camiones"><?php _e("Camiones","camaravalencia"); ?></option>
                    <option value="catering_privado"><?php _e("Catering (privado)","camaravalencia"); ?></option>
                    <option value="cables_distribucion_datos"><?php _e("Cables para la distribución de datos","camaravalencia"); ?></option>
                    <option value="companias_aereas_privadas"><?php _e("Compañías aéreas privadas/alquiler de aviones privados","camaravalencia"); ?></option>
                    <option value="ordenadores_portatiles_pantallas_escritorio"><?php _e("Ordenadores portátiles y pantallas de escritorio (monitores)","camaravalencia"); ?></option>
                    <option value="comunicaciones_moviles_telecomunicaciones_tarjetas_sim_datos"><?php _e("Comunicaciones móviles/telecomunicaciones/tarjetas sim y tarjetas de datos","camaravalencia"); ?></option>
                    <option value="concesiones_alimentacion_bares"><?php _e("Concesiones para alimentación y bares (alquiler privado)","camaravalencia"); ?></option>
                    <option value="mensajeria"><?php _e("Mensajería","camaravalencia"); ?></option>
                    <option value="equipos_proteccion_individual"><?php _e("Equipos de protección individual","camaravalencia"); ?></option>
                    <option value="uniformes_personal_evento"><?php _e("Uniformes para el personal del evento","camaravalencia"); ?></option>
                    <option value="drones_equipos_drones"><?php _e("Drones y equipos para drones","camaravalencia"); ?></option>
                    <option value="bicicletas_electricas"><?php _e("Bicicletas eléctricas","camaravalencia"); ?></option>
                    <option value="energia_servicios_suministro_privados"><?php _e("Energía (servicios de suministro privados)","camaravalencia"); ?></option>
                    <option value="extintores"><?php _e("Extintores","camaravalencia"); ?></option>
                    <option value="ferreteria_madera_generica"><?php _e("Ferretería y madera genérica","camaravalencia"); ?></option>
                    <option value="fisioterapeutas"><?php _e("Fisioterapeutas","camaravalencia"); ?></option>
                    <option value="suministrador_combustible_embarcaciones"><?php _e("Suministrador de combustible (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedor_hidrogeno_estaciones_repostaje"><?php _e("Proveedor de hidrógeno y montaje de estaciones de repostaje de hidrógeno (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedores_automoviles"><?php _e("Proveedores de automóviles","camaravalencia"); ?></option>
                    <option value="proveedores_alimentos_productos_alimenticios"><?php _e("Proveedores de alimentos y productos alimenticios","camaravalencia"); ?></option>
                    <option value="proveedores_internet_wifi"><?php _e("Proveedores de internet y wifi – proveedores privados","camaravalencia"); ?></option>
                    <option value="proveedores_escenarios_plataformas"><?php _e("Proveedores de escenarios y plataformas","camaravalencia"); ?></option>
                    <option value="proveedores_estructuras"><?php _e("Proveedores de estructuras","camaravalencia"); ?></option>
                    <option value="proveedores_carpas"><?php _e("Proveedores de carpas","camaravalencia"); ?></option>
                    <option value="generadores"><?php _e("Generadores","camaravalencia"); ?></option>
                    <option value="joyerias_locales"><?php _e("Joyerías locales","camaravalencia"); ?></option>
                    <option value="carros_golf"><?php _e("Carros de golf","camaravalencia"); ?></option>
                    <option value="gruas"><?php _e("Grúas","camaravalencia"); ?></option>
                    <option value="hardware_telefonos_moviles"><?php _e("Hardware para teléfonos móviles","camaravalencia"); ?></option>
                    <option value="iluminacion"><?php _e("Iluminación","camaravalencia"); ?></option>
                    <option value="iluminacion_instalaciones_electricas"><?php _e("Iluminación: instalaciones eléctricas para iluminación","camaravalencia"); ?></option>
                    <option value="entretenimiento_alquiler_privado_musica_cultura_artes"><?php _e("Entretenimiento de alquiler privado: música, cultura, artes (entretenimiento local)","camaravalencia"); ?></option>
                    <option value="mantenimiento_motores_marinos_barcos"><?php _e("Mantenimiento de motores marinos/de barcos","camaravalencia"); ?></option>
                    <option value="marina_asociada_privada"><?php _e("Marina asociada (privada)","camaravalencia"); ?></option>
                    <option value="medicos_privados"><?php _e("Médicos (privados)","camaravalencia"); ?></option>
                    <option value="muebles"><?php _e("Muebles","camaravalencia"); ?></option>
                    <option value="muebles_exterior"><?php _e("Muebles de exterior","camaravalencia"); ?></option>
                    <option value="scooters_motos_patinetes"><?php _e("Scooters/motos/patinetes","camaravalencia"); ?></option>
                    <option value="lanchas_transporte"><?php _e("Lanchas de transporte","camaravalencia"); ?></option>
                    <option value="alquiler_equipos_vehiculos_trabajo"><?php _e("Alquiler de equipos y vehículos de trabajo – alquiler de equipos y medios de trabajo – carretillas elevadoras, plataformas elevadoras de cizalla, elevadores de torre","camaravalencia"); ?></option>
                    <option value="alquiler_contenedores"><?php _e("Alquiler de contenedores","camaravalencia"); ?></option>
                    <option value="alquiler_televisores"><?php _e("Alquiler de televisores","camaravalencia"); ?></option>
                    <option value="talleres_mecanicos_acero_titanio"><?php _e("Talleres mecánicos (acero/titanio)","camaravalencia"); ?></option>
                    <option value="gimnasios_privados"><?php _e("Gimnasios (privados)","camaravalencia"); ?></option>
                    <option value="aparcamientos_privados"><?php _e("Aparcamientos (privados)","camaravalencia"); ?></option>
                    <option value="personal_evento"><?php _e("Personal del evento","camaravalencia"); ?></option>
                    <option value="primeros_auxilios_tiendas_medicas"><?php _e("Personal de primeros auxilios y tiendas médicas","camaravalencia"); ?></option>
                    <option value="gestion_trafico_privado"><?php _e("Personal para la gestión del tráfico (privado)","camaravalencia"); ?></option>
                    <option value="plantas_flores"><?php _e("Plantas y flores","camaravalencia"); ?></option>
                    <option value="plataformas_accesibilidad"><?php _e("Plataformas de accesibilidad","camaravalencia"); ?></option>
                    <option value="muelles_rampas"><?php _e("Muelles y rampas para muelles","camaravalencia"); ?></option>
                    <option value="asientos"><?php _e("Asientos y poufs","camaravalencia"); ?></option>
                    <option value="amarraduras"><?php _e("Amarraduras/aqueradas (en marinas, clubes náuticos/circulos, puertos)","camaravalencia"); ?></option>
                    <option value="prefabricados"><?php _e("Prefabricados","camaravalencia"); ?></option>
                    <option value="disenadores_cad"><?php _e("Diseñadores cad","camaravalencia"); ?></option>
                    <option value="puntos_recarga_electrica"><?php _e("Puntos de recarga eléctrica (para barcos y coches)","camaravalencia"); ?></option>
                    <option value="vallas"><?php _e("Vallas","camaravalencia"); ?></option>
                    <option value="restaurantes_bares"><?php _e("Restaurantes y bares (para alquiler privado)","camaravalencia"); ?></option>
                    <option value="escaleras"><?php _e("Escaleras (exteriores e interiores)","camaravalencia"); ?></option>
                    <option value="escaneres_control_multitudes"><?php _e("Escáneres para el control de multitudes","camaravalencia"); ?></option>
                    <option value="pantallas"><?php _e("Pantallas","camaravalencia"); ?></option>
                    <option value="sedes_hospitalidad"><?php _e("Sedes de hospitalidad (terrazas con vistas a las sedes de hospitalidad (terrazas con vistas al campo de regatas con sedes para eventos, hospitalidad de alquiler ","camaravalencia"); ?></option>Privado)
                    <option value="sedes_ubicaciones_eventos"><?php _e("Sedes/ubicaciones para eventos (alquiler privado)","camaravalencia"); ?></option>
                    <option value="servicios_aeroportuarios_privados"><?php _e("Servicios aeroportuarios privados","camaravalencia"); ?></option>
                    <option value="servicios_medioambientales_privados"><?php _e("Servicios medioambientales privados","camaravalencia"); ?></option>
                    <option value="servicios_prevencion_incendios_apoyo_bomberos"><?php _e("Servicios de prevención de incendios y apoyo/bomberos","camaravalencia"); ?></option>
                    <option value="servicios_ciberseguridad_seguridad_informatica"><?php _e("Servicios de ciberseguridad/seguridad informática","camaravalencia"); ?></option>
                    <option value="servicios_helicoptero_alquiler_privado_pilotos_tv"><?php _e("Servicios de helicóptero (alquiler privado), pilotos (para tv)","camaravalencia"); ?></option>
                    <option value="servicios_hidraulica_montaje_banos"><?php _e("Servicios de hidráulica y montaje de baños","camaravalencia"); ?></option>
                    <option value="servicios_diseno_grafico"><?php _e("Servicios de diseño gráfico","camaravalencia"); ?></option>
                    <option value="servicios_limpieza"><?php _e("Servicios de limpieza","camaravalencia"); ?></option>
                    <option value="servicios_contratacion"><?php _e("Servicios de contratación","camaravalencia"); ?></option>
                    <option value="servicios_traduccion"><?php _e("Servicios de traducción","camaravalencia"); ?></option>
                    <option value="servicios_voluntariado"><?php _e("Servicios de voluntariado","camaravalencia"); ?></option>
                    <option value="servicios_guardia_costera"><?php _e("Servicios y asistencia de la guardia costera","camaravalencia"); ?></option>
                    <option value="servicios_medicos"><?php _e("Servicios médicos (hospitales privados, clínicas, médicos)","camaravalencia"); ?></option>
                    <option value="servicios_gestion_invitados_vip"><?php _e("Servicios privados de gestión de invitados vip","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_audiovisuales"><?php _e("Sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas de audio","camaravalencia"); ?></option>
                    <option value="sistemas_cctv"><?php _e("Sistemas de cctv y cámaras de seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_control_acceso"><?php _e("Sistemas de control de acceso y recuento","camaravalencia"); ?></option>
                    <option value="sistemas_filtrado_agua"><?php _e("Sistemas de filtrado de agua de lluvia y almacenamiento de agua de lluvia","camaravalencia"); ?></option>
                    <option value="sistemas_paneles_solares"><?php _e("Sistemas de paneles solares/estaciones sistemas de paneles solares/estaciones de recarga solar/pod de recarga solar para teléfonos/paneles solares para las bases de ","camaravalencia"); ?></option>Los Equipos
                    <option value="sistemas_senalizacion"><?php _e("Sistemas de señalización","camaravalencia"); ?></option>
                    <option value="sistemas_aire_acondicionado"><?php _e("Sistemas y unidades de aire acondicionado","camaravalencia"); ?></option>
                    <option value="sistemas_acreditacion"><?php _e("Sistemas de acreditación","camaravalencia"); ?></option>
                    <option value="especialista_materiales_compuestos"><?php _e("Especialista en materiales compuestos","camaravalencia"); ?></option>
                    <option value="especialistas_hidraulica"><?php _e("Especialistas en hidráulica","camaravalencia"); ?></option>
                    <option value="especialistas_despacho_aduanas"><?php _e("Especialistas en despacho de aduanas","camaravalencia"); ?></option>
                    <option value="envios"><?php _e("Envíos","camaravalencia"); ?></option>
                    <option value="impresora_metalica_3d"><?php _e("Impresora metálica 3d","camaravalencia"); ?></option>
                    <option value="almacenamiento_contenedores"><?php _e("Almacenamiento de contenedores","camaravalencia"); ?></option>
                    <option value="estructuras_sombra"><?php _e("Estructuras para crear sombra (grandes y pequeñas)","camaravalencia"); ?></option>
                    <option value="estructuras_temporales"><?php _e("Estructuras temporales","camaravalencia"); ?></option>
                    <option value="soporte_informatico"><?php _e("Soporte informático - proveedores privados","camaravalencia"); ?></option>
                    <option value="alfombras"><?php _e("Alfombras","camaravalencia"); ?></option>
                    <option value="tecnicos_electronica"><?php _e("Técnicos especializados en electrónica","camaravalencia"); ?></option>
                    <option value="ferries_aliscafos"><?php _e("Ferries/aliscafos","camaravalencia"); ?></option>
                    <option value="transfers_helicoptero"><?php _e("Transfers privados vip en helicóptero (del aeropuerto al lugar del evento)","camaravalencia"); ?></option>
                    <option value="transporte_privado"><?php _e("Transporte privado de alquiler","camaravalencia"); ?></option>
                    <option value="transporte_logistica"><?php _e("Transporte de mercancías y logística","camaravalencia"); ?></option>
                    <option value="tribunas"><?php _e("Tribunas","camaravalencia"); ?></option>
                    <option value="unidades_control_radio"><?php _e("Unidades de control de radio/radio","camaravalencia"); ?></option>
                    <option value="unidades_exposicion"><?php _e("Unidades de exposición","camaravalencia"); ?></option>
                    <option value="veleria"><?php _e("Velería","camaravalencia"); ?></option>
                    <option value="videojuegos_gaming"><?php _e("Videojuegos-gaming","camaravalencia"); ?></option>
                    <option value="vino"><?php _e("Vino","camaravalencia"); ?></option>
                    <option value="otros_sectores"><?php _e("Otros sectores","camaravalencia"); ?></option>
                    <option value="servicios_recepcion_hostess"><?php _e("Servicios de recepción hostess","camaravalencia"); ?></option>
                </select>
                <select name="sector" id="sector-3">
                    <option value="null" selected disabled><?php _e("Seleccione su sector de actividad...","camaravalencia"); ?></option>
                    <option value="superyates"><?php _e("Agentes de superyates","camaravalencia"); ?></option>
                    <option value="bebidas_alcoholicas"><?php _e("Bebidas alcohólicas","camaravalencia"); ?></option>
                    <option value="alojamiento"><?php _e("Alojamiento","camaravalencia"); ?></option>
                    <option value="ambulancias_privadas"><?php _e("Ambulancias (privadas)","camaravalencia"); ?></option>
                    <option value="amplificadores_cobertura_movil"><?php _e("Amplificadores de cobertura móvil","camaravalencia"); ?></option>
                    <option value="contratistas_proveedores_instalaciones_electricas"><?php _e("Contratistas y proveedores de instalaciones eléctricas","camaravalencia"); ?></option>
                    <option value="aparejos_graperia_barcos"><?php _e("Aparejos/grapería de barcos","camaravalencia"); ?></option>
                    <option value="muebles_oficina"><?php _e("Muebles de oficina","camaravalencia"); ?></option>
                    <option value="arte_pintores_escultores"><?php _e("Arte: pintores, escultores","camaravalencia"); ?></option>
                    <option value="ascensores"><?php _e("Ascensores","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="equipamiento_nautico"><?php _e("Equipamiento náutico","camaravalencia"); ?></option>
                    <option value="equipamiento_gimnasio_fitness"><?php _e("Equipamiento de gimnasio y fitness","camaravalencia"); ?></option>
                    <option value="conductores_privados"><?php _e("Conductores privados: coches y conductores","camaravalencia"); ?></option>
                    <option value="fabricacion_escenarios_eventos_privados"><?php _e("Empresa de fabricación de escenarios para eventos privados","camaravalencia"); ?></option>
                    <option value="banos"><?php _e("Baños","camaravalencia"); ?></option>
                    <option value="alquiler_embarcaciones"><?php _e("Alquiler de embarcaciones","camaravalencia"); ?></option>
                    <option value="embarcaciones_apoyo_inflables"><?php _e("Embarcaciones de apoyo, embarcaciones inflables","camaravalencia"); ?></option>
                    <option value="embarcaciones_ver_regata"><?php _e("Embarcaciones para ver la regata desde el agua","camaravalencia"); ?></option>
                    <option value="bebidas"><?php _e("Bebidas","camaravalencia"); ?></option>
                    <option value="branding"><?php _e("Branding","camaravalencia"); ?></option>
                    <option value="branding_aeropuertos_espacios_comerciales"><?php _e("Branding en aeropuertos y espacios comerciales","camaravalencia"); ?></option>
                    <option value="camiones"><?php _e("Camiones","camaravalencia"); ?></option>
                    <option value="catering_privado"><?php _e("Catering (privado)","camaravalencia"); ?></option>
                    <option value="cables_distribucion_datos"><?php _e("Cables para la distribución de datos","camaravalencia"); ?></option>
                    <option value="companias_aereas_privadas"><?php _e("Compañías aéreas privadas/alquiler de aviones privados","camaravalencia"); ?></option>
                    <option value="ordenadores_portatiles_pantallas_escritorio"><?php _e("Ordenadores portátiles y pantallas de escritorio (monitores)","camaravalencia"); ?></option>
                    <option value="comunicaciones_moviles_telecomunicaciones_tarjetas_sim_datos"><?php _e("Comunicaciones móviles/telecomunicaciones/tarjetas sim y tarjetas de datos","camaravalencia"); ?></option>
                    <option value="concesiones_alimentacion_bares"><?php _e("Concesiones para alimentación y bares (alquiler privado)","camaravalencia"); ?></option>
                    <option value="mensajeria"><?php _e("Mensajería","camaravalencia"); ?></option>
                    <option value="equipos_proteccion_individual"><?php _e("Equipos de protección individual","camaravalencia"); ?></option>
                    <option value="uniformes_personal_evento"><?php _e("Uniformes para el personal del evento","camaravalencia"); ?></option>
                    <option value="drones_equipos_drones"><?php _e("Drones y equipos para drones","camaravalencia"); ?></option>
                    <option value="bicicletas_electricas"><?php _e("Bicicletas eléctricas","camaravalencia"); ?></option>
                    <option value="energia_servicios_suministro_privados"><?php _e("Energía (servicios de suministro privados)","camaravalencia"); ?></option>
                    <option value="extintores"><?php _e("Extintores","camaravalencia"); ?></option>
                    <option value="ferreteria_madera_generica"><?php _e("Ferretería y madera genérica","camaravalencia"); ?></option>
                    <option value="fisioterapeutas"><?php _e("Fisioterapeutas","camaravalencia"); ?></option>
                    <option value="suministrador_combustible_embarcaciones"><?php _e("Suministrador de combustible (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedor_hidrogeno_estaciones_repostaje"><?php _e("Proveedor de hidrógeno y montaje de estaciones de repostaje de hidrógeno (para embarcaciones)","camaravalencia"); ?></option>
                    <option value="proveedores_automoviles"><?php _e("Proveedores de automóviles","camaravalencia"); ?></option>
                    <option value="proveedores_alimentos_productos_alimenticios"><?php _e("Proveedores de alimentos y productos alimenticios","camaravalencia"); ?></option>
                    <option value="proveedores_internet_wifi"><?php _e("Proveedores de internet y wifi – proveedores privados","camaravalencia"); ?></option>
                    <option value="proveedores_escenarios_plataformas"><?php _e("Proveedores de escenarios y plataformas","camaravalencia"); ?></option>
                    <option value="proveedores_estructuras"><?php _e("Proveedores de estructuras","camaravalencia"); ?></option>
                    <option value="proveedores_carpas"><?php _e("Proveedores de carpas","camaravalencia"); ?></option>
                    <option value="generadores"><?php _e("Generadores","camaravalencia"); ?></option>
                    <option value="joyerias_locales"><?php _e("Joyerías locales","camaravalencia"); ?></option>
                    <option value="carros_golf"><?php _e("Carros de golf","camaravalencia"); ?></option>
                    <option value="gruas"><?php _e("Grúas","camaravalencia"); ?></option>
                    <option value="hardware_telefonos_moviles"><?php _e("Hardware para teléfonos móviles","camaravalencia"); ?></option>
                    <option value="iluminacion"><?php _e("Iluminación","camaravalencia"); ?></option>
                    <option value="iluminacion_instalaciones_electricas"><?php _e("Iluminación: instalaciones eléctricas para iluminación","camaravalencia"); ?></option>
                    <option value="entretenimiento_alquiler_privado_musica_cultura_artes"><?php _e("Entretenimiento de alquiler privado: música, cultura, artes (entretenimiento local)","camaravalencia"); ?></option>
                    <option value="mantenimiento_motores_marinos_barcos"><?php _e("Mantenimiento de motores marinos/de barcos","camaravalencia"); ?></option>
                    <option value="marina_asociada_privada"><?php _e("Marina asociada (privada)","camaravalencia"); ?></option>
                    <option value="medicos_privados"><?php _e("Médicos (privados)","camaravalencia"); ?></option>
                    <option value="muebles"><?php _e("Muebles","camaravalencia"); ?></option>
                    <option value="muebles_exterior"><?php _e("Muebles de exterior","camaravalencia"); ?></option>
                    <option value="scooters_motos_patinetes"><?php _e("Scooters/motos/patinetes","camaravalencia"); ?></option>
                    <option value="lanchas_transporte"><?php _e("Lanchas de transporte","camaravalencia"); ?></option>
                    <option value="alquiler_equipos_vehiculos_trabajo"><?php _e("Alquiler de equipos y vehículos de trabajo – alquiler de equipos y medios de trabajo – carretillas elevadoras, plataformas elevadoras de cizalla, elevadores de torre","camaravalencia"); ?></option>
                    <option value="alquiler_contenedores"><?php _e("Alquiler de contenedores","camaravalencia"); ?></option>
                    <option value="alquiler_televisores"><?php _e("Alquiler de televisores","camaravalencia"); ?></option>
                    <option value="talleres_mecanicos_acero_titanio"><?php _e("Talleres mecánicos (acero/titanio)","camaravalencia"); ?></option>
                    <option value="gimnasios_privados"><?php _e("Gimnasios (privados)","camaravalencia"); ?></option>
                    <option value="aparcamientos_privados"><?php _e("Aparcamientos (privados)","camaravalencia"); ?></option>
                    <option value="personal_evento"><?php _e("Personal del evento","camaravalencia"); ?></option>
                    <option value="primeros_auxilios_tiendas_medicas"><?php _e("Personal de primeros auxilios y tiendas médicas","camaravalencia"); ?></option>
                    <option value="gestion_trafico_privado"><?php _e("Personal para la gestión del tráfico (privado)","camaravalencia"); ?></option>
                    <option value="plantas_flores"><?php _e("Plantas y flores","camaravalencia"); ?></option>
                    <option value="plataformas_accesibilidad"><?php _e("Plataformas de accesibilidad","camaravalencia"); ?></option>
                    <option value="muelles_rampas"><?php _e("Muelles y rampas para muelles","camaravalencia"); ?></option>
                    <option value="asientos"><?php _e("Asientos y poufs","camaravalencia"); ?></option>
                    <option value="amarraduras"><?php _e("Amarraduras/aqueradas (en marinas, clubes náuticos/circulos, puertos)","camaravalencia"); ?></option>
                    <option value="prefabricados"><?php _e("Prefabricados","camaravalencia"); ?></option>
                    <option value="disenadores_cad"><?php _e("Diseñadores cad","camaravalencia"); ?></option>
                    <option value="puntos_recarga_electrica"><?php _e("Puntos de recarga eléctrica (para barcos y coches)","camaravalencia"); ?></option>
                    <option value="vallas"><?php _e("Vallas","camaravalencia"); ?></option>
                    <option value="restaurantes_bares"><?php _e("Restaurantes y bares (para alquiler privado)","camaravalencia"); ?></option>
                    <option value="escaleras"><?php _e("Escaleras (exteriores e interiores)","camaravalencia"); ?></option>
                    <option value="escaneres_control_multitudes"><?php _e("Escáneres para el control de multitudes","camaravalencia"); ?></option>
                    <option value="pantallas"><?php _e("Pantallas","camaravalencia"); ?></option>
                    <option value="sedes_hospitalidad"><?php _e("Sedes de hospitalidad (terrazas con vistas a las sedes de hospitalidad (terrazas con vistas al campo de regatas con sedes para eventos, hospitalidad de alquiler ","camaravalencia"); ?></option>Privado)
                    <option value="sedes_ubicaciones_eventos"><?php _e("Sedes/ubicaciones para eventos (alquiler privado)","camaravalencia"); ?></option>
                    <option value="servicios_aeroportuarios_privados"><?php _e("Servicios aeroportuarios privados","camaravalencia"); ?></option>
                    <option value="servicios_medioambientales_privados"><?php _e("Servicios medioambientales privados","camaravalencia"); ?></option>
                    <option value="servicios_prevencion_incendios_apoyo_bomberos"><?php _e("Servicios de prevención de incendios y apoyo/bomberos","camaravalencia"); ?></option>
                    <option value="servicios_ciberseguridad_seguridad_informatica"><?php _e("Servicios de ciberseguridad/seguridad informática","camaravalencia"); ?></option>
                    <option value="servicios_helicoptero_alquiler_privado_pilotos_tv"><?php _e("Servicios de helicóptero (alquiler privado), pilotos (para tv)","camaravalencia"); ?></option>
                    <option value="servicios_hidraulica_montaje_banos"><?php _e("Servicios de hidráulica y montaje de baños","camaravalencia"); ?></option>
                    <option value="servicios_diseno_grafico"><?php _e("Servicios de diseño gráfico","camaravalencia"); ?></option>
                    <option value="servicios_limpieza"><?php _e("Servicios de limpieza","camaravalencia"); ?></option>
                    <option value="servicios_contratacion"><?php _e("Servicios de contratación","camaravalencia"); ?></option>
                    <option value="servicios_traduccion"><?php _e("Servicios de traducción","camaravalencia"); ?></option>
                    <option value="servicios_voluntariado"><?php _e("Servicios de voluntariado","camaravalencia"); ?></option>
                    <option value="servicios_guardia_costera"><?php _e("Servicios y asistencia de la guardia costera","camaravalencia"); ?></option>
                    <option value="servicios_medicos"><?php _e("Servicios médicos (hospitales privados, clínicas, médicos)","camaravalencia"); ?></option>
                    <option value="servicios_gestion_invitados_vip"><?php _e("Servicios privados de gestión de invitados vip","camaravalencia"); ?></option>
                    <option value="seguridad"><?php _e("Seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_audiovisuales"><?php _e("Sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas audiovisuales/sistemas de difusión al público (public address)/sistemas de audio","camaravalencia"); ?></option>
                    <option value="sistemas_cctv"><?php _e("Sistemas de cctv y cámaras de seguridad","camaravalencia"); ?></option>
                    <option value="sistemas_control_acceso"><?php _e("Sistemas de control de acceso y recuento","camaravalencia"); ?></option>
                    <option value="sistemas_filtrado_agua"><?php _e("Sistemas de filtrado de agua de lluvia y almacenamiento de agua de lluvia","camaravalencia"); ?></option>
                    <option value="sistemas_paneles_solares"><?php _e("Sistemas de paneles solares/estaciones sistemas de paneles solares/estaciones de recarga solar/pod de recarga solar para teléfonos/paneles solares para las bases de ","camaravalencia"); ?></option>Los Equipos
                    <option value="sistemas_senalizacion"><?php _e("Sistemas de señalización","camaravalencia"); ?></option>
                    <option value="sistemas_aire_acondicionado"><?php _e("Sistemas y unidades de aire acondicionado","camaravalencia"); ?></option>
                    <option value="sistemas_acreditacion"><?php _e("Sistemas de acreditación","camaravalencia"); ?></option>
                    <option value="especialista_materiales_compuestos"><?php _e("Especialista en materiales compuestos","camaravalencia"); ?></option>
                    <option value="especialistas_hidraulica"><?php _e("Especialistas en hidráulica","camaravalencia"); ?></option>
                    <option value="especialistas_despacho_aduanas"><?php _e("Especialistas en despacho de aduanas","camaravalencia"); ?></option>
                    <option value="envios"><?php _e("Envíos","camaravalencia"); ?></option>
                    <option value="impresora_metalica_3d"><?php _e("Impresora metálica 3d","camaravalencia"); ?></option>
                    <option value="almacenamiento_contenedores"><?php _e("Almacenamiento de contenedores","camaravalencia"); ?></option>
                    <option value="estructuras_sombra"><?php _e("Estructuras para crear sombra (grandes y pequeñas)","camaravalencia"); ?></option>
                    <option value="estructuras_temporales"><?php _e("Estructuras temporales","camaravalencia"); ?></option>
                    <option value="soporte_informatico"><?php _e("Soporte informático - proveedores privados","camaravalencia"); ?></option>
                    <option value="alfombras"><?php _e("Alfombras","camaravalencia"); ?></option>
                    <option value="tecnicos_electronica"><?php _e("Técnicos especializados en electrónica","camaravalencia"); ?></option>
                    <option value="ferries_aliscafos"><?php _e("Ferries/aliscafos","camaravalencia"); ?></option>
                    <option value="transfers_helicoptero"><?php _e("Transfers privados vip en helicóptero (del aeropuerto al lugar del evento)","camaravalencia"); ?></option>
                    <option value="transporte_privado"><?php _e("Transporte privado de alquiler","camaravalencia"); ?></option>
                    <option value="transporte_logistica"><?php _e("Transporte de mercancías y logística","camaravalencia"); ?></option>
                    <option value="tribunas"><?php _e("Tribunas","camaravalencia"); ?></option>
                    <option value="unidades_control_radio"><?php _e("Unidades de control de radio/radio","camaravalencia"); ?></option>
                    <option value="unidades_exposicion"><?php _e("Unidades de exposición","camaravalencia"); ?></option>
                    <option value="veleria"><?php _e("Velería","camaravalencia"); ?></option>
                    <option value="videojuegos_gaming"><?php _e("Videojuegos-gaming","camaravalencia"); ?></option>
                    <option value="vino"><?php _e("Vino","camaravalencia"); ?></option>
                    <option value="otros_sectores"><?php _e("Otros sectores","camaravalencia"); ?></option>
                    <option value="servicios_recepcion_hostess"><?php _e("Servicios de recepción hostess","camaravalencia"); ?></option>
                </select>
            <textarea name="venta" placeholder="<?php _e("Productos / Servicios", "camaravalencia"); ?>" required></textarea>
            <textarea name="mensaje" placeholder="<?php _e("Mensaje", "camaravalencia"); ?>" required></textarea>
            <span class="flex flex-row gap-2 items-center">
                <input type="checkbox" name="newsletter" id="newsletter">
                <label for="newsletter"><?php _e("Deseo suscribirme al boletín informativo para recibir las últimas novedades sobre la copa vela.", "camaravalencia"); ?></label>                 
            </span>
            <span class="flex flex-row gap-2 items-center">
                <input type="checkbox" name="legal" id="legal" required>
                <label for="legal"><?php _e("He leído y acepto la", "camaravalencia"); ?> <a target="_blank" href="/politica-de-privacidad"><?php _e("política de privacidad", "camaravalencia"); ?></a>*</label>                 
            </span>

            <button type="submit"><?php _e("Enviar", "camaravalencia"); ?></button>
        </form>



    <?php
        wp_reset_postdata();
    }
}

// El registro del widget se maneja en functions-form.php
