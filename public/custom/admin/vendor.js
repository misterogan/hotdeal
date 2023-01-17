$("#btn_cancel").on("click", function() {
    location.href = '/admin/vendor';
});


//Province Select
function get_city(province_id) {
    let token = $('meta[name="csrf-token"]').attr('content');
    $("#kt_select2_103").empty();
    $("#kt_select2_104").empty();
    $("#kt_select2_105").empty();
    $('#kt_select2_103').prop('disabled', false);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token
        },
        url: '/city/select2?province_id=' + province_id,
        type: "GET",
        dataType: 'json',
        success: function (res) {
            $("#kt_select2_103").empty();
            $("#kt_select2_103").append('<option>Pilih Kota</option>');
            $.each(res, function (key, value) {
                $("#kt_select2_103").append('<option value="' + key + '">' + value + '</option>');
            });
        },
        error: function (xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err);
        }
    })
}

function get_district(city_id) {
    let token = $('meta[name="csrf-token"]').attr('content');
    $("#kt_select2_104").empty();
    $('#kt_select2_104').prop('disabled', false);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token
        },
        url: '/suburb/select2?city_id=' + city_id,
        type: "GET",
        dataType: 'json',
        success: function (res) {
            $("#kt_select2_104").empty();
            $("#kt_select2_104").append('<option>Pilih Kecamatan</option>');
            $.each(res, function (key, value) {
                $("#kt_select2_104").append('<option value="' + key + '">' + value + '</option>');
            });
        },
        error: function (xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err);
        }
    })
}

function get_area(suburb_id) {
    let token = $('meta[name="csrf-token"]').attr('content');
    $("#kt_select2_105").empty();
    $('#kt_select2_105').prop('disabled', false);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token
        },
        url: '/area/select2?suburb_id=' + suburb_id,
        type: "GET",
        dataType: 'json',
        success: function (res) {
            $("#kt_select2_105").empty();
            $("#kt_select2_105").append('<option>Pilih Kelurahan</option>');
            $.each(res, function (key, value) {
                $("#kt_select2_105").append('<option value="' + key + '">' + value + '</option>');
            });
        },
        error: function (xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err);
        }
    })
}

$("#create_vendor_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/vendor/create',
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
                    location.href = "admin/vendor";
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

$("#edit_vendor_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/vendor/edit',
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
                    location.href = "admin/vendor";
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
    let table = $('#dt_vendor');
    if (table != null) {
        table.DataTable({
            order:[2,'asc'],
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
                url: '/vendor/dt',
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
                { data: 'user.name', name: 'User Name'},
                { data: 'image', name: 'Image'},
                { data: 'rating', name: 'Rating'},
                { data: 'status', name: 'Status'},
                { data: 'address', name: 'Address'},
                { data: 'province.name', name: 'Province Name' },
                { data: 'id', name: 'id' },
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
                        return '<a href="/admin/vendor/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<img src="'+data+'" height="100" width="100">'
                    }
                },
                {
                    targets: 8,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                      return data ? data : ''
                    }
                },
                {
                    targets: 9,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return '<a href="/admin/merchant/'+full.id+'" class="btn btn-success">'+'Manage'+'</a>'
                    }
                },

            ],
        });
    }
}


$("#edit_highlight").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/merchant/product/highlight',
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
                    location.reload()
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

$("#add_merchant_banner").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/merchant/banner',
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
                    location.reload()
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

function init_data_banner_table() {
    var segments      = location.pathname.split('/');
    getId = segments[segments.length - 1];
    let table = $('#dt_banner_merchant');
    if (table != null) {
        table.DataTable({
            order:[5,'desc'],
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
                url: '/admin/merchant/banner/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.vendor_id = getId;
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'img_url', name: 'Image'},
                { data: 'url', name: 'Url'},
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at'},
                { data: 'created_by', name: 'created_by'},
                { data: 'id', name: 'id'},
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
                        if (data != null) {
                            return '<img src="' + data + '" height="100" width="250">'
                        } else {
                            return ''
                        }
                    }
                },
                {
                    targets: 3,
                },
                {
                    targets: 4,
                    className: "text-center",
                },
                {
                    targets: 5,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 6,
                    className: "text-center",
                },
                {
                    targets: 7,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        return '<button type="submit" id="edit_highlight" class="btn btn-success mr-2" onclick="inactiveBanner('+data+')">Inactive</button>';
                    }
                }

            ],
        });
    }
}

function inactiveBanner(id, event) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax(
        {
            headers: { 'X-CSRF-TOKEN': token },
            method: "POST",
            url: '/admin/merchant/banner/inactive',
            data: {
                data_id : id,
                _token : token
            },
        }
    ).then(function() {
        location.reload();
    });
}

$(document).ready(function() {
    init_data_table();
    init_data_banner_table();

    if ($('#isEdit').val() === 'true') {
        initializeSelect2();
    }
});


