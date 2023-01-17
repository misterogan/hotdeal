$("#btn_cancel").on("click", function() {
    location.href = '/admin/invites';
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
    let table = $('#dt_invite_users');
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
                url: '/admin/invite/user/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            order: [[ 9, 'desc' ]],
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'email', name: 'email'},
                { data: 'registration_source', name: 'Registration Source'},
                { data: 'email_verified_at', name: 'Email Verified At'},
                { data: 'image', name: 'Image'},
                { data: 'is_vendor', name: 'Is Vendor'},
                { data: 'status', name: 'Status'},
                { data: 'created_at', name: 'created_at'},
                { data: 'referal_code', name: 'referal_code'},
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
                        return '<a href="/admin/user/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    searchable: true,
                },
                {
                    targets: 4,
                },
                {
                    targets: 5,
                    className: 'text-center',
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 6,
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
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 10,
                    searchable: true,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        if (data) {
                            return '<a href="/admin/invite/'+data+'" class="btn btn-danger">'+'View Childs'
                        } else {
                            return '';
                        }
                    }
                },
            ],

        });
    }
}

function init_data_invited_table() {
    var segments      = location.pathname.split('/');
    getCode = segments[segments.length - 1];
    
    let table = $('#dt_invited_user');
    if (table != null) {
        table.DataTable({
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
                url: '/admin/invited/user',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.code = getCode;
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'email', name: 'email'},
                { data: 'created_at', name: 'created_at'},
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
                        return '<a href="/admin/user/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    searchable: true,
                },
                {
                    targets: 4,
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
            ],

        });
    }
}

function init_data_rules_table() {
    let table = $('#dt_invite_rules');
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
                url: '/admin/invite/rules/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            order: [[ 8, 'desc' ]],
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'start_date', name: 'start_date'},
                { data: 'end_date', name: 'end_date'},
                { data: 'count_from', name: 'count_from'},
                { data: 'count_after', name: 'count_after'},
                { data: 'percentage', name: 'percentage'},
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at'},
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
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 3,
                    searchable: true,
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 4,
                    searchable: true,
                    className: 'text-center',
                },
                {
                    targets: 5,
                    className: 'text-center',
                },
                {
                    targets: 6,
                    searchable: true,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/invite/rule/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 7,
                    className: "text-center",
                },
                {
                    targets: 8,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
            ],

        });
    }
}

$("#create_rule_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/invite/rules/submit',
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
                    location.href = "/admin/hotdeal-invite/rules";
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


$("#edit_rule_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/invite/rules/update',
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
                    location.href = "/admin/hotdeal-invite/rules";
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

$(document).ready(function() {
    init_data_table();
    init_data_invited_table();
    init_data_rules_table();
});