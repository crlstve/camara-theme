document.addEventListener('DOMContentLoaded', function() {
    function scrollMegamenu() {
        const megamenu = document.querySelectorAll('.megawrap');
        const scrollPosition = window.scrollY;

        if (scrollPosition > 64) {
            megamenu.forEach(item => item.classList.add('scrolled'));
        } else {
            megamenu.forEach(item => item.classList.remove('scrolled'));
        }
    }
    window.addEventListener('scroll', scrollMegamenu);

    // Funcionalidad del menú móvil con acordeón
    const navIcon = document.querySelector('#mega-nav-icon');
    if (navIcon) {
        navIcon.addEventListener('click', function() {
            navIcon.classList.toggle('open');
            const menuItems = document.querySelectorAll('ul#menu-principal > li:not(#mega-responsive), ul#menu-megamenu-es > li:not(#mega-responsive), ul#menu-megamenu-val > li:not(#mega-responsive)');
            const menu = document.querySelector('ul#menu-principal, ul#menu-megamenu-es, ul#menu-megamenu-val');
            
            if (menu.classList.contains('open')) {
                // Cerrar menú
                menu.classList.remove('open');
                menuItems.forEach(item => {
                    item.style.display = 'none';
                    // Cerrar todos los acordeones al cerrar el menú
                    item.classList.remove('accordion-open');
                    const subItems = item.querySelectorAll('.menu-item-has-children');
                    subItems.forEach(subItem => subItem.classList.remove('accordion-open'));
                    
                    // Resetear todos los botones
                    const buttons = item.querySelectorAll('.accordion-toggle-btn');
                    buttons.forEach(button => {
                        button.classList.remove('open');
                        button.setAttribute('aria-label', 'Expandir menú');
                    });
                });
            } else {
                // Abrir menú
                menu.classList.add('open');
                menuItems.forEach(item => {
                    item.style.display = 'flex';
                });
            }
        });
    }

    // Manejador para acordeón de primer nivel
    function handleFirstLevelAccordion(e) {
        if (window.innerWidth <= 1024) {
            e.preventDefault();
            e.stopPropagation();
            
            // Asegurar que obtenemos el botón correcto, incluso si se hace click en arrow-css
            const button = e.target.closest('.accordion-toggle-btn') || e.target;
            const parentLi = button.closest('li');
            const megawrap = parentLi.querySelector('.megawrap');
            
            if (megawrap) {
                const isCurrentlyOpen = parentLi.classList.contains('accordion-open');
                
                // Cerrar TODOS los acordeones primero
                const allFirstLevelAccordions = document.querySelectorAll('ul#menu-principal > li.menu-item-has-children:not(#mega-responsive), ul#menu-megamenu-es > li.menu-item-has-children:not(#mega-responsive), ul#menu-megamenu-val > li.menu-item-has-children:not(#mega-responsive)');
                allFirstLevelAccordions.forEach(accordion => {
                    accordion.classList.remove('accordion-open');
                    const accordionButton = accordion.querySelector('.accordion-toggle-btn');
                    if (accordionButton) {
                        accordionButton.classList.remove('open');
                        accordionButton.setAttribute('aria-label', 'Expandir menú');
                    }
                    // También cerrar todos los subacordeones
                    const subAccordions = accordion.querySelectorAll('.menu-item-has-children');
                    subAccordions.forEach(subAccordion => {
                        subAccordion.classList.remove('accordion-open');
                        const subButton = subAccordion.querySelector('.accordion-toggle-btn');
                        if (subButton) {
                            subButton.classList.remove('open');
                            subButton.setAttribute('aria-label', 'Expandir menú');
                        }
                    });
                });
                
                // Si no estaba abierto, abrir solo el actual
                if (!isCurrentlyOpen) {
                    parentLi.classList.add('accordion-open');
                    button.classList.add('open');
                    button.setAttribute('aria-label', 'Colapsar menú');
                }
            }
        }
    }

    // Manejador para acordeón de segundo nivel
    function handleSecondLevelAccordion(e) {
        if (window.innerWidth <= 1024) {
            e.preventDefault();
            e.stopPropagation();
            
            // Asegurar que obtenemos el botón correcto, incluso si se hace click en arrow-css
            const button = e.target.closest('.accordion-toggle-btn') || e.target;
            const parentLi = button.closest('li');
            const megawrap = parentLi.querySelector('.megawrap');
            
            if (megawrap) {
                const isCurrentlyOpen = parentLi.classList.contains('accordion-open');
                
                // Cerrar todos los acordeones de segundo nivel en el contenedor actual
                const parentMegawrap = parentLi.closest('.megawrap');
                if (parentMegawrap) {
                    const allSecondLevelAccordions = parentMegawrap.querySelectorAll('.menu-item-has-children');
                    allSecondLevelAccordions.forEach(accordion => {
                        accordion.classList.remove('accordion-open');
                        const accordionButton = accordion.querySelector('.accordion-toggle-btn');
                        if (accordionButton) {
                            accordionButton.classList.remove('open');
                            accordionButton.setAttribute('aria-label', 'Expandir menú');
                        }
                    });
                }
                
                // Si no estaba abierto, abrir solo el actual
                if (!isCurrentlyOpen) {
                    parentLi.classList.add('accordion-open');
                    button.classList.add('open');
                    button.setAttribute('aria-label', 'Colapsar menú');
                }
            }
        }
    }
    // Manejador para acordeón de segundo nivel
    function handleSecondLevelAccordion(e) {
            e.preventDefault();
            e.stopPropagation();

            // Asegurar que obtenemos el botón correcto, incluso si se hace click en arrow-css
            const button = e.target.closest('.accordion-toggle-btn') || e.target;
            const parentLi = button.closest('li');
            const megawrap = parentLi.querySelector('.megawrap');

            if (megawrap) {
                const isCurrentlyOpen = parentLi.classList.contains('accordion-open');

                // Cerrar todos los acordeones de segundo nivel en el contenedor actual
                const parentMegawrap = parentLi.closest('.megawrap');
                if (parentMegawrap) {
                    const allSecondLevelAccordions = parentMegawrap.querySelectorAll('.menu-item-has-children');
                    allSecondLevelAccordions.forEach(accordion => {
                        accordion.classList.remove('accordion-open');
                        const accordionButton = accordion.querySelector('.accordion-toggle-btn');
                        if (accordionButton) {
                            accordionButton.classList.remove('open');
                            accordionButton.setAttribute('aria-label', 'Expandir menú');
                        }
                    });
                }

                // Si no estaba abierto, abrir solo el actual
                if (!isCurrentlyOpen) {
                    parentLi.classList.add('accordion-open');
                    button.classList.add('open');
                    button.setAttribute('aria-label', 'Colapsar menú');
                }
            }
    }
    // Función para limpiar el estado de los botones de acordeón
    function cleanAccordionButtons() {
        const allButtons = document.querySelectorAll('.accordion-toggle-btn');
        allButtons.forEach(button => {
            button.classList.remove('open');
            button.setAttribute('aria-label', 'Expandir menú');
        });
    }

    // Configurar event listeners para acordeones
    // Botones de primer nivel (directamente en #menu-principal)
    const firstLevelButtons = document.querySelectorAll('ul#menu-principal > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn, ul#menu-megamenu-es > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn, ul#menu-megamenu-val > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn');
    firstLevelButtons.forEach(button => {
        button.addEventListener('click', handleFirstLevelAccordion);
    });

    // Event listeners adicionales para arrow-css de primer nivel
    const firstLevelArrows = document.querySelectorAll('ul#menu-principal > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn .arrow-css, ul#menu-megamenu-es > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn .arrow-css, ul#menu-megamenu-val > li.menu-item-has-children:not(#mega-responsive) > .accordion-toggle-btn .arrow-css');
    firstLevelArrows.forEach(arrow => {
        arrow.addEventListener('click', handleFirstLevelAccordion);
    });

    // Botones de segundo nivel (dentro de .megawrap)
    const secondLevelButtons = document.querySelectorAll('.megawrap .menu-item-has-children > .accordion-toggle-btn');
    secondLevelButtons.forEach(button => {
        button.addEventListener('click', handleSecondLevelAccordion);
    });

    // Event listeners adicionales para arrow-css de segundo nivel
    const secondLevelArrows = document.querySelectorAll('.megawrap .menu-item-has-children > .accordion-toggle-btn .arrow-css');
    secondLevelArrows.forEach(arrow => {
        arrow.addEventListener('click', handleSecondLevelAccordion);
    });

    // Botones de tercer nivel (dentro de .megawrap)
    const thirdLevelButtons = document.querySelectorAll('.ul.megamenu-list>li.menu-item>.megawrap>ul.sub-menu>li.mega-label>.megawrap>ul>li.sub-toggle>button.accordion-toggle-btn');
    thirdLevelButtons.forEach(button => {
        button.addEventListener('click', handleThirdLevelAccordion);
    });

    // Event listeners adicionales para arrow-css de tercer nivel
    const thirdLevelArrows = document.querySelectorAll('.ul.megamenu-list>li.menu-item>.megawrap>ul.sub-menu>li.mega-label>.megawrap>ul>li.sub-toggle>button.accordion-toggle-btn .arrow-css');
    thirdLevelArrows.forEach(arrow => {
        arrow.addEventListener('click', handleThirdLevelAccordion);
    });


    // Reinicializar acordeón al cambiar el tamaño de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            // Si cambiamos a desktop, limpiar botones y cerrar el menú móvil
            cleanAccordionButtons();
            
            const menu = document.querySelector('ul#menu-principal, ul#menu-megamenu-es, ul#menu-megamenu-val');
            const navIcon = document.querySelector('#mega-nav-icon');
            const menuItems = document.querySelectorAll('ul#menu-principal > li:not(#mega-responsive), ul#menu-megamenu-es > li:not(#mega-responsive), ul#menu-megamenu-val > li:not(#mega-responsive)');
            
            if (menu && menu.classList.contains('open')) {
                menu.classList.remove('open');
                if (navIcon) {
                    navIcon.classList.remove('open');
                }
                menuItems.forEach(item => {
                    item.style.display = 'none';
                    item.classList.remove('accordion-open');
                    const subItems = item.querySelectorAll('.menu-item-has-children');
                    subItems.forEach(subItem => subItem.classList.remove('accordion-open'));
                });
            }
            
            // Remover todas las clases de acordeón abierto
            const allAccordions = document.querySelectorAll('.menu-item-has-children');
            allAccordions.forEach(accordion => {
                accordion.classList.remove('accordion-open');
            });
        } 
    });
});