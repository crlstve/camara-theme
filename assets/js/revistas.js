/**
 * JavaScript para el Card Slider de Revistas - Cámara Valencia
 * 
 * @package Cámara Valencia
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Ejecutar cuando el DOM esté listo
    $(document).ready(function() {
        initRevistasCardSlider();
    });

    /**
     * Inicializar funcionalidad del card slider de revistas
     */
    function initRevistasCardSlider() {
        console.log('Inicializando Card Slider de Revistas...');
        
        $('.revistas-card-slider').each(function() {
            const $slider = $(this);
            const $cardsStack = $slider.find('.cards-stack');
            const $nextBtn = $slider.find('.next-btn');
            const $prevBtn = $slider.find('.prev-btn');
            const $indicators = $slider.find('.indicator');
            
            // Función para obtener todas las cartas (actualizada)
            function getCards() {
                return $cardsStack.find('.revista-card');
            }
            
            let $cards = getCards();
            console.log('Cards encontradas:', $cards.length);
            
            if ($cards.length <= 1) {
                console.log('No hay suficientes cartas para el slider');
                return;
            }
            
            let currentIndex = 0;
            let isTransitioning = false;
            
            // Función para actualizar posiciones de las cartas
            function updateCardPositions() {
                $cards = getCards(); // Actualizar referencia
                $cards.each(function(index) {
                    const $card = $(this);
                    $card.attr('data-index', index);
                });
            }
            
            // Función para mover al siguiente
            function moveNext() {
                if (isTransitioning) return;
                
                console.log('Moviendo a siguiente...');
                isTransitioning = true;
                
                $cards = getCards(); // Actualizar referencia antes de usar
                const $firstCard = $cards.first();
                
                // Animar la primera carta hacia atrás y arriba a la derecha
                $firstCard.css({
                    'transform': 'translateX(100px) translateY(-80px) rotateY(-15deg) scale(0.85)',
                    'opacity': '0.6',
                    'transition': 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)'
                });
                
                setTimeout(() => {
                    // Mover la carta al final del DOM
                    $cardsStack.append($firstCard);
                    
                    // Resetear el estilo de la carta
                    $firstCard.css({
                        'transform': '',
                        'opacity': '',
                        'transition': ''
                    });
                    
                    // Actualizar referencias e índice
                    $cards = getCards();
                    currentIndex = (currentIndex + 1) % $cards.length;
                    
                    updateCardPositions();
                    updateIndicators();
                    
                    isTransitioning = false;
                    console.log('Animación siguiente completada');
                }, 600);
            }
            
            // Función para mover al anterior
            function movePrev() {
                if (isTransitioning) return;
                
                console.log('Moviendo a anterior...');
                isTransitioning = true;
                
                $cards = getCards(); // Actualizar referencia antes de usar
                const $lastCard = $cards.last();
                
                // Obtener la posición actual de la carta (su índice en el stack)
                const currentCardIndex = $cards.length - 1;
                
                // Calcular la posición inicial basada en su posición actual en el stack
                const initialTranslateX = -80 + (currentCardIndex * 20); // Más separación horizontal
                const initialTranslateY = 60 - (currentCardIndex * 15); // Partir desde su altura actual
                const initialRotateY = 15 - (currentCardIndex * 3); // Rotación progresiva
                
                // Preparar la carta desde su posición actual en el stack
                $lastCard.css({
                    'transform': `translateX(${initialTranslateX}px) translateY(${initialTranslateY}px) rotateY(${initialRotateY}deg) scale(1.05)`,
                    'transition': 'none'
                });
                
                // Mover al principio del DOM
                $cardsStack.prepend($lastCard);
                
                // Pequeño delay para que el DOM se actualice
                setTimeout(() => {
                    // Aplicar transición y animar a posición normal (primera posición)
                    $lastCard.css({
                        'transition': 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                        'transform': 'translateX(0) translateY(0) rotateY(0deg) scale(1)'
                    });
                    
                    setTimeout(() => {
                        // Limpiar estilos de transición
                        $lastCard.css({
                            'transition': '',
                            'transform': ''
                        });
                        
                        // Actualizar referencias e índice
                        $cards = getCards();
                        currentIndex = (currentIndex - 1 + $cards.length) % $cards.length;
                        
                        updateCardPositions();
                        updateIndicators();
                        
                        isTransitioning = false;
                        console.log('Animación anterior completada');
                    }, 600);
                }, 50);
            }
            
            // Función para actualizar indicadores
            function updateIndicators() {
                $indicators.removeClass('active');
                $indicators.eq(currentIndex).addClass('active');
            }
            
            // Event listeners para los botones
            $nextBtn.on('click', function(e) {
                e.preventDefault();
                console.log('Click en botón siguiente');
                moveNext();
            });
            
            $prevBtn.on('click', function(e) {
                e.preventDefault();
                console.log('Click en botón anterior');
                movePrev();
            });
            
            // Event listeners para los indicadores
            $indicators.on('click', function(e) {
                e.preventDefault();
                const targetIndex = parseInt($(this).data('slide'));
                console.log('Click en indicador:', targetIndex);
                
                if (targetIndex === currentIndex || isTransitioning) return;
                
                // Calcular la diferencia más corta
                const totalCards = $cards.length;
                let steps = targetIndex - currentIndex;
                
                if (steps < 0) steps += totalCards;
                
                // Función recursiva para animar hacia el objetivo
                function animateToTarget() {
                    if (currentIndex === targetIndex || isTransitioning) return;
                    
                    moveNext();
                    
                    // Continuar después de que termine la animación
                    setTimeout(animateToTarget, 700);
                }
                
                if (steps > 0) animateToTarget();
            });
            
            // Inicializar
            updateCardPositions();
            updateIndicators();
            
            console.log('Card Slider inicializado correctamente');
        });
    }

})(jQuery);