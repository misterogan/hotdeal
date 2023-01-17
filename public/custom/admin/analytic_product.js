$(document).ready(function() {
    drawing_chart();
    init_data_table();

    $('#search').on( 'click change', function (event) {
        event.preventDefault();
        init_data_table();
    });


});


function drawing_chart(){
    // alert($("#kt_select2_101").val());
    $.ajax({
        url: "/admin/analytic/get/data/product",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            product_name: $("#kt_select2_101").val(),
            date_from: $("#date_from").val(),
            date_until: $("#date_until").val()
        },
        beforeSend:function(){
            $('#myChart').remove();
            $('.chart-container').append('<canvas id="myChart" width="1200" height="500" ></canvas>');
        },
        success: function(data) {

            var barChartData = {
                labels:data.timestamp,
                datasets: [
                    {
                        label:"Total Sale",
                        backgroundColor: "rgb(255, 99, 132)",
                        data: data.sum
                    },
                ]

            };

            // var ctx = document.getElementById('canvas').getContext('2d');
            var ctx = document.getElementById('myChart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'line',
                data : barChartData,
                options: {
                    scales: {
                        x: {
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
}



function init_data_table() {

    // alert($('#date_from_1').val());
    var dataTable =  $('#dt_product').DataTable({
        retrieve: true,
        order:[3,'DESC'],
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
            url: '/admin/analytic/get/data/product/dt',
            type: "POST",
            data: function ( d ) {
                d.myKey = "myValue";
                d._token = $('meta[name="csrf-token"]').attr('content');
                d.date_from = $('#date_from_1').val();
                d.date_until = $('#date_until_1').val();
            }
        },
        columns: [
            { defaultContent: '<td></td>' },
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'product_name', name: 'product_name'},
            { data: 'total', name: 'total'},
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

        ],
    });
    dataTable.draw();
}


