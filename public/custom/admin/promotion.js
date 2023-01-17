$('#apply_to_all_product').change(
    function() {
        if (this.checked) {
            $('#kt_select2_4').prop('disabled', true);
        } else {
            $('#kt_select2_4').prop('disabled', false);
        }
    }
);

$('#apply_to_all_user').change(
    function() {
        if (this.checked) {
            $('#kt_select2_1').prop('disabled', true);
        } else {
            $('#kt_select2_1').prop('disabled', false);
        }
    }
);

$("#btn_cancel").on("click", function() {
    location.href = '/admin/promotion';
});

$("#create_promotion_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/promotion/create',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            swal.showLoading();
        },
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
                    location.href = "admin/promotion";
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


$("#edit_promotion_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/promotion/edit',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            swal.showLoading();
        },
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
                    location.href = "admin/promotion";
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


$("#promotion_banner").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/promotion/banner',
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
                    location.reload();
                    // location.href = "admin/rejeki-nomplok/master";
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

function init_data_table() {
    let table = $('#dt_promotions');
    if (table != null) {
        table.DataTable({
            order:[13,'desc'],
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
                url: '/promotion/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'voucher_name', name: 'voucher_name'},
                { data: 'voucher_description', name: 'Description'},
                { data: 'image', name: 'Image' },
                { data: 'minimum_payment', name: 'Minimum Payment'},
                { data: 'maximum_promo', name: 'Maximum Promo'},
                { data: 'discount_type', name: 'Discount Type'},
                { data: 'value_discount', name: 'Value Discount'},
                { data: 'status', name: 'Status'},
                { data: 'start_date', name: 'Start Date'},
                { data: 'end_date', name: 'End Date' },
                { data: 'created_by', name: 'Created By' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'updated_by', name: 'updated_by' },
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
                        return '<a href="/admin/promotion/edit/'+full.voucher_code+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<img src="'+data+'" height="100" width="100">'
                    }
                },
                {
                    targets: 5,

                },
                {
                    targets: 6,
                    className: "text-center",
                },
                {
                    targets: 7,
                    className: "text-center",
                },
                {
                    targets: 8,
                    className: "text-center",
                },
                {
                    targets: 9,
                    className: "text-center",
                },
                {
                    targets: 10,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 11,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 12,
                    className: "text-center",
                },
                {
                    targets: 13,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 14,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 15,
                },
            ],
        });
    }
}

$(document).ready(function() {
    init_data_table();
});

$(".delete_image").on("click", function (event) {
    var span = $(this)
    var id = span.data('id')
    console.log(id);
    var token = $('meta[name="csrf-token"]').attr('content');
    // let id = $(this).val();
    $.ajax(
        {
            headers: { 'X-CSRF-TOKEN': token },
            method: "POST",
            url: '/admin/promotion/delete-image',
            data: {
                data_id : id,
                _token : token
            }
        }
    ).then(function() {
        location.reload();
        $('')
    });
});
