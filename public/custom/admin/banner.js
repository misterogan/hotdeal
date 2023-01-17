$("#create_banner_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/banner/create',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){
            if(data.status === true) {
                swal.hideLoading();
                swal.fire({
                    text: data.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    location.href = "admin/banner";
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

$("#btn_cancel").on("click", function() {
    location.href = '/admin/banner';
});

$("#edit_banner_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/banner/edit',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){
            if(data.status === true) {
                swal.hideLoading();
                swal.fire({
                    text: data.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    location.href = "admin/banner";
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

// $('#kt_datatable_search_query').change(function() {
//     init_data_table_with_filter();
// });

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

// $('#kt_datatable_search_button').click(function() {
//     let search = $('#kt_datatable_search_query').val();
//     // table.search(search).draw();
//     $('#dt_banners').dataTable().fnFilter(search);
// });
//
// $('#kt_datatable_search_status').change(function() {
//     let status = $('#kt_datatable_search_status').val();
//     table.column('status').search(status).draw();
// });
//
// function init_data_table_with_filter() {
//     let table = $('#dt_banners');
//     if (table != null) {
//         table.DataTable({
//             order:[2,'asc'],
//             responsive: {
//                 details: {
//                     renderer: function ( api, rowIdx, columns ) {
//                         var data = $.map( columns, function ( col, i ) {
//                             return col.hidden ?
//                                 '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
//                                 '<td>'+col.title+''+'</td> '+
//                                 '<td>'+col.data+'</td>'+
//                                 '</tr>' :
//                                 '';
//                         } ).join('');
//
//                         return data ?
//                             $('<table/>').append( data ) :
//                             false;
//                     },
//                 }
//             },
//             processing: true,
//             className: 'details-control',
//             responsivePriority: 1,
//             serverSide: true,
//             scrollX: true,
//             ajax: {
//                 url: '/banner/filter/dt',
//                 type: "GET",
//                 data: function ( d ) {
//                     d.myKey = "myValue";
//                     d._token = $('meta[name="csrf-token"]').attr('content');
//                     d.search = $('#kt_datatable_search_query').val();
//                     d.status = $('#kt_datatable_search_status').val();
//                 }
//             },
//             columns: [
//                 { defaultContent: '<td></td>' },
//                 { data: 'DT_RowIndex', name: 'DT_RowIndex'},
//                 { data: 'name', name: 'Name'},
//                 { data: 'type', name: 'Media Type'},
//                 { data: 'img_url', name: 'Image' },
//                 { data: 'sequence', name: 'Sequence' },
//                 { data: 'url', name: 'Deeplink' },
//                 { data: 'status', name: 'Status' },
//                 { data: 'created_by', name: 'Created By' },
//                 { data: 'created_at', name: 'Created At' },
//             ],
//             columnDefs: [
//                 {
//                     targets: 0,
//                     className: "text-right",
//                 },
//                 {
//                     targets: 1,
//                     className: "text-center",
//                 },
//                 {
//                     targets: 2,
//                     render: function (data, type, full, meta) {
//                         return '<a href="/admin/banner/edit/'+full.id+'">'+data+'</a>'
//                     }
//                 },
//                 {
//                     targets: 3,
//                     className: "text-center",
//                 },
//                 {
//                     targets: 4,
//                     className: "text-center",
//                     render: function(data, type, full, meta) {
//                         if (data !== null) {
//                             return '<img src="' + data + '" height="67" width="200">';
//                         } else {
//                             return '<video width="200" height="67" controls> <source src="' + full.video_url + '" type="video/mp4"> Your browser does not support the video tag. </video>';
//                         }
//                     }
//                 },
//                 {
//                     targets: 5,
//                     className: "text-center",
//                 },
//                 {
//                     targets: 6,
//                 },
//                 {
//                     targets: 7,
//                     className: "text-center",
//                 },
//                 {
//                     targets: 8,
//                     className: "text-center",
//                 },
//                 {
//                     targets: 9,
//                     className: "text-center",
//                     render: function(data, type, full, meta) {
//                         return to_date_time(data);
//                     }
//                 },
//             ],
//         });
//     }
// }
//
// let table;

function init_data_table() {
    let table = $('#dt_banners');
    if (table != null) {
        table.DataTable({
            order:[9,'desc'],
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
                url: '/banner/dt',
                type: "GET",
                data: function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'type', name: 'type'},
                { data: 'img_url', name: 'img_url' },
                { data: 'sequence', name: 'sequence' },
                { data: 'url', name: 'url' },
                { data: 'status', name: 'Status' },
                { data: 'created_by', name: 'created_by' },
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
                        return '<a href="/admin/banner/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    className: "text-center",
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        if (data !== null) {
                            return '<img src="' + data + '" height="67" width="200">';
                        } else {
                            return '<video width="200" height="67" controls> <source src="' + full.video_url + '" type="video/mp4"> Your browser does not support the video tag. </video>';
                        }
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
                },
                {
                    targets: 6,
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
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 11,
                },
            ],
        });
    }
}

function init_data_table_product_discounts() {
    let table = $('#dt_discount_products');
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
                url: '/discount/product/detail/edit/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'product.name', name: 'Product Name'},
                { data: 'variant_key_1', name: 'Variant Key 1' },
                { data: 'variant_value_1', name: 'Variant Value 1'},
                { data: 'variant_key_2', name: 'Variant Key 2' },
                { data: 'variant_value_2', name: 'Variant Value 2'},
                { data: 'price', name: 'Price'},
                { data: 'stock', name: 'Stock'},
                { data: 'created_by', name: 'Created By' },
                { data: 'created_at', name: 'Created At' },
                { data: 'product_discount.id', name: 'Action'}
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
                },
                {
                    targets: 3,
                },
                {
                    targets: 4,
                },
                {
                    targets: 5,
                },
                {
                    targets: 6,
                },
                {
                    targets: 7,
                },
                {
                    targets: 8,
                },
                {
                    targets: 9,
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
                    render: function (data,type,row) {
                        if (data == true) {
                            return '<input type="checkbox" checked>';
                        } else {
                            return '<input type="checkbox">';
                        }
                        return data;
                    }
                },
            ],
        });
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        alert('select a file to see preview');
        $('#imagePreview').attr('src', '');
    }
}

$("#imageUpload").change(function() {
    readURL(this);
});

try {
    document.getElementById("videoUpload").onchange = function (event) {
        let file = event.target.files[0];
        let blobURL = URL.createObjectURL(file);
        document.getElementById("videoPreview").src = blobURL;
    }
} catch (e) {
    console.log(e);
}

$(document).ready(function() {
    init_data_table();
    init_data_table_product_discounts();
});

$(".delete_image").on("click", function (event) {
    var span = $(this)
    var id = span.data('id')
    // console.log(id);
    var token = $('meta[name="csrf-token"]').attr('content');
    // let id = $(this).val();
    $.ajax(
        {
            headers: { 'X-CSRF-TOKEN': token },
            method: "POST",
            url: '/admin/banner/delete-image',
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

