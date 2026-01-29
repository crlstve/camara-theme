// Widget-compatible calendar initialization
function initInvestCalendar(options = {}) {
  const {
    calendarSelector = '#calendar',
    widgetId = '',
    modalPrefix = '',
    filterPrefix = ''
  } = options;

  // Función para obtener el valor de una cookie
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  // Detectar idioma desde Polylang y también desde PHP
  let polylangCode = getCookie('pll_language') || 'en';
  
  if (typeof investCalendarTranslations !== 'undefined' && investCalendarTranslations.currentLanguage) {
    polylangCode = investCalendarTranslations.currentLanguage;
  }
  
  let currentLanguage = polylangCode;
  
  if (polylangCode === 'val') {
    currentLanguage = 'ca';
  } else if (polylangCode === 'es') {
    currentLanguage = 'es';
  } else {
    currentLanguage = 'en';
  }

  // Configuración de textos según idioma
  function getTexts(lang) {
      // Primero intentar usar las traducciones de PHP si están disponibles
      if (typeof investCalendarTranslations !== 'undefined' && investCalendarTranslations.texts) {
          return investCalendarTranslations.texts;
          }
    const translations = {
      'en': {
        buttonText: { today: 'Today', month: 'Month', week: 'Week', day: 'Day', list: 'List' },
        noEvents: 'acfEvents is not defined or is not an array.',
        noCalendar: 'Calendar element not found',
        errorLoading: 'Error loading calendar. Please check the console for more details.',
        noSpecified: 'Not specified'
      },
      'es': {
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día', list: 'Lista' },
        noEvents: 'acfEvents no está definido o no es un array.',
        noCalendar: 'No se encontró el elemento del calendario',
        errorLoading: 'Error al cargar el calendario. Por favor, verifica la consola para más detalles.',
        noSpecified: 'No especificado'
      },
      'ca': {
        buttonText: { today: 'Hui', month: 'Mes', week: 'Setmana', day: 'Dia', list: 'Llista' },
        noEvents: 'acfEvents no està definit o no és un array.',
        noCalendar: 'No s\'ha trobat l\'element del calendari',
        errorLoading: 'Error en carregar el calendari.',
        noSpecified: 'No especificat'
      }
    };
    return translations[lang] || translations['en'];
  }

  const texts = getTexts(currentLanguage);
  
  const calendarEl = document.querySelector(calendarSelector);
  
  if (!calendarEl) {
    console.error(texts.noCalendar + ': ' + calendarSelector);
    return null;
  }

  if (!acfEvents || !Array.isArray(acfEvents)) {
    console.error(texts.noEvents);
    return null;
  }

  // Función para obtener color de fondo basado en el sector
  function getEventColor(sector) {
    const colorMap = {
      'Agnóstico': '#6b7280',
      'Baterías': '#eab308',
      'Energy': '#22c55e',
      'ESG': '#10b981',
      'FDI & Finance': '#3b82f6',
      'Food': '#f59e0b',
      'Gaming': '#8b5cf6',
      'Logistics': '#06b6d4',
      'Real Estate & Urban': '#ef4444',
      'Sports': '#ec4899',
      'Tech Multisectorial': '#1e40af',
      'Institucional': '#64748b',
    };
    return colorMap[sector] || '#1E3B7D';
  }

  function getEventBorderColor(sector) {
    const borderColorMap = {
      'Agnóstico': '#4b5563',
      'Baterías': '#ca8a04',
      'Energy': '#16a34a',
      'ESG': '#059669',
      'FDI & Finance': '#2563eb',
      'Food': '#d97706',
      'Gaming': '#7c3aed',
      'Logistics': '#0891b2',
      'Real Estate & Urban': '#dc2626',
      'Sports': '#db2777',
      'Tech Multisectorial': '#1e3a8a',
      'Institucional': '#475569',
    };
    return borderColorMap[sector] || '#1E3B7D';
  }

  // Transformar los eventos
  const events = acfEvents
    .filter(event => event.start !== null && event.start !== '')
    .map(event => {
      let startDate = event.start;
      let endDate = event.end;
      
      if (startDate.includes('/')) {
        startDate = startDate.split('/').reverse().join('-');
      }
      if (endDate && endDate.includes('/')) {
        endDate = endDate.split('/').reverse().join('-');
      }
      
      // Crear el objeto del evento
      const eventObj = {
        id: event.id || Math.random().toString(36).substr(2, 9),
        title: event.title || 'Sin título',
        start: startDate,
        allDay: true, // Forzar que sea evento de día completo
        extendedProps: {
          sector: event.sector || '',
          country: event.country || '',
          lugar: event.lugar || '',
          pais: event.pais || '',
          image: event.image || 'https://investinvlc.com/wp-content/uploads/image-1.jpg',
          content: event.content || '',
          ciudad: event.ciudad || '',
          web: event.web || '',
          originalEnd: event.end || null, // Guardar la fecha de fin original
        },
        url: event.web || event.url || '#',
        backgroundColor: getEventColor(event.sector),
        borderColor: getEventBorderColor(event.sector),
        textColor: '#ffffff'
      };
      
      // Solo agregar 'end' si hay una fecha de fin diferente a la de inicio
      if (endDate && endDate !== startDate) {
        // Debug temporal
        console.log('Evento con fechas diferentes:', {
          title: event.title,
          originalStart: event.start,
          originalEnd: event.end,
          processedStart: startDate,
          processedEnd: endDate
        });
        
        // Para eventos allDay, FullCalendar considera la fecha de fin como exclusiva
        // Por lo tanto, sumamos un día para que se muestre hasta la fecha de fin inclusive
        try {
          const endDateObj = new Date(endDate);
          // Verificar que la fecha sea válida
          if (!isNaN(endDateObj.getTime())) {
            endDateObj.setDate(endDateObj.getDate() + 1);
            const adjustedEndDate = endDateObj.getFullYear() + '-' + 
                                  String(endDateObj.getMonth() + 1).padStart(2, '0') + '-' + 
                                  String(endDateObj.getDate()).padStart(2, '0');
            eventObj.end = adjustedEndDate;
            console.log('Fecha de fin ajustada:', {
              original: endDate,
              adjusted: adjustedEndDate
            });
          } else {
            console.warn('Fecha de fin inválida para evento:', event.title, endDate);
          }
        } catch (error) {
          console.warn('Error procesando fecha de fin para evento:', event.title, error);
        }
      }
      
      return eventObj;
    });

  // Función para configurar los filtros con IDs únicos del widget
  function setupFilters(calendar) {
    const sectorFilterId = filterPrefix ? `sectorFilter-${filterPrefix}` : 'sectorFilter';
    const countryFilterId = filterPrefix ? `countryFilter-${filterPrefix}` : 'countryFilter';
    const clearFiltersId = filterPrefix ? `clearFilters-${filterPrefix}` : 'clearFilters';

    const sectorFilter = document.getElementById(sectorFilterId);
    const countryFilter = document.getElementById(countryFilterId);
    const clearFiltersBtn = document.getElementById(clearFiltersId);

    // Función para aplicar filtros específica para este calendario
    function applyFilters() {
      const selectedSector = sectorFilter ? sectorFilter.value : '';
      const selectedCountry = countryFilter ? countryFilter.value : '';

      try {
        calendar.getEvents().forEach(event => {
          let showEvent = true;

          // Filtrar por sector
          if (selectedSector && event.extendedProps.sector !== selectedSector) {
            showEvent = false;
          }

          // Filtrar por país
          if (selectedCountry && (event.extendedProps.country !== selectedCountry && event.extendedProps.pais !== selectedCountry)) {
            showEvent = false;
          }

          // Mostrar u ocultar el evento
          if (showEvent) {
            event.setProp('display', '');
          } else {
            event.setProp('display', 'none');
          }
        });
      } catch (err) {
        console.error('Error applying filters for widget:', err);
      }
    }

    // Event listeners para los filtros
    if (sectorFilter) {
      sectorFilter.addEventListener('change', applyFilters);
    }
    if (countryFilter) {
      countryFilter.addEventListener('change', applyFilters);
    }
    if (clearFiltersBtn) {
      clearFiltersBtn.addEventListener('click', function() {
        if (sectorFilter) sectorFilter.value = '';
        if (countryFilter) countryFilter.value = '';
        applyFilters();
      });
    }
  }

  // Función para llenar los selects de filtros
  function populateFilterSelects() {
    const sectorSelectId = filterPrefix ? `sectorFilter-${filterPrefix}` : 'sectorFilter';
    const countrySelectId = filterPrefix ? `countryFilter-${filterPrefix}` : 'countryFilter';
    
    const sectorSelect = document.getElementById(sectorSelectId);
    const countrySelect = document.getElementById(countrySelectId);

    if (!sectorSelect || !countrySelect) {
      console.warn('Filter elements not found in DOM');
      return;
    }

    // Obtener sectores y países únicos de los eventos
    const sectors = [...new Set(acfEvents.map(event => event.sector).filter(s => s && s.trim() !== ''))].sort();
    const countries = [...new Set(acfEvents.map(event => event.country || event.pais).filter(c => c && c.trim() !== ''))].sort();

    // Llenar select de sectores
    if (sectors.length > 0) {
      sectors.forEach(sector => {
        const option = document.createElement('option');
        option.value = sector;
        option.textContent = sector;
        sectorSelect.appendChild(option);
      });
    } else {
      sectorSelect.disabled = true;
      const option = document.createElement('option');
      option.value = '';
      option.textContent = 'No hay sectores disponibles';
      sectorSelect.appendChild(option);
    }

    // Llenar select de países
    if (countries.length > 0) {
      countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
      });
    } else {
      countrySelect.disabled = true;
      const option = document.createElement('option');
      option.value = '';
      option.textContent = 'No hay países disponibles';
      countrySelect.appendChild(option);
    }
  }

  // Llenar los filtros antes de inicializar el calendario
  populateFilterSelects();

  // Inicializar el calendario
  try {
    const calendar = new FullCalendar.Calendar(calendarEl, {
      locale: currentLanguage,
      firstDay: 1, // Semana empieza el lunes
      initialView: 'dayGridMonth',
      events: events,
      height: 'auto',
      buttonText: texts.buttonText,
      dayHeaderFormat: { weekday: 'short' },
      headerToolbar: {
        left: 'title',
        right: 'prev,next'
      },
      eventClick: function (info) {
        info.jsEvent.preventDefault();

        // IDs del modal con prefijo
        const modalTitleId = modalPrefix ? `modalTitle-${modalPrefix}` : 'modalTitle';
        const modalStartId = modalPrefix ? `modalStart-${modalPrefix}` : 'modalStart';
        const modalEndId = modalPrefix ? `modalEnd-${modalPrefix}` : 'modalEnd';
        const modalContentId = modalPrefix ? `modalContent-${modalPrefix}` : 'modalContent';
        const modalLocationId = modalPrefix ? `modalLocation-${modalPrefix}` : 'modalLocation';
        const modalUrlId = modalPrefix ? `modalUrl-${modalPrefix}` : 'modalUrl';
        const modalImageId = modalPrefix ? `modalImage-${modalPrefix}` : 'modalImage';

        // Llenar el contenido del modal
        const modalTitle = document.getElementById(modalTitleId);
        const modalStart = document.getElementById(modalStartId);
        const modalEnd = document.getElementById(modalEndId);
        const modalContent = document.getElementById(modalContentId);
        const modalLocation = document.getElementById(modalLocationId);
        const modalUrl = document.getElementById(modalUrlId);
        const modalImage = document.getElementById(modalImageId);

        if (modalTitle) modalTitle.textContent = info.event.title;
        
        // Función para formatear fecha sin problemas de zona horaria
        function formatDateLocal(date) {
          if (!date) return null;
          
          // Si es una fecha de FullCalendar (objeto Date)
          if (date instanceof Date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
          }
          
          // Si es un string, intentar parsearlo
          if (typeof date === 'string') {
            // Si ya está en formato YYYY-MM-DD, devolverlo tal como está
            if (date.match(/^\d{4}-\d{2}-\d{2}$/)) {
              return date;
            }
            
            // Si está en formato DD/MM/YYYY, convertirlo
            if (date.includes('/')) {
              return date.split('/').reverse().join('-');
            }
          }
          
          return null;
        }
        
        const startDate = formatDateLocal(info.event.start);
        if (modalStart) modalStart.textContent = startDate;
        
        // Verificar si hay fecha de fin
        let endDateText = texts.noSpecified;
        const originalEnd = info.event.extendedProps.originalEnd;
        
        if (originalEnd) {
          // Convertir fecha original si es necesario
          let endDateFormatted = formatDateLocal(originalEnd);
          
          // Mostrar la fecha de fin siempre que exista
          if (endDateFormatted) {
            endDateText = endDateFormatted;
          }
        } else if (info.event.end) {
          // Fallback: usar la fecha de fin de FullCalendar si existe
          const endDate = formatDateLocal(info.event.end);
          if (endDate) {
            endDateText = endDate;
          }
        }
        
        if (modalEnd) modalEnd.textContent = endDateText;
        
        if (modalContent) modalContent.textContent = info.event.extendedProps.content;
        
        // Construir la dirección
        const lugar = info.event.extendedProps.lugar || info.event.extendedProps.ciudad || '';
        const pais = info.event.extendedProps.pais || info.event.extendedProps.country || '';
        const partesDireccion = [lugar.trim(), pais.trim()].filter(parte => parte !== '');
        const direccion = partesDireccion.length > 0 ? partesDireccion.join(', ') : texts.noSpecified;
        
        if (modalLocation) modalLocation.textContent = direccion;
        
        if (modalUrl) {
          modalUrl.href = info.event.extendedProps.web || info.event.url;
        }
        
        if (modalImage) {
          modalImage.style.background = `url(${info.event.extendedProps.image})`;
          modalImage.style.backgroundSize = 'cover';
          modalImage.style.backgroundPosition = 'top center';
        }

          // Abrir modal
          // Siempre usar la función global que tiene las animaciones
          if (typeof openModal === 'function') {
              openModal();
          } else if (modalPrefix && typeof window['openModal' + modalPrefix] === 'function') {
              // Solo como fallback si no existe la global
              window['openModal' + modalPrefix]();
          }
      }
    });

    calendar.render();

    // Configurar los filtros después de renderizar el calendario
    setupFilters(calendar);

    return calendar;

  } catch (error) {
    console.error('Error initializing calendar:', error);
    if (calendarEl) {
      calendarEl.innerHTML = `<div style="padding: 20px; text-align: center; color: #666;">${texts.errorLoading}</div>`;
    }
    return null;
  }
}

