$(document).ready(function() {
    init_data_table();
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

$("#btn_cancel").on("click", function() {
    location.href = '/admin/coupon';
});

function init_data_table() {
    let table = $('#dt_coupon');
    if (table != null) {
        table.DataTable({
            order:[7,'desc'],
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,
            ajax: {
                url: '/admin/coupon/get',
                type: "GET",
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'coupon_name', name: 'coupon_name'},
                { data: 'start_date', name: 'start_date'},
                { data: 'expired_date', name: 'expired_date'},
                { data: 'total_coupon', name: 'total_coupon'},
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at'},
                { data: 'created_by', name: 'created_by'},
            ],
            columnDefs: [

                {
                    targets: 2,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/coupon/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
            ]
        });
    }
}
function get_code(id) {

    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        dataType: 'JSON',
        data: {
            id_partner:id
        },
        url  : '/admin/partner/get/code',

        success: function(data){

            $("#serial_code").val(data.code);

        }
    });
}


$("#create_coupon_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        statusCode: {
            500: function() {
                alert("Script exhausted");
            }
        },
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/coupon/create',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false, beforeSend: function() {
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
                    location.href = "admin/coupon";
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
        },

    });
});


function init_data_detail_table() {
    var coupon_id = $("#coupon_id").val();
    let table = $('#dt_coupon_detail');
    if (table != null) {
        table.DataTable({
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,

            ajax: {
                url: '/admin/coupon/detail/get',
                data: {
                    coupon_id:coupon_id
                },
                type: "GET",
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'code', name: 'code'},
                { data: 'email', name: 'email'},
                { data: 'claim_date', name: 'claim_date'},
                { data: 'buy_date', name: 'buy_date'},
                { data: 'isActive', name: 'isActive'},
                { data: 'status', name: 'status'},
            ],
            columnDefs: [
                {
                    targets: 8,
                    searchable: true,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return '<a onclick="disable_coupon('+full.id+');" class="btn btn-icon btn-light btn-hover-primary btn-sm">\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<span class="svg-icon svg-icon-md svg-icon-primary">\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg-->\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<rect x="0" y="0" width="24" height="24"></rect>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</g>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</svg>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<!--end::Svg Icon-->\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</span>\n' +
                            '\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t</a>'
                    }
                },
            ]
        });
    }
}

$("#create_coupon_edit_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/coupon/edit',
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
                    location.href = "admin/coupon/edit/"+data.id;
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

function disable_coupon(id){
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: {
            id_coupon_detail:id
        },
        url  : '/admin/coupon/detail/disable',
        dataType: 'JSON',
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
