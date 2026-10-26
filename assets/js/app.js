document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('themeToggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            const html = document.documentElement;
            const next = html.dataset.theme === 'dark' ? 'light' : 'dark';
            html.dataset.theme = next;
            document.cookie = `theme=${next}; path=/; max-age=31536000`;
        });
    }

    document.querySelectorAll('[data-chart]').forEach((canvas) => {
        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const values = JSON.parse(canvas.dataset.values || '[]');
        new Chart(canvas, {
            type: canvas.dataset.chart,
            data: {
                labels,
                datasets: [{
                    label: canvas.dataset.title || 'Analytics',
                    data: values,
                    backgroundColor: ['#1769aa','#18865b','#f59e0b','#bd2c3c','#7c3aed','#0891b2']
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    });
});
