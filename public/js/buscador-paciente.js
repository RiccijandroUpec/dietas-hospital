document.addEventListener('DOMContentLoaded', function () {
    const pacientes = window.PACIENTES_LIST || [];
    const input = document.getElementById('buscador_paciente');
    const results = document.getElementById('buscador_paciente_results');
    const selectPaciente = document.getElementById('paciente_select');
    const condicionBox = document.getElementById('condicion_paciente_box');
    const dietaSelect = document.getElementById('dieta_select');
    const dietaOptions = window.DIETAS_LIST || [];

    input.addEventListener('input', function () {
        const val = input.value.trim().toLowerCase();
        results.innerHTML = '';
        if (!val) return;
        const filtrados = pacientes.filter(p =>
            p.nombre.toLowerCase().includes(val) ||
            p.apellido.toLowerCase().includes(val) ||
            p.cedula.toLowerCase().includes(val)
        );
        filtrados.slice(0, 10).forEach(p => {
            const div = document.createElement('div');
            div.className = 'cursor-pointer px-2 py-1 hover:bg-gray-100';
            div.textContent = `${p.nombre} ${p.apellido} (${p.cedula})`;
            div.onclick = function () {
                selectPaciente.value = p.id;
                input.value = `${p.nombre} ${p.apellido} (${p.cedula})`;
                results.innerHTML = '';
                // Mostrar condición
                let cond = (p.condicion || '').split(',').map(c => c.trim()).filter(Boolean);
                let label = cond.length ? cond.map(c => {
                    if (c === 'diabetico') return 'Diabético';
                    if (c === 'hiposodico') return 'Hiposódico';
                    if (c === 'normal') return 'Normal';
                    return c;
                }).join(', ') : '—';
                condicionBox.innerHTML = `<span class='text-xs text-gray-700'>Condición: <b>${label}</b></span>`;
                // Selección automática de dieta
                if (cond.includes('diabetico') && cond.includes('hiposodico')) {
                    autoSelectDieta(['diabetico', 'hiposodico']);
                } else if (cond.includes('diabetico')) {
                    autoSelectDieta(['diabetico']);
                } else if (cond.includes('hiposodico')) {
                    autoSelectDieta(['hiposodico']);
                } else {
                    dietaSelect.value = '';
                }
            };
            results.appendChild(div);
        });
    });

    function autoSelectDieta(condiciones) {
        let found = null;
        for (let d of dietaOptions) {
            let nombre = d.nombre.toLowerCase();
            if (condiciones.length === 2 && nombre.includes('diabetico') && nombre.includes('hiposodico')) {
                found = d.id; break;
            }
            if (condiciones.length === 1 && nombre.includes(condiciones[0])) {
                found = d.id; break;
            }
        }
        if (found) dietaSelect.value = found;
    }
});
