/**
 * JavaScript para el Widget de Áreas - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Inicializar funcionalidad del widget de áreas
     */
    function initAreasWidget() {
        
        // Manejar el menú responsive
        handleResponsiveMenu();
        
        // Manejar acordeones en móvil
        handleAccordionToggle();
        
        // Manejar hover en desktop
        handleDesktopHover();
    }

    /**
     * Manejar el menú responsive (hamburguesa)
     */
    function handleResponsiveMenu() {
        $(document).on('click', '#areas-nav-icon', function() {
            const $menuList = $(this).closest('.areas-widget').find('.areas-list');
            
            // Toggle de la clase active
            $menuList.toggleClass('active');
            
            // Toggle del icono hamburguesa
            $(this).toggleClass('active');
        });

        // Cerrar menú al hacer click fuera
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.areas-widget').length) {
                $('.areas-list').removeClass('active');
                $('#areas-nav-icon').removeClass('active');
            }
        });
    }

    /**
     * Manejar acordeones en móvil
     */
    function handleAccordionToggle() {
        $(document).on('click', '.areas-widget .accordion-toggle-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $megawrap = $(this).siblings('.megawrap');
            const $arrow = $(this).find('.arrow-css');
            
            // Toggle del submenú
            $megawrap.toggleClass('active');
            
            // Rotar la flecha
            if ($megawrap.hasClass('active')) {
                $arrow.css('transform', 'rotate(225deg)');
            } else {
                $arrow.css('transform', 'rotate(45deg)');
            }
            
            // Cerrar otros submenús abiertos
            $('.areas-widget .megawrap').not($megawrap).removeClass('active');
            $('.areas-widget .arrow-css').not($arrow).css('transform', 'rotate(45deg)');
        });
    }

    /**
     * Manejar hover en desktop
     */
    function handleDesktopHover() {
        // Solo aplicar hover en pantallas grandes
        function checkScreenSize() {
            if (window.innerWidth > 768) {
                // Habilitar hover para desktop
                $('.areas-widget .areas-list > li').off('mouseenter mouseleave').hover(
                    function() {
                        // Mouse enter
                        $(this).find('.megawrap').addClass('desktop-hover');
                    },
                    function() {
                        // Mouse leave
                        $(this).find('.megawrap').removeClass('desktop-hover');
                    }
                );
            } else {
                // Deshabilitar hover para móvil
                $('.areas-widget .areas-list > li').off('mouseenter mouseleave');
                $('.areas-widget .megawrap').removeClass('desktop-hover');
            }
        }

        // Ejecutar al cargar y al redimensionar
        checkScreenSize();
        $(window).on('resize', debounce(checkScreenSize, 250));
    }

    /**
     * Función debounce para optimizar eventos de resize
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Manejar animaciones suaves para submenús
     */
    function handleSmoothAnimations() {
        // Agregar clases CSS adicionales para animaciones más suaves
        $('.areas-widget .megawrap').each(function() {
            $(this).on('transitionend', function() {
                if (!$(this).hasClass('active') && !$(this).hasClass('desktop-hover')) {
                    $(this).css('display', 'none');
                }
            });
        });
    }

    /**
     * Funciones de utilidad para accesibilidad
     */
    function enhanceAccessibility() {
        // Agregar atributos ARIA para mejor accesibilidad
        $('.areas-widget .areas-list > li').each(function() {
            const $item = $(this);
            const $submenu = $item.find('.megawrap');
            const $toggle = $item.find('.accordion-toggle-btn');
            
            if ($submenu.length > 0) {
                const submenuId = 'areas-submenu-' + Math.random().toString(36).substr(2, 9);
                
                $submenu.attr({
                    'id': submenuId,
                    'aria-hidden': 'true'
                });
                
                $toggle.attr({
                    'aria-controls': submenuId,
                    'aria-expanded': 'false'
                });
                
                // Actualizar atributos al abrir/cerrar
                $toggle.on('click', function() {
                    const isExpanded = $submenu.hasClass('active');
                    $toggle.attr('aria-expanded', isExpanded ? 'true' : 'false');
                    $submenu.attr('aria-hidden', isExpanded ? 'false' : 'true');
                });
            }
        });

        // Manejar navegación por teclado
        $('.areas-widget .areas-list a, .areas-widget .accordion-toggle-btn').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
    }

    /**
     * Inicializar cuando el DOM esté listo
     */
    $(document).ready(function() {
        initAreasWidget();
        handleSmoothAnimations();
        enhanceAccessibility();
    });

    /**
     * Reinicializar cuando se cargue contenido dinámico (Elementor, AJAX, etc.)
     */
    $(window).on('elementor/frontend/init', function() {
        setTimeout(initAreasWidget, 100);
    });

    // También escuchar eventos de Elementor si están disponibles
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/areas.default', function($scope) {
            // Reinicializar solo para este widget específico
            const $widget = $scope.find('.areas-widget');
            if ($widget.length) {
                // Aplicar funcionalidad específica a este widget
                initAreasWidget();
            }
        });
    }

})(jQuery);