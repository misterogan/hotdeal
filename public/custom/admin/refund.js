$("#btn_cancel").on("click", function() {
    location.href = '/admin/refunds';
});

$("#edit_refund_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/refunds/status/update',
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
                    location.href = "admin/refunds";
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
        +(tanggal.getUTCHours().toString() > 9 ? tanggal.getUTCHours().toString() : "0" + tanggal.getUTCHours().toString())
        + ":" + (tanggal.getUTCMinutes().toString() > 9 ? tanggal.getUTCMinutes().toString() : "0" + tanggal.getUTCMinutes().toString())
        + ":" + (tanggal.getUTCSeconds().toString() > 9 ? tanggal.getUTCSeconds().toString() : "0" + tanggal.getUTCSeconds().toString());
}

function init_data_table() {
    let table = $('#dt_refund');
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
                url: '/refund/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            }, 
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'refund_status', name: 'refund_status'},
                { data: 'refund_type', name: 'refund_type'},
                { data: 'invoice_number', name: 'invoice_number'},
                { data: 'description', name: 'description'},
                { data: 'image_1', name: 'image_1'},
                { data: 'image_2', name: 'image_2'},
                { data: 'image_3', name: 'image_3'},
                { data: 'video', name: 'video'},
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
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
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/user/edit/' + full.user_id + '">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    searchable: true,
                },
                {
                    targets: 4,
                    searchable: true,
                },
                {
                    targets: 5,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/order/edit/'+ convertToSlug(data) + '">'+data+'</a>'
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/refunds/edit/'+ full.refund_id + '?invoice_number=' + convertToSlug(full.invoice_number) + '">' + data + '</a>'
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        if (data !== null) {
                            return '<img src="' + data + '" height="100" width="100">';
                        }
                    }
                },
                {
                    targets: 8,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        if (data !== null) {
                            return '<img src="' + data + '" height="100" width="100">';
                        } else {
                            return "";
                        }
                    }
                },
                {
                    targets: 9,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        if (data !== null) {
                            return '<img src="' + data + '" height="100" width="100">';
                        } else {
                            return "";
                        }
                    }
                },
                {
                    targets: 10,
                    className: "text-center",
                    searchable: true,
                    render: function(data, type, full, meta) {
                        if (data !== null) {
                            return '<video width="200" height="67" controls> <source src="' + full.video + '" type="video/mp4"> Your browser does not support the video tag. </video>';
                        }
                    }
                },
                {
                    targets: 11,
                    className: "text-center",
                },
                {
                    targets: 12,
                    className: "text-center",
                },
            ],
        });
    }
}

function convertToSlug(text) {
    return text.toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '-');
}



$('.refundStatus').change(function() {
    var isHotpoint = $('#refund_type').val();
    if (this.value == 5 && isHotpoint == 'hotpoint') {
        $('#hotpoint_view').show();
    } else {
        $('#hotpoint_view').hide();
    }
 })

function refund(refund_id) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: { refund_id: refund_id },
        url  : '/admin/refunds/approve',
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
                    location.href = "admin/refunds";
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
}

$(document).ready(function() {
    init_data_table();
});
