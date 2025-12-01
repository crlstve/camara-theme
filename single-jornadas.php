<?php
/**
 * Plantilla para mostrar un solo post de Revistas - Cámara Valencia
 * @package Cámara Valencia
 * @since 1.0.0
 */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    get_header();
            // Campos básicos
                $subtitulo = get_field('jornadas_subtitulo');
                $objetivo = get_field('jornadas_objetivos');
                $target = get_field('jornadas_dirigidoa');
            // Campos de fecha y hora
                $hora_inicio = get_field('jornadas_horainicio');
                $hora_fin = get_field('jornadas_horafin');
                $hora_desc = get_field('jornadas_horariodescrip');
                $fecha_inicio = get_field('jornadas_fechainicio');
                $fecha_fin = get_field('jornadas_fechafin');
                $duracion = get_field('jornadas_duracion');
            // Campos de lugar y contacto
                $lugar = get_field('jornadas_lugar');
                $lugar_desc = get_field('jornadas_lugardescrip');
                $contacto = get_field('jornadas_contacto');
                $tipo_jornada = get_field('jornadas_tipojornada');
            // Campos adicionales que faltaban
                $programa_pie = get_field('jornadas_programapie');
                $imagen_banner = get_field('jornadas_imagenbanner');
                $imagen_mailing = get_field('jornadas_imagenmailing');
                $pdf_programa = get_field('jornadas_pdfprograma');
                $mas_informacion = get_field('jornadas_masinformacion');
                $precio_descrip = get_field('jornadas_preciodescrip');
                $url_externa = get_field('jornadas_urlexterna');
                $url_evento_manual = get_field('jornadas_urleventomanual');
            // Campos checkbox/opciones
                $exclusivo_club = get_field('jornadas_exclusivoclub');
                $programa_con_horario = get_field('jornadas_programaconhorario');
                $agenda_tic = get_field('jornadas_agendatic');
                $agenda_sostenibilidad = get_field('jornadas_agendasostenibilidad');
                $agenda_internacional = get_field('jornadas_agendainternacional');
                $agenda_ayudas = get_field('jornadas_agendaayudas');
                $agenda_rse = get_field('jornadas_agendarse');
            // Campos adicionales
                $id_proyecto_crm = get_field('jornadas_idproyectocrm');
                $tags = get_field('jornadas_tags');
                $pais_destino = get_field('jornadas_paisdestino');
            // Repetidores
                $patrocinadores = get_field('jornadas_patrocinadores');
                $programa_fila = get_field('programa_fila');

            // Taxonomías
                $patrocinadores_tax = wp_get_post_terms( get_the_ID(), 'patrocinadorjornada' );

                // Colores
                    if($agenda_tic){
                        $bg_color = '#F5FAFE';
                        $color = '#2EA5DA';
                    } elseif ($agenda_sostenibilidad) {
                        $bg_color = '#F3F8F6';
                        $color = '#046244';
                    } elseif ($agenda_internacional) {
                        $bg_color = '#FFF5F8';
                        $color = '#FC2366';
                    } elseif ($agenda_ayudas) {
                        $bg_color = '#fff9e7';
                        $color = '#fecf49';
                    } else {
                        $bg_color = '#F4F5FC';
                        $color = '#BA0727';   
                    }

                    // Usa el web service para obtener datos adicionales del evento
                     $datos_agenda_ws = get_info_ws_agenda_global($id_proyecto_crm, ICL_LANGUAGE_CODE);
                    // Si el evento es exclusivo para el club, redirige a la página externa
                    $id_jornada_crm_club = es_jornada_club(get_the_ID());

?>
<style>
    :root{
        --bg-color-jornada: <?= $bg_color ?>;
        --color-jornada: <?= $color ?>;
    }
