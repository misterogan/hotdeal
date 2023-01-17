$("#create_promotion_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/discount/create',
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
                    location.href = "admin/discount/edit/" + data.id;
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

function toRupiah(value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

$("#btn_cancel").on("click", function() {
    location.href = '/admin/discount';
});

$("#edit_discount_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/discount/edit',
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
    let table = $('#dt_discounts');
    if (table != null) {
        table.DataTable({
            order:[7,'desc'],
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
                url: '/discount/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'vendor.name', name: 'vendor.name'},
                { data: 'status', name: 'Status' },
                { data: 'start_date', name: 'Start Date'},
                { data: 'end_date', name: 'End Date' },
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
                        return '<a href="/admin/discount/edit/'+full.id+'">'+data+'</a>'
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
                        return to_date_time(data);
                    }
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
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 8,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 9,
                }
            ],
        });
    }
}

function init_data_table_product_discounts() {
    let table = $('#dt_discount_products');
    if (table != null) {
        table.DataTable({
            order:[2,'desc'],
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
                url: '/discount/set/dt',
                type: "GET",
                data: function ( d ) {
                    d.vendor_id = $('#vendor_id').val(); //replace dynamic value
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'name', name: 'name'},
                { data: 'detail_hasone.price',  name: 'Price'},
                { data: 'discount.value_discount', name: 'Discount'},
                { data: 'discount.value_discount', name: 'Nominal Discount'},
                { data: 'discount.value_discount', name: 'Discounted Price'},

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
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta) {
                        const price = parseInt(data);
                        const admin_fee = parseInt(full.admin_fee);
                        const total = price + admin_fee;
                        return isNaN(total) ? toRupiah(0) : toRupiah(total);
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        const price = parseInt(full.detail_hasone != null ? full.detail_hasone.price : 0);
                        const admin_fee = parseInt(full.admin_fee);
                        const total = price + admin_fee;
                        const percent = ((total - (total - data)) / total) * 100;
                        const discounted = data === undefined ? '' : total - data;
                        const percentage = isNaN(percent) ? '' : percent;

                        return '<div class="input-group col-md-8 mb-2">\n' +
                        '  <div class="input-group-append">\n' +
                        '    <span class="input-group-text" id="basic-addon2">Rp</span>\n' +
                        '  </div> '+
                        '  <input type="text" class="form-control product-discount nominal-discount-'+full.id+'" placeholder="Product\'s discount" aria-label="Product\'s discount" aria-describedby="basic-addon2" data-productid="' + full.id + '" value="' + discounted + '">\n' +
                        '</div>' +
                        '<div class="input-group col-md-8">\n' +
                        '  <input type="text" class="form-control product-discount percentage-discount-'+full.id+'" placeholder="Product\'s discount" aria-label="Product\'s discount" maxlength="2" aria-describedby="basic-addon2" data-productid="' + full.id + '" value="' + Math.ceil(percentage) + '">\n' +
                        '  <div class="input-group-append">\n' +
                        '    <span class="input-group-text" id="basic-addon2">%</span>\n' +
                        '  </div>\n' +
                        '</div>';
                        
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        if (isNaN(data)) {                        
                            return '<div class="discounted-price"><p id="price_discount" class="real-price-'+full.id+'" name="price_discount"><p></div>';
                        }
                        return '<div class="discounted-price"><p id="price_discount" class="real-price-'+full.id+'" name="price_discount">' + toRupiah(data) + '<p></div>';
                    }
                },
                {
                    targets: 6,
                    render: function(data, type, full, meta) {
                        const price = parseInt(full.detail_hasone != null ? full.detail_hasone.price : 0);
                        const admin_fee = parseInt(full.admin_fee);
                        const total_price = price + admin_fee;
                        const value = total_price - data;
                        if (isNaN(value)) {
                            return '<div class="discounted-price"><p id="price_discount" name="price_discount"  class="real-discount-'+full.id+'"><p></div>';
                        }
                        return '<div class="discounted-price"><p id="price_discount" name="price_discount"  class="real-discount-'+full.id+'">' + toRupiah(value) + '<p></div>';
                    }
                },
            ],
        });
    }

    table.on('change', '.product-discount', function (e) {
        e.preventDefault();
        var token = $('meta[name="csrf-token"]').attr('content');
        var id = $('#id').val();
        var vendorId = $('#vendor_id').val();
        var valueDiscount = $(this).val();
        var productId = $(this).data("productid");


        $.ajax({
            headers: { 'X-CSRF-TOKEN': token },
            type : 'POST',
            url  : '/discount/set',
            data: { vendor_id: vendorId, value_discount: valueDiscount, product_id: productId, id: id },
            success: function(res) {
                var data = JSON.parse(res);
                if(data.status === true) {
                    swal.fire({
                        text: data.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                    console.log(data)
                    $('.real-discount-'+productId).html(data.price)
                    $('.real-price-'+productId).html(data.total_discount)
                    $('.percentage-discount-'+productId).prop("value", data.percentage); 
                    $('.nominal-discount-'+productId).prop("value", data.nominal); 
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



}

$(document).ready(function() {
    init_data_table();
    init_data_table_product_discounts();
});
