$("#btn_cancel").on("click", function() {
    location.href = '/admin/file';
});

$("#create_file_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/file/submit',
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
                    location.href = "admin/file";
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
    let table = $('#dt_file');
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
                url: 'admin/file/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'link', name: 'link'},
                { data: 'link', name: 'link'},
                { data: 'created_at', name: 'Created At' },
                { data: 'created_by', name: 'Created By' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    className: "text-right",
                },
                {
                    targets: 1,
                    className: "text-right",
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return '<a class="btn btn-primary" onclick="myFunction('+full.id+')"><i class="ki ki-copy"></i></a> <input type="hidden" name="link" id="link'+full.id+'" value='+data+'>'

                    }
                },
                {
                    targets: 3,
                },
                {
                    targets: 4,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 4,
                    className: "text-center",
                },
            ],
        });
    }
}

$(document).ready(function() {
    init_data_table();
});
