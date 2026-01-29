/* Widget Agenda / Noticias - JavaScript */

let splideInstances = {};

function initSliders() {
    
    // Verificar que Splide esté disponible
    if (typeof Splide === 'undefined') {
        console.error('Splide no está disponible');
        return;
    }
    
    const sliders = ['#agenda-slide', '#noticias-slide'];
    
    sliders.forEach(sliderId => {
        const slider = document.querySelector(sliderId);
        
        if (!slider) {
    
            return;
        }
        
        // Destruir instancia anterior si existe
        if (splideInstances[sliderId]) {
            splideInstances[sliderId].destroy();
            delete splideInstances[sliderId];
        }
        
        // Asegurar visibilidad
        slider.style.visibility = 'visible';
        
        if (window.innerWidth < 768) {
            // Móvil: Inicializar Splide
            
            try {
                splideInstances[sliderId] = new Splide(slider, {
                    gap: 20,
                    padding: { left: 12, right: 12 },
                    perPage: 1,
                    type: 'loop',
                    arrows: true,
                    pagination: true
                });
                
                splideInstances[sliderId].mount();
                
            } catch (error) {
                console.error(`Error al inicializar Splide para ${sliderId}:`, error);
            }
            
        } else {
            // Desktop: Configurar como grid
            
            const track = slider.querySelector('.splide__track');
            const list = slider.querySelector('.splide__list');
            
            if (track) {
                track.style.overflow = 'visible';
                track.style.position = 'static';
            }
            if (list) {
                list.style.display = 'grid';
                list.style.transform = 'none';
            }
        }
    });
}

function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    const tabSubtitles = document.querySelectorAll('.section-subtitle');
    const tabSlider = document.querySelector('.tab-slider');

    if (!tabButtons.length || !tabContents.length || !tabSlider) return;

    // Función para cambiar tab
    function switchTab(targetTab, buttonIndex) {
        // Remover active de todos los botones y contenidos
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        tabSubtitles.forEach(subtitle => subtitle.classList.remove('active'));
        
        // Activar el botón y contenido seleccionado
        tabButtons[buttonIndex].classList.add('active');
        document.getElementById(targetTab).classList.add('active');
        
        // Activar el subtítulo correspondiente
        if (tabSubtitles[buttonIndex]) {
            tabSubtitles[buttonIndex].classList.add('active');
        }
        
        // Mover el slider
        const buttonWidth = tabButtons[buttonIndex].offsetWidth;
        const buttonLeft = tabButtons[buttonIndex].offsetLeft - 4;
        tabSlider.style.width = buttonWidth + 'px';
        tabSlider.style.transform = `translateX(${buttonLeft}px)`;
        
        // Reinicializar sliders para el contenido activo
        setTimeout(() => {
            initSliders();
        }, 300);
    }
    
    // Agregar event listeners a los botones
    tabButtons.forEach((button, index) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetTab = button.getAttribute('data-tab');
            switchTab(targetTab, index);
        });
    });
    
    // Inicializar posición del slider en el primer botón
    if (tabButtons[0]) {
        tabSlider.style.width = tabButtons[0].offsetWidth + 'px';
        //tabSlider.style.transform = 'translateX(-4px)';
    }
}



document.addEventListener('DOMContentLoaded', function() {
    initSliders();
    initTabs();
});

// Manejar resize
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => { initSliders(); }, 150);
});