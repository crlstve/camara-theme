<?php


$_conf = Array();

$_conf["ws_formacion"]["getdatoscurso"] = "http://ws.camaravalencia.com/Formacion/WS_Formacion.asmx/getDatosCursoGlobal";
if (!defined("ICL_LANGUAGE_CODE")) define("ICL_LANGUAGE_CODE", "es");
$_conf["ws_formacion"]["getcursos"] = "http://ws.camaravalencia.com/formacion/WS_Formacion.asmx/getCursosGlobal";

$_conf["ws_agenda"]["getentradaagendaglobal"] = "http://ws.camaravalencia.com/Agenda/WS_Agenda.asmx/getEntradaAgendaGlobal";


if (ICL_LANGUAGE_CODE == "es") {
	$_conf["ids_paginas"]["solicita_admision_curso"] = 7343;
	$_conf["ids_paginas"]["politica_privacidad"] = 3;	
	$_conf["ids_paginas"]["agenda_actividades"] = 8540;		
}
else {
	$_conf["ids_paginas"]["solicita_admision_curso"] = 0;	
	$_conf["ids_paginas"]["politica_privacidad"] = 0;
	$_conf["ids_paginas"]["agenda_actividades"] = 14495;	
}

$_conf["links"]["te_llamamos"] = "https://calendly.com/escuelanegocios/te-llamamos";

$_textos = Array(

	"t_cursos_consultar" => __("Consultar","camaravalencia"),
	"t_cursos_gratuito" => __("Gratuito","camaravalencia")

);