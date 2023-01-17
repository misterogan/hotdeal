$(document).ready(function() {
    _hotpoint_chart();
    _monthly_hotpoint_chart();
    init_data_hotpoint_table();
});
function toRupiah(value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

function init_data_hotpoint_table() {
    let table = $('#dt_hotpoint');
    if (table != null) {
        table.DataTable({
            order:[2,'desc'],
            responsive: {
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            return col.hidden ?
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+''+'</td> '+
                                '<td>'+col.data+'</td>'+
                                '</tr>' :
                                '';
                        } ).join('');

                        return data ?
                            $('<table/>').append( data ) :
                            false;
                    },
                }
            },
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: 'admin/analytic/hot-point/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'date', name: 'date'},
                { data: 'sum_use', name: 'use'},
                { data: 'sum_earn', name: 'earn'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-right",
                },
                {
                    targets: 1,
                    className: "text-center",
                },
                {
                    targets: 2,
                    className: "text-center",
                },
                {
                    targets: 3,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return toRupiah(data);
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return toRupiah(data);
                    }
                },
            ],
        });
    }
}

var _hotpoint_chart = function () {
    const apexChart = "#_hotpoint_chart";
    var options = {
        series: [{
            name: "Desktops",
            data: []
        }],
        noData : {
            text : 'Loading'
        },
        chart: {
            type: 'area',
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
        url: "/admin/analytic/hot-point",
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
                    name: 'Using to transaction',
                    data: data.type2
                },
                {
                    name: 'Earning from member Cashtree',
                    data: data.type1
                },
                {
                    name: 'Earning from cancel order',
                    data: data.type3
                },
                {
                    name: 'Send From Admin',
                    data: data.type4
                },
            ])
            // chart.hideSeries('Total Use')
            chart.updateOptions({
                xaxis: {
                    categories: data.timestamp,
                }
            })
            
        }
    });
}

var _monthly_hotpoint_chart = function () {
    const apexChart = "#_monthly_hotpoint_chart";
    var options = {
        series: [{
            name: "Desktops",
            data: []
        }],
        noData : {
            text : 'Loading'
        },
        chart: {
            type: 'area',
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
        url: "/admin/analytic/hot-point",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            chart.updateSeries([
                {
                    name: 'Total Use',
                    data: data.use_by_month
                },
                {
                    name: 'Using to transaction',
                    data: data.type2_by_month
                },
                {
                    name: 'Total Earn',
                    data: data.earn_by_month
                },
                {
                    name: 'Earning from member Cashtree',
                    data: data.type1_by_month
                },
                {
                    name: 'Earning from cancel order',
                    data: data.type3_by_month
                },
                {
                    name: 'Send from admin',
                    data: data.type4_by_month
                }
            ])
              chart.updateOptions({
                    xaxis: {
                        categories: data.months,
                    }
                })
        }
    });
}

$('#download').click(function(){
    var date_from = $('#date_from').val();
    var date_until = $('#date_until').val();
    if(!date_from || !date_until){
        alert('Tanggal tidak boleh kosong!');
        return false
    }
    var link = '/admin/analytic/hot-point/download/'+date_from+'-'+date_until;
    window.location.href = link;
})
 