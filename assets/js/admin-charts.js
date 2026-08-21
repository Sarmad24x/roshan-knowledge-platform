document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-chart-values]').forEach(function (chart) {
        const values = JSON.parse(chart.dataset.chartValues || '[]');
        const maximum = Math.max(...values, 1);
        chart.innerHTML = values.map(value => '<span style="height:' + (value / maximum * 100) + '%" title="' + value + '"></span>').join('');
    });
});
