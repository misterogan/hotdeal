$("#btn_cancel").on("click", function() {
    location.href = '/admin/rejeki-nomplok/coupons';
});
$("#create_master_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/faq/create',
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
                    location.href = "admin/faq";
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
$("#edit_strength_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/strengths/edit',
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
                    location.href = "admin/strengths";
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
$("#edit_master_form").on("submit", function (event) {
    event.preventDefault(); 
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/rejeki-nomplok/master/edit',
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
                    location.href = "admin/rejeki-nomplok/master";
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
function check_rejeki_nomplok_winner(status){
    if(status){
        $('#ihsg_copy').val(null)
    }else{
        $('#ihsg_copy').val($('#ihsg').val())
    }
    let table = $('#dt_coupons').DataTable();
    table.ajax.reload();
}
function init_data_table() {
    let table = $('#dt_coupons');
    if (table != null) {
        table.DataTable({
            order:[1,'asc'],
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
                url: '/rejeki-nomplok/coupons/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.week = $('#week').val() !== undefined ? $('#week').val() : null,
                    d.ihsg = $('#ihsg_copy').val()
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'user.name', name: 'user.name'},
                { data: 'week.week', name: 'week.week'},
                { data: 'order.invoice_number', name: 'order.invoice_number'},
                { data: 'product.name', name: 'product.name'},
                { data: 'coupon_number', name: 'coupon_number'},
                { data: 'status', name: 'status'},
                { data: 'created_at', name: 'created_at' },
                { data: 'price', name: 'price' },
                { data: 'coupon_number', name: 'coupon_number' },
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
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/user/edit/'+full.user.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    
                },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/order/edit/'+ replace_string(full.order.invoice_number) +'">'+data+'</a>'
                    }
                },
                {
                    targets: 5,
                    className: "text-center",
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
                    // render: function(data, type, full, meta) {
                    //     return to_date_time(data);
                    // }
                },
                {
                    targets: 10,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        if(data == $('#coupon_winner').val() && full.has_send_point != true){
                            return '<button type="button" class="btn btn-primary btn-sm send-point" coupon="'+full.id+'" char="'+full.price+'">Kirim</button>';
                        }else{
                            return ''
                        }

                    }
                },
            ],
        });
    }
}
function replace_string(str){
    return str.replaceAll('/' , '-');
}
$(document).ready(function() {
    init_data_table();
    $(document).on('click', '.send-point' , function(){
        var _this = $(this)
        var token = $('meta[name="csrf-token"]').attr('content');
        var point = $(this).attr('char');
        var id = $(this).attr('coupon')
        Swal.fire({
            title: 'Are you sure?',
            text: "Kamu akan mengirimkan poin sebesar  "+ point,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Kirim!'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': token },
                    type : 'POST',
                    dataType:"JSON",
                    data: {id : id},
                    url  : '/admin/rejeki-nomplok/send/point',
                    success: function(response){
                        if(response.status === true) {
                            Swal.fire(
                                'Success!',
                                'Poin telah dikirim.',
                                'success'
                            )
                            _this.remove()
                        }else {
                            Swal.fire(
                                'Ooopss!',
                                response.message,
                                'error'
                            )
                        }
                    }
                });
                
            }
          })
    })
});