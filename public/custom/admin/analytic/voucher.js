$(document).ready(function() {
    voucher();
});
var inputF = document.getElementById("voucher_value");

$(".filter-voucher").on('click', function(e){
    var a = $('.filter-voucher');
    console.log(e);
    inputF.value = e.currentTarget.text;
    console.log(inputF.value);
    filter_voucher()
});

function voucher() {
    $.ajax({
        url: "/admin/analytic/voucher",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            date_from: $("#date_from").val(),
            date_until: $("#date_until").val(),
            filter_voucher: $("#voucher_value").val()
        },
        beforeSend:function(){
            $('#hotpointChart').remove();
            $('.chart-hot-point').append('<canvas id="hotpointChart" width="1000" height="500" ></canvas>');
        },
        success: function(a) {
            var datos = a;
            console.log(datos);
            const randomBetween = (min, max) => min + Math.floor(Math.random() * (max - min + 1));
            var datasets = [];
            var dataset = {};
            var timestamp = datos.timestamp;
            delete datos.timestamp;
            $.each(datos, function(i,v){
                const r = randomBetween(0, 255);
                const g = randomBetween(0, 255);
                const b = randomBetween(0, 255);
                const rgbFill = `rgba(${r},${g},${b},0.2)`;
                const rgbBorder = `rgba(${r},${g},${b},1)`;
                dataset['label'] = v.name;
                dataset['data'] = v.data;
                dataset['backgroundColor'] = rgbFill;
                dataset['borderColor'] = rgbBorder;
                dataset['tension'] = 0.2;
                dataset['fill'] = true;
                dataset['borderWidth'] = 0.7;
                datasets.push(dataset);
                dataset = {};
                
            });

            var ctx = document.getElementById('hotpointChart').getContext('2d');
            var hotpointChart = '';
            hotpointChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timestamp,
                    datasets: datasets
                },
                options: {
                    interaction: {
                        mode: 'index',
                        intersect: false
                      },
                    scales: {
                        y: {
                            beginAtZero: true,

                        },
                        x: {
                            ticks: {
                                display: false
                           }
                        }
                    },
                    ticks: {
                        precision: 0
                    }
                }
            });
        }
    });
}

function filter_voucher() {
    $.ajax({
        url: "/admin/analytic/voucher",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            date_from: $("#date_from").val(),
            date_until: $("#date_until").val(),
            filter_voucher: $("#voucher_value").val()
        },
        beforeSend:function(){
            $('#hotpointChart').remove();
            $('.chart-hot-point').append('<canvas id="hotpointChart" width="1000" height="500" ></canvas>');
        },
        success: function(data) {
            var datos = data;
            console.log(datos);
            var ctx = document.getElementById('hotpointChart').getContext('2d');
            var hotpointChart = '';
            const randomBetween = (min, max) => min + Math.floor(Math.random() * (max - min + 1));
            const r = randomBetween(0, 255);
            const g = randomBetween(0, 255);
            const b = randomBetween(0, 255);
            const rgbFill = `rgba(${r},${g},${b},0.2)`;
            const rgbBorder = `rgba(${r},${g},${b},1)`;
            hotpointChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: datos.timestamp,
                    datasets: [
                        {
                            label: datos.voucher,
                            data: datos.data,
                            fill: true,
                            tension: 0.1,
                            backgroundColor: [
                                rgbFill,

                            ],
                            borderColor: [
                                rgbBorder,
                            ],
                            borderWidth: 0.7
                        },

                    ]
                },
                options: {
                    interaction: {
                        mode: 'index',
                        intersect: false
                      },
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                        x: {
                            ticks: {
                                display: false
                           }
                        }
                    },
                    ticks: {
                        precision: 0
                    }
                }
            });
        }
    });
}
