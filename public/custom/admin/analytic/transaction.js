
$(document).ready(function() {
    init_data_status_transaksi_table();
    _cart_wishlist();
});

function toRupiah(value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

function init_data_status_transaksi_table() {
    let table = $('#dt_status_transaksi');
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
                url: '/analytic/status-transaksi/dt',
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
                { data: 'total_invoice', name: 'invoice'},
                { data: 'gross_profit', name: 'gross_profit'},
                { data: 'gmv', name: 'gmv'},
                { data: 'nmv', name: 'nmv'},
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
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return toRupiah(data);
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return toRupiah(data);
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, full, meta) {
                        return toRupiah(data);
                    }
                }
            ],
        });
    }
}

var _cart_wishlist = function () {
    const apexChart = "#_cart_wishlist";
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
        url: "/admin/analytic/status-transaksi/graph",
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
                    name: 'Gross Profit',
                    data: data.gross_profit
                },
                {
                    name: 'GMV',
                    data: data.gmv
                },
                {
                    name: 'NMV',
                    data: data.nmv
                },
            ])
              chart.updateOptions({
                    xaxis: {
                        categories: data.timestamp,
                    }
                })
        }
    });
}