/**
 * Video Hero Scroll Effect - Cámara Valencia
 * Efecto de scroll con máscara de video en el título
 */

(function($) {
    'use strict';

    class VideoHeroScrollEffect {
        constructor(element) {
            this.$widget = $(element);
            this.$container = this.$widget.find('.title-media-scroll-container');
            this.$heading = this.$widget.find('.title-media-heading');
            this.$video = this.$widget.find('.title-media-video-background');
            this.$line = this.$widget.find('.title-media-line');
            
            // Configuración
            this.config = {
                minFontSize: null,         // Se leerá del estilo aplicado por Elementor
                maxFontSize: 100,         // Se calculará para ocupar el ancho
                scrollStart: 1,            // Inicio del scroll
                scrollEnd: 400,            // Final del efecto (reducido para menos scroll)
                fadeInStart: 0,            // Inicio del fade in
                fadeInEnd: 70,             // Final del fade in (más rápido)
                videoFadeStart: 0,         // Inicio del fade out del video
                videoFadeEnd: 40,          // Final del fade out del video (desaparece rápido)
                lineStart: 400,            // Inicio de la línea (cuando termina la animación del título)
                lineEnd: 600,              // Final de la línea
                lineHeight: this.$container.data('line-height') || 3,
                lineColor: this.$container.data('line-color') || '#C81E35'
            };
            
            // Estado
            this.isVideoMask = false;
            this.titleColor = null;
            this.canvas = null;
            this.ctx = null;
            
            this.init();
        }
        
        init() {
            if (this.$heading.length === 0 || this.$video.length === 0) {
                return;
            }
            
            // Calcular el tamaño máximo del texto
            this.calculateMaxFontSize();
            
            // Crear canvas para la máscara de video
            this.setupVideoMask();
            
            // Vincular eventos
            this.bindEvents();
            
            // Ejecutar una vez para establecer el estado inicial
            this.onScroll();
        }
        
        calculateMaxFontSize() {
            // El texto debe ocupar al menos 2 veces el ancho de la pantalla al inicio
            const screenWidth = $(window).width();
            const text = this.$heading.text();
            
            // Estimación: cada carácter ocupa aproximadamente 0.6 de su font-size
            // Multiplicamos por 5 para asegurar que sea al menos 2 veces el ancho de pantalla
            const estimatedFontSize = (screenWidth * 8) / (text.length * 0.6);
            
            // El tamaño máximo será muy grande para el efecto inicial
            this.config.maxFontSize = estimatedFontSize;
        }
        
        getMinFontSize() {
            // Leer el tamaño de fuente aplicado por Elementor
            if (!this.$heading.length) {
                return 48; // Valor por defecto
            }
            
            // Obtener el font-size computado del h1
            const computedFontSize = parseFloat(window.getComputedStyle(this.$heading[0]).fontSize);
            
            // Si no hay un tamaño definido, usar 48px por defecto
            return computedFontSize || 48;
        }
        
        getTitleColor() {
            // Leer el color del título aplicado por Elementor
            if (!this.$heading.length) {
                return '#1E1E1E';
            }
            
            // Crear un elemento temporal para obtener el color real
            const $temp = this.$heading.clone();
            $temp.css({
                '-webkit-text-fill-color': 'initial',
                'background-image': 'none',
                'background-clip': 'initial',
                '-webkit-background-clip': 'initial'
            });
            $temp.css('opacity', '0');
            $temp.appendTo(this.$container);
            
            const color = window.getComputedStyle($temp[0]).color;
            $temp.remove();
            
            return color || '#1E1E1E';
        }
        
        setupVideoMask() {
            if (!this.$video.length || !this.$video[0]) {
                return;
            }
            
            const video = this.$video[0];
            
            // Crear canvas para capturar frames del video
            this.canvas = document.createElement('canvas');
            this.ctx = this.canvas.getContext('2d');
            
            // Esperar a que el video esté listo
            const updateCanvas = () => {
                if (video.readyState >= 2) {
                    this.canvas.width = video.videoWidth || 1920;
                    this.canvas.height = video.videoHeight || 1080;
                    this.updateVideoMask();
                }
            };
            
            video.addEventListener('loadeddata', updateCanvas);
            video.addEventListener('play', () => {
                this.updateVideoMaskLoop();
            });
            
            if (video.readyState >= 2) {
                updateCanvas();
            }
        }
        
        updateVideoMaskLoop() {
            if (!this.isVideoMask || !this.$video[0]) {
                return;
            }
            
            this.updateVideoMask();
            requestAnimationFrame(() => this.updateVideoMaskLoop());
        }
        
        updateVideoMask() {
            if (!this.canvas || !this.ctx || !this.$video[0]) {
                return;
            }
            
            const video = this.$video[0];
            
            try {
                // Dibujar el frame actual del video en el canvas
                this.ctx.drawImage(video, 0, 0, this.canvas.width, this.canvas.height);
                
                // Convertir a data URL y aplicar como background
                const dataUrl = this.canvas.toDataURL('image/jpeg', 0.8);
                this.$heading.css('background-image', `url(${dataUrl})`);
            } catch (e) {
                console.warn('Error al actualizar máscara de video:', e);
            }
        }
        
        bindEvents() {
            $(window).on('scroll.videoHeroEffect', () => this.onScroll());
            $(window).on('resize.videoHeroEffect', () => {
                this.config.minFontSize = null; // Recalcular en resize
                this.calculateMaxFontSize();
                this.onScroll();
            });
        }
        
        onScroll() {
            const scrollTop = $(window).scrollTop();
            const widgetOffset = this.$widget.offset().top;
            const relativeScroll = scrollTop - widgetOffset;
            
            // Obtener el tamaño mínimo de fuente de Elementor
            if (this.config.minFontSize === null) {
                this.config.minFontSize = this.getMinFontSize();
            }
            
            // Obtener el color del título una sola vez
            if (this.titleColor === null) {
                this.titleColor = this.getTitleColor();
            }
            
            // Calcular progreso (0 a 1)
            let progress = (relativeScroll - this.config.scrollStart) / 
                          (this.config.scrollEnd - this.config.scrollStart);
            progress = Math.max(0, Math.min(1, progress));
            
            // Calcular opacidad del texto (fade in)
            let opacity = (relativeScroll - this.config.fadeInStart) / 
                         (this.config.fadeInEnd - this.config.fadeInStart);
            opacity = Math.max(0, Math.min(1, opacity));
            
            // Calcular opacidad del video de fondo (fade out más rápido)
            let videoProgress = (relativeScroll - this.config.videoFadeStart) / 
                               (this.config.videoFadeEnd - this.config.videoFadeStart);
            videoProgress = Math.max(0, Math.min(1, videoProgress));
            const videoBackgroundOpacity = 1 - videoProgress;
            
            // Calcular tamaño de fuente (de máximo a mínimo)
            const fontSize = this.config.maxFontSize - 
                           (progress * (this.config.maxFontSize - this.config.minFontSize));
            
            // Aplicar estilos
            this.$heading.css({
                'font-size': fontSize + 'px',
                'opacity': opacity
            });
            
            // Controlar opacidad del video de fondo
            this.$video.css('opacity', videoBackgroundOpacity);
            
            // Añadir/quitar clase de fading
            if (videoProgress > 0.1) {
                this.$video.addClass('fading');
            } else {
                this.$video.removeClass('fading');
            }
            
            // Controlar si se muestra el video en la máscara o el color
            // Cuando el progreso llega a 1 (animación completa), cambiar a color
            if (progress >= 0.95) {
                // Activar color, desactivar máscara de video
                this.$heading.addClass('color-active');
                this.$heading.css('color', this.titleColor);
                
                if (this.isVideoMask) {
                    this.isVideoMask = false;
                }
            } else if (opacity > 0) {
                // Mostrar máscara de video
                this.$heading.removeClass('color-active');
                
                if (!this.isVideoMask) {
                    this.isVideoMask = true;
                    this.$heading.addClass('has-video-mask');
                    this.$widget.addClass('scroll-effect-active');
                    
                    // Iniciar la reproducción del video
                    if (this.$video[0] && this.$video[0].paused) {
                        this.$video[0].play().catch(() => {});
                    }
                    
                    this.updateVideoMaskLoop();
                }
            } else {
                // Sin opacidad, resetear todo
                this.$heading.removeClass('color-active');
                this.$heading.removeClass('has-video-mask');
                this.$widget.removeClass('scroll-effect-active');
                this.isVideoMask = false;
            }
            
            // Controlar animación de la línea
            if (this.$line.length) {
                let lineProgress = (relativeScroll - this.config.lineStart) / 
                                  (this.config.lineEnd - this.config.lineStart);
                lineProgress = Math.max(0, Math.min(1, lineProgress));
                
                // Aplicar estilos a la línea
                this.$line.css({
                    'width': (lineProgress * 100) + '%',
                    'opacity': lineProgress > 0 ? 1 : 0,
                    'height': this.config.lineHeight + 'px',
                    'background-color': this.config.lineColor
                });
            }
        }
        
        destroy() {
            $(window).off('.videoHeroEffect');
            this.isVideoMask = false;
        }
    }
    
    // Inicializar en widgets de Elementor
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/video_hero.default', function($scope) {
            new VideoHeroScrollEffect($scope.find('.title-media-widget')[0]);
        });
    });
    
    // Inicializar en páginas normales (fuera del editor)
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            $('.title-media-widget').each(function() {
                new VideoHeroScrollEffect(this);
            });
        }
    });
    
})(jQuery);
