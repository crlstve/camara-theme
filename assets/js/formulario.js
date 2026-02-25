document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('camara-form-solicitud');

    if (!form) {
        return;
    }

    const sector1 = form.querySelector('#sector-1');
    const sector2 = form.querySelector('#sector-2');
    const sector3 = form.querySelector('#sector-3');

    const tieneValorSector = (valor) => Boolean(valor && valor !== 'null');

    const actualizarVisibilidadSectores = () => {
        if (!sector1 || !sector2 || !sector3) {
            return;
        }

        const mostrarSector2 = tieneValorSector(sector1.value);
        sector2.style.display = mostrarSector2 ? '' : 'none';

        if (!mostrarSector2) {
            sector2.value = 'null';
            sector3.value = 'null';
        }

        const mostrarSector3 = mostrarSector2 && tieneValorSector(sector2.value);
        sector3.style.display = mostrarSector3 ? '' : 'none';

        if (!mostrarSector3) {
            sector3.value = 'null';
        }
    };

    if (sector1 && sector2 && sector3) {
        sector1.addEventListener('change', actualizarVisibilidadSectores);
        sector2.addEventListener('change', actualizarVisibilidadSectores);
        actualizarVisibilidadSectores();
    }

    const urlPowerAutomate = form.dataset.powerAutomateUrl || '';

    const construirPayload = (formData) => {
        const sectores = formData
            .getAll('sector')
            .filter((valor) => valor && valor !== 'null');

        const [sector1 = '', sector2 = '', sector3 = ''] = sectores;

        return {
            fecha: new Date().toISOString(),
            empresa: (formData.get('empresa') || '').toString().trim(),
            cif: (formData.get('cif') || '').toString().trim(),
            direccion: (formData.get('direccion') || '').toString().trim(),
            web: (formData.get('web') || '').toString().trim(),
            contacto: (formData.get('contacto') || '').toString().trim(),
            cargo: (formData.get('cargo') || '').toString().trim(),
            email: (formData.get('email') || '').toString().trim(),
            sector_1: sector1,
            sector_2: sector2,
            sector_3: sector3,
            venta: (formData.get('venta') || '').toString().trim(),
            mensaje: (formData.get('mensaje') || '').toString().trim(),
            newsletter: formData.get('newsletter') === 'on',
            legal: formData.get('legal') === 'on'
        };
    };

    const enviarDatos = async (payload) => {
        if (!urlPowerAutomate) {
            throw new Error('No hay URL de Power Automate configurada en el formulario.');
        }

        const response = await fetch(urlPowerAutomate, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const responseText = await response.text();
            throw new Error(`Power Automate devolvió ${response.status}: ${responseText}`);
        }

        return response;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);
        const payload = construirPayload(formData);

        try {
            if (submitButton) {
                submitButton.disabled = true;
            }

            console.log('Payload enviado a Power Automate:', payload);
            await enviarDatos(payload);
            alert('¡Datos enviados al Excel!');
            form.reset();
            actualizarVisibilidadSectores();
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            alert('No se pudo enviar el formulario. Revisa la consola para más detalle.');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    });
});
