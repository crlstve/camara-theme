jQuery(document).ready(function ($) {

    // Variables globales
    let currentDate = new Date();
    let selectedDate = new Date();
    let heroSplide = null;
    let eventsData = {};

    // Inicializar Splide
    function initSplide() {
        if (heroSplide) {
            heroSplide.destroy();
            heroSplide = null;
        }

        heroSplide = new Splide('#hero-events-slider', {
            type: 'loop',
            perPage: 1,
            perMove: 1,
            gap: '2rem',
            pagination: true,
            arrows: false,
            autoplay: false,
            lazyLoad: 'nearby',
            preloadPages: 1,
        });

        heroSplide.mount();
    }

    // Formatear fecha a YYYYMMDD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}${month}${day}`;
    }

    // Obtener nombre del mes en español
    function getMonthName(date) {
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return months[date.getMonth()];
    }

    // Cargar eventos por fecha via AJAX
    function loadEventsByDate(date) {
        const dateStr = formatDate(date);

        $.ajax({
            url: MyAjax.url,
            type: 'POST',
            data: {
                action: 'get_events_by_date',
                date: dateStr
            },
            success: function (response) {
                if (response.success && response.data.events.length > 0) {
                    let slides = '';
                    response.data.events.forEach(function (event) {
                        slides += `
                                <li class="splide__slide" style="height: 384px;">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center h-full">
                                        <div class="order-2 md:order-1 p-6 col-span-5">
                                            <span class="inline-block px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full mb-3">${event.type}</span>
                                            <h2 class="slider-calendar-title text-xl font-bold text-gray-900 mb-4">${event.title}</h2>
                                            <div class="space-y-2 mb-6 text-sm">
                                                ${event.hours ? `<p class="text-gray-600"><strong>${event.hours}</strong>${event.price ? ' | ' + event.price : ''}${event.location ? ' | ' + event.location : ''}</p>` : ''}
                                                ${event.description ? `<p class="text-gray-600 text-sm">${event.description}</p>` : ''}
                                            </div>
                                            <a href="${event.url}" class="inline-flex items-center px-6 py-3 bg-[#e91e63] text-white rounded-full font-medium hover:bg-[#c2185b] transition-colors border">
                                                Inscripción
                                            </a>
                                        </div>
                                        <div class="order-1 md:order-2 col-span-7 my-3 rounded-2xl">
                                            <img src="${event.image}" alt="${event.title}" class="w-full object-cover rounded-2xl shadow-lg" style="height: 320px;" loading="lazy" />
                                        </div>
                                    </div>
                                </li>
                            `;
                    });
                    $('#hero-slider-wrapper').html(slides);
                } else {
                    $('#hero-slider-wrapper').html(`
                            <li class="splide__slide flex items-center justify-center" style="height: 384px;">
                                <div class="text-center text-gray-400">
                                    <p class="text-lg">No hay eventos para esta fecha</p>
                                    <p class="text-sm mt-2">Intenta seleccionar otra fecha en el calendario</p>
                                </div>
                            </li>
                        `);
                }
                initSplide();
            },
            error: function () {
                $('#hero-slider-wrapper').html('<li class="splide__slide flex items-center justify-center" style="height: 384px;"><div class="text-center text-red-400"><p>Error al cargar eventos</p></div></li>');
                initSplide();
            }
        });
    }

    // Obtener días con eventos del mes
    function getMonthEvents(year, month) {
        $.ajax({
            url: MyAjax.url,
            type: 'POST',
            data: {
                action: 'get_month_events',
                year: year,
                month: month + 1
            },
            success: function (response) {
                if (response.success) {
                    eventsData = response.data;
                    renderCalendar();
                }
            }
        });
    }

    // Renderizar calendario
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Actualizar header
        $('#calendar-month-year').text(`${getMonthName(currentDate)} ${year}`);

        // Primer día del mes y último día
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        // Ajustar para que Lunes sea el primer día (0 = Domingo, queremos 1 = Lunes)
        let startDay = firstDay.getDay();
        startDay = startDay === 0 ? 6 : startDay - 1;

        const daysInMonth = lastDay.getDate();

        let html = '';

        // Días vacíos al inicio
        for (let i = 0; i < startDay; i++) {
            html += '<div></div>';
        }

        // Días del mes
        const today = new Date();
        const todayStr = formatDate(today);

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = formatDate(date);
            const hasEvents = eventsData[dateStr] && eventsData[dateStr] > 0;
            const isToday = dateStr === todayStr;
            const isSelected = dateStr === formatDate(selectedDate);
            const isPast = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());

            let classes = 'calendar-day';
            if (hasEvents) classes += ' has-events';
            if (isToday) classes += ' today';
            if (isSelected) classes += ' selected';
            if (isPast && !hasEvents) classes += ' disabled';

            html += `<div class="${classes}" data-date="${dateStr}">${day}</div>`;
        }

        $('#calendar-days').html(html);
    }

    // Event listeners del calendario
    $(document).on('click', '.calendar-day:not(.disabled)', function () {
        const dateStr = $(this).data('date');
        if (!dateStr) return;

        const year = parseInt(dateStr.substring(0, 4));
        const month = parseInt(dateStr.substring(4, 6)) - 1;
        const day = parseInt(dateStr.substring(6, 8));

        selectedDate = new Date(year, month, day);

        renderCalendar();
        loadEventsByDate(selectedDate);
    });

    $('#calendar-prev').on('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        getMonthEvents(currentDate.getFullYear(), currentDate.getMonth());
    });

    $('#calendar-next').on('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        getMonthEvents(currentDate.getFullYear(), currentDate.getMonth());
    });

    // Inicializar
    getMonthEvents(currentDate.getFullYear(), currentDate.getMonth());
    loadEventsByDate(selectedDate);

    // Filtros existentes
    $('select.filter_selector_select').on('change', function () {
        var field_programa = $('#filter_programa').val();
        var field_lugar = $('#filter_lugar').val();
        var field_fecha = $('#filter_fecha').val();

        var url = window.location.pathname + '?';
        var params = [];

        if (field_programa) params.push('filtro_programa=' + field_programa);
        if (field_lugar) params.push('filtro_lugar=' + field_lugar);
        if (field_fecha) params.push('filtro_fecha=' + field_fecha);

        url += params.join('&');
        window.location.href = url;
    });
});