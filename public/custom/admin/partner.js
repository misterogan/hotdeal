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
    location.href = '/admin/partner';
});

function init_data_table() {
    let table = $('#dt_partner');
    if (table != null) {
        table.DataTable({
            order:[7,'desc'],
            processing: true,
            className: 'details-control',
            responsivePriority: 1,
            serverSide: true,
            ajax: {
                url: '/admin/partner/get',
                type: "GET",
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'partner_name', name: 'partner_name'},
                { data: 'image', name: 'image'},
                { data: 'partner_code', name: 'partner_code'},
                { data: 'status', name: 'status'},
                { data: 'show_in_footer', name: 'footer'},
                { data: 'created_at', name: 'created_at'},
            ],
            columnDefs: [
                {
                    targets: 2,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/partner/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data) {
                        return data
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        if (data != null) {
                            return '<img src="' + data + '" height="100" width="100">'
                        } else {
                            return '<img src="img/avatar_placeholder.jpeg" height="100" width="100">'
                        }
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        if(data == true){
                            $value = "Yes"
                        } else{
                            $value = "No"
                        }
                        return $value
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

$("#create_partner_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/partner/create',
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
                    location.href = "admin/partner";
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

$("#edit_partner_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/partner/edit',
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
                    location.href = "admin/partner";
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
