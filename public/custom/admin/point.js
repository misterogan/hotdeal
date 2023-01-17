$("#btn_send_point_members").on("click", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = $('#send_point_member').serialize();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: $('#send_point_member').serialize(),
        url  : '/admin/hotpoint/send/point',
        dataType: 'JSON',
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){

            if(data.status === true) {
                swal.fire({
                    text: 'Send Hotpoint Success!',
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    location.href = "/admin/hotpoint/send";
                });
            }
            else if(data.status === false) {
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
            }else{
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

$("#btn_create_hotpoint_code").on("click", function (event) {

    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = $('#hotpoint_code_form').serialize();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/hotpoint/code/create',
        dataType: 'JSON',
        beforeSend: function() {
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
                    location.href = "/admin/hotpoint/code";
                });
            }else{
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

function init_data_table_point() {
    let table = $('#dt_hotpoint_log');
    if (table != null) {
        table.DataTable({
            order:[6,'desc'],
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '/admin/hotpoint/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'email', name: 'email'},
                { data: 'amount', name: 'amount'},
                { data: 'description', name: 'description'},
                { data: 'created_by', name: 'created_at' },
                { data: 'created_at', name: 'created_at' },
            ],
            columnDefs: [
                {
                    targets: 6,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
            ]
        });
    }
}


function init_data_table() {
    let table = $('#dt_hotpoint');
    if (table != null) {
        table.DataTable({
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '/admin/hotpoint/code/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'code', name: 'Code'},
                { data: 'description', name: 'description'},
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at' },
            ],
            columnDefs: [
                {
                    targets: 2,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/hotpoint/code/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
            ]
        });
    }
}



$("#btn_edit_hotpoint_code").on("click", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = $('#hotpoint_code_edit_form').serialize();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/hotpoint/code/update',
        dataType: 'JSON',
        beforeSend: function() {
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
                    location.href = "/admin/hotpoint/code";
                });
            }else{
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


function request_otp(){
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = $('#hotpoint_code_edit_form').serialize();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/hotpoint/send/otp/point',
        dataType: 'JSON',
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){
            if(data.status === true) {
                swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                })
                $('#btn-request-otp').prop('disabled', true)
            }else{
                var values = '';
                jQuery.each(data.msg, function (key, value) {
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
                });
            }
        }
    });
}

$(document).ready(function() {
    init_data_table_point();
    init_data_table();
    $("#btn_cancel").on("click", function() {
        location.href = '/admin/hotpoint/send';
    });

});