</style>

    <main>

        <header role="hero-image" class="elementor-section elementor-section-boxed single-hero relative" style="background-color:<?= $bg_color ?>;">

            <div class="elementor-container mx-auto flex flex-col-reverse lg:flex-row justify-center gap-2 lg:gap-6 lg:min-h-96">

                <div class="w-full md:w-1/2 px-6 pt-12 pb-24 single-hero-content">

                    <?php if($tipo_jornada): ?>
                        
                        <span class="text-2xl"><?= get_term($tipo_jornada, 'tipojornada')->name; ?></span>

                    <?php endif; ?>

                    <h1 style="color:<?= $color ?>;" class="pb-12 mt-0"><?php the_title(); ?></h1>

                    <div class="my-6">

                        <?php if($duracion): ?>
                            
                            <span><?= $duracion ?> h</span>

                        <?php endif; ?>

                        <?php if($precio_descrip): ?>

                            <span style="border-left: 1px solid #ccc; padding-left: 6px;"><?= $precio_descrip ?></span>
                        
                        <?php else: ?>

                            <span style="border-left: 1px solid #ccc; padding-left: 6px;"><?php _e('Gratuito', 'camaravalencia'); ?></span>

                        <?php endif; ?>

                            

                    </div>
                    
                    <?php if ($url_externa): ?>

                        <div>
                            <a role="button" class="single-hero-button px-6 py-3 rounded-full" href="<?= esc_url($url_externa); ?>" target="_blank" rel="noopener noreferrer">
                                <?php _e('Inscripción', 'camaravalencia'); ?>
                            </a>
                        </div>

                    <?php elseif (!empty($datos_agenda_ws) && $datos_agenda_ws["Plazas_Vacantes"] > 0): ?>

                        <div class="flex flex-row gap-4">

                            <?php if($datos_agenda_ws["UrlInscripcion"] && ($datos_agenda_ws["P_Streaming"] != "3")): ?>

                                    <a role="button" class="single-hero-button px-6 py-3 rounded-full" href="<?= esc_url($datos_agenda_ws["UrlInscripcion"]); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php esc_html_e('Inscripción', 'camaravalencia'); ?>
                                    </a>

                            <?php endif; ?>

                            <?php if( $datos_agenda_ws["UrlInscripcionStreaming"] && ($datos_agenda_ws["P_Streaming"] == "3" || $datos_agenda_ws["P_Streaming"] == "1")): ?> 

                                    <a role="button" class="single-hero-button px-6 py-3 rounded-full" href="<?= esc_url($datos_agenda_ws["UrlInscripcionStreaming"]); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php esc_html_e('Inscripción Online', 'camaravalencia'); ?>
                                    </a>

                            <?php endif; ?>

                        </div>
                    

                    <?php elseif ($datos_agenda_ws["UrlListaEspera"] && !$id_jornada_crm_club): ?>

                        <div>  

                            <a role="button" class="single-hero-button px-6 py-3 rounded-full" href="<?= esc_url($datos_agenda_ws["UrlListaEspera"]); ?>" target="_blank" rel="noopener noreferrer">
                                <?php _e('Lista de espera', 'camaravalencia'); ?>
                            </a>

                        </div>

                    <?php endif; ?> 

                </div>

                <div class="w-full md:w-1/2">

                        <img src="<?= esc_url( $imagen_banner ); ?>" alt="<?= esc_attr( the_title() ); ?>" class="object-cover" style="height: 100%;"/>

                </div>

            </div>



        </header>

        <section class="relative elementor-section elementor-section-boxed mx-auto pt-24 pb-18 px-6" >

            <div class="absolute top-0 left-0 right-0 -translate-y-5 mx-auto py-3 px-6 w-10/12 lg:w-fit overflow-hidden bg-white rounded-xl shadow-lg elementor-sticky elementor-sticky--active elementor-section--handles-inside elementor-sticky--effects"">
                
                <ul class="flex flex-row flex-wrap lg:flex-nowrap gap-3">
                    
                    <?php if ($fecha_inicio): ?>

                        <li class="flex flex-row gap-1 h-fit">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="19" height="16" rx="2" stroke="#bfc3cc" stroke-width="2"/>
                                <path d="M8 2v4M17 2v4" stroke="#bfc3cc" stroke-width="2" stroke-linecap="round"/>
                                <path d="M3 10h19" stroke="#bfc3cc" stroke-width="2"/>
                                <path d="M7 14h2v2H7zM11 14h2v2h-2zM15 14h2v2h-2z" fill="#bfc3cc"/>
                            </svg>
                            <?= $fecha_inicio ?>
                        </li>

                    <?php endif; ?>

                    <?php if ($hora_inicio || $hora_fin || $hora_desc): ?>
                        <li class="flex flex-row gap-1 h-fit">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12.5" cy="12.5" r="9.5" stroke="#bfc3cc" stroke-width="2"/>
                                <path d="M12.5 7.5V12.5L16 16" stroke="#bfc3cc" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <?php if ($hora_desc): ?>
                                <?= $hora_desc ?>
                            <?php elseif ($hora_inicio && $hora_fin): ?>
                                <?= $hora_inicio ?> - <?= $hora_fin ?>
                            <?php elseif ($hora_inicio): ?>
                                <?= $hora_inicio ?>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>

                    <?php if ($lugar || $lugar_desc): ?>

                        <li class="flex flex-row gap-1 h-fit z-10">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.5 2.5C8.5 2.5 5.5 5.5 5.5 9.5C5.5 14.5 12.5 22.5 12.5 22.5C12.5 22.5 19.5 14.5 19.5 9.5C19.5 5.5 16.5 2.5 12.5 2.5Z" stroke="#bfc3cc" stroke-width="2"/>
                                <circle cx="12.5" cy="9.5" r="3" stroke="#bfc3cc" stroke-width="2"/>
                            </svg>
                            <?php if ($lugar_desc): ?>
                                <?= $lugar_desc ?>
                            <?php else: ?>
                                <?= wp_strip_all_tags(get_term($lugar, 'lugar')->name); ?>
                            <?php endif; ?>
                        </li>

                    <?php endif; ?>

                </ul>

            </div>

            <div class="elementor-container gap-10">

                <div class="w-full lg:w-5/12">

                    <?php if($objetivo): ?>

                        <article class="camara-article">

                            <h2 class="camara-title"><?php _e('Objetivos', 'camaravalencia'); ?></h2>    

                            <?= $objetivo; ?>

                        </article>

                    <?php endif; ?>

                    <?php if($target): ?>

                        <article class="camara-article">

                            <h2 class="camara-title"><?php _e('Dirigido a', 'camaravalencia'); ?></h2>  

                            <?= $target; ?>

                        </article>

                    <?php endif; ?>

                    <?php if($mas_informacion): ?>

                        <article class="camara-article">

                            <h2 class="camara-title"><?php _e('Más información', 'camaravalencia'); ?></h2>  

                            <?= $mas_informacion; ?>

                        </article>

                    <?php endif; ?>

                </div>


                <div class="w-full lg:w-5/12">

                    <?php if($programa_con_horario && $programa_fila): ?>

                        <section class="camara-section">

                            <h2 class="camara-title"><?php _e('Programa', 'camaravalencia'); ?></h2>                            

                            <ul class="w-full border-collapse mt-6 pt-4 flex flex-col gap-6">
                                    <?php foreach($programa_fila as $fila): ?>

                                        <li class="grid grid-cols-12 gap-3 pb-2">
                                            
                                            <div class="col-span-3">
                                                <time  class="align-top px-4 mx-auto py-2 rounded-full" style="color: <?= $color ?>;background-color: <?= $bg_color ?>;">
                                                    <?php 
                                                        // Formatear la hora al formato HH:MM
                                                        $hora_raw = $fila['programa_fila_hora'];
                                                        // Si ya está en formato HH:MM, mostrarlo tal como está
                                                        if (preg_match('/^\d{1,2}:\d{2}$/', $hora_raw)) {
                                                            // Asegurar formato HH:MM (con cero a la izquierda si es necesario)
                                                            $time_parts = explode(':', $hora_raw);
                                                            $hora_formateada = sprintf('%02d:%02d', intval($time_parts[0]), intval($time_parts[1]));
                                                        } 
                                                        // Si es un timestamp o fecha/hora completa, extraer solo la hora
                                                        else if (strtotime($hora_raw) !== false) {
                                                            $hora_formateada = date('H:i', strtotime($hora_raw));
                                                        }
                                                        // Si es solo números (ej: 1900 para 19:00)
                                                        else if (preg_match('/^\d{3,4}$/', $hora_raw)) {
                                                            $hora_num = str_pad($hora_raw, 4, '0', STR_PAD_LEFT);
                                                            $hora_formateada = substr($hora_num, 0, 2) . ':' . substr($hora_num, 2, 2);
                                                        }
                                                        // Si no se puede formatear, mostrar tal como está
                                                        else {
                                                            $hora_formateada = $hora_raw;
                                                        }
                                                        echo $hora_formateada;
                                                    ?>
                                                </time> 
                                            </div>

                                            <div class="col-span-9">   
                                                <p class="font-semibold">
                                                    <?= $fila['programa_fila_descripcion_1']; ?>
                                                </p>
                                                
                                                <?php
                                                    if ($fila['programa_fila_descripcion_2']) :

                                                        echo $fila['programa_fila_descripcion_2']; 
  
                                                    endif;
                                                    if ($fila['programa_fila_descripcion_3']) :

                                                        echo $fila['programa_fila_descripcion_3']; 

                                                    endif;
                                                ?>
                                     
                                            </div>
                                            
                                        </li>

                                    <?php endforeach; ?>
                            </ul>
                        <section class="camara-section">

                    <?php endif; ?>

                    <?php if ($pdf_programa): ?>

                        <div class="camara-button-container mt-12 flex justify-center">
                            <a role="button" class="single-hero-button" href="<?= esc_url($pdf_programa); ?>" target="_blank" rel="noopener noreferrer">
                                <?php _e('Descargar programa', 'camaravalencia'); ?>
                            </a>
                        </div>

                    <?php endif; ?>

                </div>

    
            </div>

        </section>


        <section class="w-full bg-(--e-global-color-text) py-1 elementor-section elementor-section-boxed">

            <div class="elementor-container mx-auto text-center text-white flex flex-col md:flex-row gap-8 justify-around py-24">

                <?php 
                    // plugins/camara_jornadas/inc/funciones.php =>
                    $patrocinadores_html = patrocinadores_jornadas(null, true);
                    if ($patrocinadores_html):
                        echo $patrocinadores_html;
                    endif;
                ?>

            </div>

        </section>
     
        <?php 
            // plugin camara_formularios/inc/shortcodes.php
             echo formulario_contacto_refactor() 
        ?>

    </main>

    <?php get_footer(); ?>