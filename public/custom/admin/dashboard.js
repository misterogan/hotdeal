$(document).ready(function() {
    _nru_chart();
});

var _nru_chart = function () {
    const apexChart = "#_nru_chart";
    var options = {
        series: [{
            name: "Desktops",
            data: []
        }],
        noData : {
            text : 'Loading'
        },
        chart: {
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: { 	
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: [],
            labels: {
                show: false,
            },
        },
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();

    $.ajax({
        url: "/admin/chart",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            date_from: $("#date_from").val(),
            date_until: $("#date_until").val(),
        },
        success: function(data) {
            chart.updateSeries([
                {
                    name: 'DAU',
                    data: data.dau
                },
                {
                    name: 'NRU',
                    data: data.nru
                }
            ])
              chart.updateOptions({
                    xaxis: {
                        categories: data.timestamp,
                    }
                })
        }
    });
}
