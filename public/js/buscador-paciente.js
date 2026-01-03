document.addEventListener('DOMContentLoaded', function () {
    const pacientes = window.PACIENTES_LIST || [];
    const input = document.getElementById('buscador_paciente');
    const results = document.getElementById('buscador_paciente_results');
    const selectPaciente = document.getElementById('paciente_select');
    const condicionBox = document.getElementById('condicion_paciente_box');

    input.addEventListener('input', function () {
        const val = input.value.trim().toLowerCase();
        results.innerHTML = '';
        
        if (!val) {
            results.classList.add('hidden');
            return;
        }
        
        const filtrados = pacientes.filter(p =>
            p.nombre.toLowerCase().includes(val) ||
            p.apellido.toLowerCase().includes(val) ||
            p.cedula.toLowerCase().includes(val)
        );
        
        if (filtrados.length === 0) {
            results.classList.add('hidden');
            return;
        }
        
        results.classList.remove('hidden');
        
        filtrados.slice(0, 10).forEach(p => {
            const div = document.createElement('div');
            div.className = 'cursor-pointer px-4 py-3 hover:bg-blue-50 border-b border-gray-100 text-sm';
            div.innerHTML = `<div class="font-medium text-gray-900">${p.nombre} ${p.apellido}</div><div class="text-xs text-gray-500">${p.cedula}</div>`;
            div.onclick = function () {
                selectPaciente.value = p.id;
                input.value = `${p.nombre} ${p.apellido} (${p.cedula})`;
                results.classList.add('hidden');
                results.innerHTML = '';
                // Mostrar condición
                let cond = (p.condicion || '').split(',').map(c => c.trim()).filter(Boolean);
                let label = cond.length ? cond.map(c => {
                    if (c === 'diabetico') return 'Diabético';
                    if (c === 'hiposodico') return 'Hiposódico';
                    if (c === 'normal') return 'Normal';
                    return c;
                }).join(', ') : '—';
                condicionBox.innerHTML = `<div class="text-sm text-gray-700"><span class="font-semibold">Condición:</span> <span class="text-gray-600">${label}</span></div>`;
            };
            results.appendChild(div);
        });
    });
    
    // Cerrar dropdown al hacer clic afuera
    document.addEventListener('click', function(e) {
        if (e.target !== input && e.target !== results) {
            results.classList.add('hidden');
        }
    });
});