// Inicialización automática para el caso estándar (compatibilidad hacia atrás)
document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('calendar')) {
    initInvestCalendar();
  }
});

// Funciones globales del modal (compatibilidad hacia atrás)
function openModal() {
    // Buscar modal y overlay con IDs dinámicos
    const modal = document.querySelector('[id^="eventModal"]');
    const overlay = document.querySelector('[id^="modalOverlay"]');

    if (modal && overlay) {
        modal.style.display = 'block';
        overlay.style.display = 'block';

        // Resetear estilos iniciales para permitir que la animación funcione
        modal.style.opacity = '';
        modal.style.transform = '';

        // Forzar reflow y usar requestAnimationFrame para asegurar que las animaciones se apliquen
        requestAnimationFrame(() => {
            modal.classList.add('modal-slide-up');
            overlay.classList.add('modal-fade-in');
        });
    }
}

function closeModal() {
  // Buscar modal y overlay con IDs dinámicos
  const modal = document.querySelector('[id^="eventModal"]');
  const overlay = document.querySelector('[id^="modalOverlay"]');
  
  if (modal && overlay) {
    modal.classList.remove('modal-slide-up');
    modal.classList.add('modal-slide-down');
    overlay.classList.remove('modal-fade-in');
    overlay.classList.add('modal-fade-out');
    
    setTimeout(() => {
      modal.style.display = 'none';
      overlay.style.display = 'none';
      
      // Restaurar estilos iniciales
      modal.style.opacity = '';
      modal.style.transform = '';
      
      modal.classList.remove('modal-slide-down');
      overlay.classList.remove('modal-fade-out');
    }, 200);
  }
}
