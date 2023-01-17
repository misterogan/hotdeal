$("#btn_cancel").on("click", function() {
    location.href = '/admin/order';
});

$("#edit_user_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/user/edit',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
            if(data.status === true) {
                swal.fire({
                    text: data.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    location.href = "admin/user";
                });
            }else {
                var values = '';
                jQuery.each(data.message, function (key, value) {
                    values += value+"<br>";
                });

                swal.fire({
                    html: values,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() { });
            }
        }
    });
});

function to_date_time(date) {
    let tanggal = new Date(date);
    return tanggal.getFullYear()+"-"
        + (tanggal.getMonth()+ 1 > 9 ? (tanggal.getMonth()+ 1).toString() : "0" + (tanggal.getMonth()+ 1).toString())
        +"-"
        +(tanggal.getDate() > 9 ? tanggal.getDate().toString() : "0" + tanggal.getDate().toString())
        + " "
        +(tanggal.getHours().toString() > 9 ? tanggal.getHours().toString() : "0" + tanggal.getHours().toString())
        + ":" + (tanggal.getUTCMinutes().toString() > 9 ? tanggal.getUTCMinutes().toString() : "0" + tanggal.getUTCMinutes().toString())
        + ":" + (tanggal.getUTCSeconds().toString() > 9 ? tanggal.getUTCSeconds().toString() : "0" + tanggal.getUTCSeconds().toString());
}

function date_only(date) {
    let tanggal = new Date(date);
    return tanggal.getFullYear()+"-"
        + (tanggal.getMonth()+ 1 > 9 ? (tanggal.getMonth()+ 1).toString() : "0" + (tanggal.getMonth()+ 1).toString())
        +"-"
        +(tanggal.getDate() > 9 ? tanggal.getDate().toString() : "0" + tanggal.getDate().toString());
}

function init_data_table() {
    let table = $('#dt_orders');
    if (table != null) {
        table.DataTable({
            order:[0,'desc'],
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
                url: '/order/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.filter_id = $('#filter_id').val();
                    d.filter = $('#filter').val();
                    d.status_value = $('#status_value').val();
                }
            },
            order: [[ 9, 'desc' ]],
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'order.transaction_number', name: 'transaction_number'},
                { data: 'invoice_number', name: 'invoice_number'},
                { data: 'productswithdetail', name: 'productswithdetail'},
                { data: 'product.quantity', name: 'quantity'},
                { data: 'vendor.name', name: 'vendor.name'},
                { data: 'invoice_total_payment', name: 'Invoice Total Payment'},
                { data: 'master_status.description', name: 'Status'},
                { data: 'created_at', name: 'created_at' },
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
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/transaction/edit/'+ data +'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/order/edit/'+ convertToSlug(data) + '">'+data+'</a>'
                    }
                },
                {
                    targets: 4,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        var arr_name = []
                        for (let index = 0; index < data.length; index++) {
                            var name = data[index].product_detail_with_product.name
                            arr_name.push('&#8226; ' + name)
                        }
                        var str = arr_name.toString()
                        return str.replaceAll(",", "<br>");
                    }
                },
                {
                    targets: 5,
                    searchable: true,
                },
                {
                    targets: 6,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/vendor/edit/'+ full.vendor.id +'">'+data+'</a>'
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return toRupiah(data)
                    }
                },
                {
                    targets: 8,
                    className: "text-center",
                },
                {
                    targets: 9,
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return date_only(data);
                    }
                },
            ],
        });

    }
}

function init_data_canceled_table(){
    let table = $('#dt_orders_canceled');
    if (table != null) {
        table.DataTable({
            order:[0,'desc'],
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
                url: '/admin/order/canceled/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            order: [[ 5, 'desc' ]],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'invoice_number', name: 'invoice_number'},
                { data: 'order_logs_desc', name: 'order_logs_desc'},
                { data: 'refund_type', name: 'refund_type'},
                { data: 'date', name: 'date'},
                { data: 'refund_date', name: 'refund_date'},
            ],
            columnDefs:[
                {
                    targets: 2,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/order/edit/'+ convertToSlug(data) + '">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/canceled/detail/'+ full.id + '?invoice_number=' + convertToSlug(full.invoice_number) + '">' + data + '</a>'
                        // return data;
                    }
                },
            ]
        });
    }
}
function toRupiah(value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

function convertToSlug(text) {
    return text.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '-');
}

$(document).ready(function() {
    init_data_table();
    init_data_canceled_table();
    

});

$("#apply_filter").on('click', function(){
    // alert('test');
    $('#dt_orders').DataTable().ajax.reload();
});

$('#filter').keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
    $('#dt_orders').DataTable().ajax.reload();
    }
});   

var inputF = document.getElementById("status_value");

$(".filter-status").on('click', function(e){
    var a = $('.filter-status');
    console.log(e);
    $.each(a, function(i,v){
        
        if (v.classList.contains('text-primary')) {
            v.classList.remove('text-primary')
            v.classList.add('text-muted')
        }
    })
    e.currentTarget.classList.add('text-primary');
    e.currentTarget.classList.remove('text-muted');
    

    inputF.value = e.currentTarget.innerHTML;
    e.currentTarget.value = inputF.value
    $('#dt_orders').DataTable().ajax.reload();


});


