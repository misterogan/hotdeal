$('#apply_to_all_product').change(
    function() {
        if (this.checked) {
            $('#kt_select2_4').prop('disabled', true);
        } else {
            $('#kt_select2_4').prop('disabled', false);
        }
    }
);

var counter_1 = 2;
var counter_2 = 2;
var is_addVariant = $('#is_addVariant').val() === 'true';
let removed_image = [];
if (is_addVariant) {
    $('.variant-2').show();
    $('#add_variation').html('Tutup Variasi 2');
    $('.tableVariantName2').show();
    $('.tableVariantValue2').show();
    is_addVariant = false;
    $('#have_variant_2').val("true");
}
 
$('#add_value_1').click(function() {
    variant_1.variant.push({option : '' , price : 0 , stock : 0});
    //alert('a');
    generate_variant_1();
    //detailVariant()
    //var variant_1 =  { label : '' , variant :[]};

    // var valueHTML = '<div class="form-group row"> <label class="col-lg-3 col-form-label">Pilihan:</label> <div class="col-lg-6"> <input type="text" name="variant_value_1[]" data-id="' + counter_1 + '" class="form-control variant_value_1" placeholder="Enter Variant Value"/> </div> <button type="button" onclick="remove(this)" class="close">X</button></div>';
    // $('.values-1').append(valueHTML);
    // var img_value_1 = '<div class="col-3">' +
    //     '<div class="image-input image-input-outline" id="kt_image_20'+counter_1+'">' +
    //     '<div class="image-input-wrapper" style="background-image: url(\'assets/media/misc/ic_add.png\');"></div>' +
    //     '<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">' +
    //     '<i class="fa fa-pen icon-sm text-muted"></i>' +
    //     '<input type="file" name="variant_image_200[]" id="variant_image_200" accept=".png, .jpg, .jpeg, .svg" />' +
    //     '<input type="hidden" name="profile_avatar_remove" />' +
    //     '</label>' +
    //     '<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">' +
    //     '<i class="ki ki-bold-close icon-xs text-muted"></i>' +
    //     '</span>' +
    //     '</div>' +
    //     '</div>';
    // $('#variant_images').append(img_value_1);
    // counter_1++;
});

$(document).on('change' , '#variant_key_1' , function(){
    variant_1.label = $(this).val()
    detailVariant()
})


$(document).on('change' , '#variant_key_2' , function(){
    variant_2.label = $(this).val()
    detailVariant()
})

function generate_variant_1(){
    var valueHTML = ''
    for (let i = 0; i < Object.keys(variant_1.variant).length; i++) {
         valueHTML += '<div class="form-group row"> <label class="col-lg-3 col-form-label">Pilihan:</label> <div class="col-lg-6"> <input type="text" name="variant_value_1[]" onchange="update_variant_1($(this))"  data-id="'+i+'" value="'+variant_1.variant[i].option+'"  class="form-control variant_value_1" placeholder="Enter Variant Value"/> </div> <button type="button" onclick="remove_variant_1($(this))" data-id="'+i+'" class="close">X</button></div>';
    }
    $('#variant_key_1').val(variant_1.label)
    $('.values-1').html(valueHTML);
    
}

function generate_variant_2(){
    var valueHTML = ''
    for (let i = 0; i < Object.keys(variant_2.variant).length; i++) {
         valueHTML += '<div class="form-group row"> <label class="col-lg-3 col-form-label">Pilihan:</label> <div class="col-lg-6"> <input type="text" name="variant_value_2[]" onchange="update_variant_2($(this))"  data-id="'+i+'" value="'+variant_2.variant[i].option+'"  class="form-control variant_value_1" placeholder="Enter Variant Value"/> </div> <button type="button" onclick="remove_variant_2($(this))" data-id="'+i+'" class="close">X</button></div>';
    }
    $('#variant_key_2').val(variant_2.label)
    $('.values-2').html(valueHTML);
}


$('#add_value_2').click(function() {
    variant_2.variant.push({option : '' , price : 0 , stock : 0});
    generate_variant_2()
    //detailVariant()
    // var valueHTML = '<div class="form-group row"> <label class="col-lg-3 col-form-label">Pilihan:</label> <div class="col-lg-6"> <input type="text" name="variant_value_2[]" data-id="' + counter_2 + '" class="form-control variant_value_2" placeholder="Enter Variant Value"/> </div> <button type="button" onclick="remove(this)" class="close">X</button></div>';
    // $('.values-2').append(valueHTML);
    // counter_2++;
});

$('#add_variation').click(function() {

    if (is_addVariant) {
        variant_2 = { label : '' , variant :[]};
        $('.variant-2').hide();
        $('#add_variation').html('Tambah Variasi 2');
        $('.tableVariantName2').hide();
        $('.tableVariantValue2').hide();
        is_addVariant = false;
        $('#have_variant_2').val("true");
    } else {
        $('.variant-2').show();
        $('#add_value_2').click()
        $('#add_variation').html('Tutup Variasi 2');
        $('.tableVariantName2').show();
        $('.tableVariantValue2').show();
        is_addVariant = true;
        $('#have_variant_2').val("false");
    }
    generate_variant_2()
    detailVariant()

});

function remove(el) {
    $(el).parent().remove();
}

function saveState() {
    $('#createVariantTable tr').each(function(){
        $(this).find('td').each(function(){
            //do your stuff, you can use $(this) to get current cell
           // console.log($(this));
        })
    })
}
function remove_variant_1(_this){
    variant_1.variant.splice(parseInt(_this.attr('data-id')) , 1)
    //this.form.variant_2.variant.splice(parsetInt(_this.attr('data-id')) , 1)
    generate_variant_1()
    detailVariant();
}
function remove_variant_2(_this){
    variant_2.variant.splice(parseInt(_this.attr('data-id')) , 1)
    if(Object.keys(variant_2.variant).length < 1){
        $('#add_variation').click();
    }
    generate_variant_2()
    detailVariant();
}
function change_price_variant(_this){
    //alert(p.details_variant[_this.attr('data-id')].price)
    p.details_variant[_this.attr('data-id')].price = _this.val()
}
function change_sku_variant(_this){
    p.details_variant[_this.attr('data-id')].sku = _this.val()
}
function change_quantity_variant(_this){
    p.details_variant[_this.attr('data-id')].stock = _this.val()
}
function update_variant_1(_this){
    variant_1.variant[parseInt(_this.attr('data-id'))].option = _this.val()
    detailVariant();
}

function update_variant_2(_this){
    variant_2.variant[parseInt(_this.attr('data-id'))].option = _this.val()
    detailVariant();
}

function generate_table_2(){
    var text = '';
    var headTable = '';
    var contentTable = '';
    var footTable = '';
    var fullTable = '';
    var variantData = [];
    if(Object.keys(variant_2.variant).length > 0 ){
        headTable = '<table class="table table-bordered" id="createVariantTable">'+
                        '<tr>'+
                        '<th class="tableVariantName1">' + variant_1.label + '</th> '+
                        '<th class="tableVariantName1">' + variant_2.label + '</th> '+
                        '<th>Price</th> '+
                        '<th>Stock</th> '+
                        '<th>Variation Code</th> '+
                        '</tr>';
        for (let i = 0; i < Object.keys(variant_1.variant).length; i++) {
            for (let j = 0; j < Object.keys(variant_2.variant).length; j++) {
                //let prev_value = p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option] != undefined ? p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option] : 
                contentTable += '<tr> <td>' + variant_1.variant[i].option + 
                                '</td><td>' + variant_2.variant[j].option + 
                                '</td><td>Rp. <input type="text" data-id="'+variant_1.variant[i].option+'_'+variant_2.variant[j].option+'" onchange="change_price_variant($(this))" placeholder="Price" name="price_' + i+'_'+j + '" value="'+ p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option].price+'"/> </td><td> <input type="number" data-id="'+variant_1.variant[i].option+'_'+variant_2.variant[j].option+'" onchange="change_quantity_variant($(this))"  placeholder="Stock" name="stock_' + i+'_'+j + '" value="'+p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option].stock+'"/> </td><td> <input type="text" placeholder="Variation Code" name="code_' + i+'_'+j + '" value="'+p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option].sku+'" data-id="'+variant_1.variant[i].option+'_'+variant_2.variant[j].option+'" onchange="change_sku_variant($(this))"/> </td></tr>';
            }
        }
        
        footTable = '</table> </div>';

        fullTable = headTable + contentTable + footTable;

        $('.variant-table').html(fullTable);
    }else{
        headTable = '<table class="table table-bordered" id="createVariantTable">'+
                        '<tr>'+
                        '<th class="tableVariantName1">' + variant_1.label + '</th> '+
                        '<th>Price</th> '+
                        '<th>Stock</th> '+
                        '<th>Variation Code</th> '+
                        '</tr>';
        for (let i = 0; i < Object.keys(variant_1.variant).length; i++) {
            contentTable += '<tr> <td>' + variant_1.variant[i].option + 
                            '</td><td>Rp. <input type="text" data-id="'+variant_1.variant[i].option+'_" onchange="change_price_variant($(this))" placeholder="Price" name="price_' + i + '" value="'+ p.details_variant[variant_1.variant[i].option+'_'].price+'"/> </td><td> <input type="number" data-id="'+variant_1.variant[i].option+'_" onchange="change_quantity_variant($(this))"  placeholder="Stock" name="stock_' + i + '" value="'+p.details_variant[variant_1.variant[i].option+'_'].stock+'"/> </td><td> <input type="text" placeholder="Variation Code" name="code_' + i+'" value="'+p.details_variant[variant_1.variant[i].option+'_'].sku+'" data-id="'+variant_1.variant[i].option+'_" onchange="change_sku_variant($(this))"/> </td></tr>';
        }
        
        footTable = '</table> </div>';

        fullTable = headTable + contentTable + footTable;

        $('.variant-table').html(fullTable);
    }
    
    console.log(text);
}

function image_of_variant(){
    var valueHTML = ''
    for (let i = 0; i < Object.keys(variant_1.variant).length; i++) {
         var image = variant_1.variant[i].picture != null ? variant_1.variant[i].picture : 'assets/media/misc/ic_add.png';
         valueHTML += ' <div data-id="'+(i+100)+'" class="image-input image-input-outline ml-6 kt_image_'+(i+100)+'">'+
         '<input type="hidden" class="image_of_variant_'+(i+100)+'" name="image_'+(i+100)+'_id" data-id="'+(i+100)+'">'+
         '<div class="image-input-wrapper" id="preview_image_variant_'+(i+100)+'" style="background-image: url('+image+');"></div>'+
         '<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-id="'+(i+100)+'" data-toggle="tooltip" title="" data-original-title="Change Image">'+
             '<i class="fa fa-pen icon-sm text-muted"></i>'+
             '<input type="file" onchange="change_image($(this) , '+i+')" data-id="'+(i+100)+'"  name="image_'+(i+100)+'" id="image" accept=".png, .jpg, .jpeg, .svg, .webp" />'+
             '<input type="hidden" name="profile_avatar_remove" />'+
         '</label>'+
         '<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"  data-toggle="tooltip"  data-id="'+(i+100)+'" title="Remove" onclick="removeImageVariant($(this) , '+i+')">'+
             '<i class="ki ki-bold-close icon-xs text-muted"></i>'+
         '</span>'+
         '<span class="form-text text-muted">'+variant_1.variant[i].option+'</span>'+
     '</div>';
    }
    $('#variant_images').html(valueHTML);

}

$(document).on('click' , '.trigger_change_of_image' , function(){
   // alert('a')
    _id = $(this).attr('data-id')
    //$('.image_of_variant_'+_id).click()
})

function change_image(_this , id){
    _id = _this.attr('data-id')
    var token = $('meta[name="csrf-token"]').attr('content');
    const file = _this.get(0).files[0];
    const fd = new FormData();
    fd.append('image' , file)
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: fd,
        url  : '/admin/product/image/variant',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success: function(response){
            if(response.status == 'success'){
                //console.log(variant_1);
                variant_1.variant[id].picture = response.url
                //console.log(variant_1)
            }else{
                alert(response.message);
            }
        }
    });
    if (file){
        let reader = new FileReader();
        reader.onload = function(event){
            $('#preview_image_variant_'+_id).css('background-image', "url("+reader.result+")");
        }
        reader.readAsDataURL(file);
    }
}

function removeImageVariant(_this , id){
    _id = _this.attr('data-id');
    variant_1.variant[id].picture = '';
    $('#preview_image_variant_'+_id).css('background-image', "url('/assets/media/misc/ic_add.png')");
    //console.log(variant_1.variant[id])
}

function detailVariant(){
    //console.log(p)
    //console.log(variant_1)
    //p.details_variant = [];
    for(let i=0; i < Object.keys(variant_1.variant).length; i++){
        if( Object.keys(variant_2.variant).length > 0){
            for(let j=0; j < Object.keys(variant_2.variant).length; j++){
                if(p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option] == undefined){
                   p.details_variant[variant_1.variant[i].option+'_'+variant_2.variant[j].option] = {option : '' , price : 0 , stock : 0 , sku : ''};
                }
            }
        }else{
            console.log(p.details_variant);
            if(p.details_variant[variant_1.variant[i].option+'_'] == undefined){
                p.details_variant[variant_1.variant[i].option+'_'] = {option : '' , price : 0 , stock : 0 , sku : ''};
            }
        }
    }
    generate_table_2();
    //console.log(p)
    image_of_variant()
    // generate_table_2()
    
    // console.log(variant_1);
    // console.log('a')
    // console.log(this.form)
}


function generateTable() {
    var variant_name_1 = $('#variant_key_1').val();
    var variant_name_2 = $('#variant_key_2').val();
    var variant_value_1_count = document.getElementsByClassName("variant_value_1").length;
    var variant_value_2_count = document.getElementsByClassName("variant_value_2").length;
    var variant_value_1 = document.getElementsByClassName("variant_value_1");
    var variant_value_2 = document.getElementsByClassName("variant_value_2");
    //console.log(variant_value_1_count + ' : ' + variant_value_2_count);

    var headTable = '';
    var contentTable = '';
    var footTable = '';
    var fullTable = '';
    var variantData = [];

    if (is_addVariant) {
        headTable = '<table class="table table-bordered" id="createVariantTable"> <tr> <th class="tableVariantName1">' + variant_name_1 + '</th> <th>Price</th> <th>Stock</th> <th>Variation Code</th> </tr>';

        for (var i = 0; i < variant_value_1_count; i++) {
            contentTable += '<tr><td>' + variant_value_1[i].value + '</td><td>Rp. <input type="text" placeholder="Price" name="price_' + i + '"/> </td><td> <input type="number" placeholder="Stock" name="stock_' + i + '"/> </td><td> <input type="text" placeholder="Variation Code" name="code_' + i + '"/> </td></tr>';
        }

        footTable = '</table> </div>';

        fullTable = headTable + contentTable + footTable;

        $('.variant-table').html(fullTable);
    } else {
        headTable = '<table class="table table-bordered" id="createVariantTable"> <tr> <th class="tableVariantName1">' + variant_name_1 + '</th> <th class="tableVariantName2">' + variant_name_2 + '</th> <th>Price</th> <th>Stock</th> <th>Variation Code</th> </tr>';

        var counter = 0;
        
        for (var j = 0; j < variant_value_1_count; j++) {
            for (var k = 0; k < variant_value_2_count; k++) {
                contentTable += '<tr> <td>' + variant_value_1[j].value + '</td><td>' + variant_value_2[k].value + '</td><td>Rp. <input type="text" placeholder="Price" name="price_' + counter + '"/> </td><td> <input type="number" placeholder="Stock" name="stock_' + counter + '"/> </td><td> <input type="text" placeholder="Variation Code" name="code_' + counter + '"/> </td></tr>';
                counter++;
            }
        }

        footTable = '</table> </div>';

        fullTable = headTable + contentTable + footTable;

        $('.variant-table').html(fullTable);
    }

    var imgHTML = '';
    var imgCounter = 2;

    for (var l = 0; l < variant_value_1_count; l++) {
        var imgComponent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="image-input image-input-outline" id="kt_image_10' + imgCounter + '"> <div class="image-input-wrapper" style="background-image: url(\'assets/media/misc/ic_add.png\');"></div><label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar"> <i class="fa fa-pen icon-sm text-muted"></i> <input type="file" name="image_10' + imgCounter + '" id="image_10' + imgCounter + '" accept=".png, .jpg, .jpeg, .svg"/> <input type="hidden" name="profile_avatar_remove"/> </label> <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar"> <i class="ki ki-bold-close icon-xs text-muted"></i> </span> </div>';
        imgHTML += imgComponent;
        imgCounter++;
    }

    $('.variant-images').html(imgHTML);
}

function loadTable() {

}

//Update selling price logic
$('#admin_fee').change(
    function() {
        var adminFee = parseInt($('#admin_fee').val());
        var setPrice = 0;
        var is_variant = $('#is_variant').val();

        if (is_variant === "1") {
            var table = document.getElementById("variantTable");
            var rowCount = table.rows.length;
            var colCount = document.getElementById("variantTable").rows[0].cells.length;


            for (var r = 1; r < rowCount; r++) {

                for (var c = 0; c < colCount; c++) {
                    if (c === 2) {
                        setPrice = parseInt(table.rows[r].cells[c].innerHTML);
                    }

                    if (c === 3) {
                        const cell = table.rows[r].cells[c];
                        cell.innerHTML = adminFee + setPrice;
                    }
                }
            }
        } else {
            setPrice = parseInt($('#price').val());
            $('#sell').val(toRupiah(adminFee + setPrice));
        }
    }
);

$('input[type=radio][name=option]').change(function() {
    if (this.value === 'single') {
        $('#single').show();
        $('#variant').hide();
    }
    else if (this.value === 'variant') {
        $('#variant').show();
        $('#single').hide();
        $('#add_value_1').click()
    }
});

$(document).on('click' , '.remove-image-primary' , function(){
    //alert($(this).attr('data-image'))
    if(!removed_image.includes($(this).attr('data-image'))){
        removed_image.push($(this).attr('data-image'))
    }
   $(this).parent('.image-input-outline').find('.image-input-wrapper').css('background-image', "url('/assets/media/misc/ic_add.png')");
})

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
    location.href = '/admin/product';
});

function toRupiah(value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

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
    let table = $('#dt_products');
    if (table != null) {
        table.DataTable({
            order:[8,'desc'],
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
                url: '/product/dt',
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
                { data: 'vendor.name', name: 'vendor.name'},
                { data: 'category.category', name: 'category.category' },
                { data: 'sku', name: 'SKU'},
                { data: 'stock', name: 'total_stock'},
                { data: 'status', name: 'Status'},
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
                        return '<a href="/admin/product/edit/'+full.id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/vendor/edit/'+full.vendor_id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 4,
                    searchable: true,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/category/edit/'+full.category_id+'">'+data+'</a>'
                    }
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
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
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
                },
            ],
        });
    }
}

function init_log_data_table() {
    var segments      = location.pathname.split('/');
    getId = segments[segments.length - 1];
    let table = $('#dt_log_product');
    if (table != null) {
        table.DataTable({
            order:[4,'desc'],
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
                url: '/product/log/dt',
                type: "GET",
                data: function ( d ) {
                    d.myKey = "myValue";
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.product_id = getId;
                }
            },
            columns: [
                { defaultContent: '<td></td>' },
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'product.name', name: 'name'},
                { data: 'compare', name: 'compare'},
                { data: 'updated_at', name: 'updated_at' },
                { data: 'type', name: 'type' },
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
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/product/edit/'+full.product_id+'">'+data+'</a>'
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        return '<a href="/admin/product/changes/'+full.id+'">'+"View Changes"+'</a>'
                    }
                },
                {
                    targets: 4,
                    searchable: true,
                    className: "text-center",
                    render: function(data, type, full, meta) {
                        return to_date_time(data);
                    }
                },
                {
                    targets: 5,
                    searchable: true,
                },
                {
                    targets: 5,
                    searchable: true,
                },
            ],
        });
    }
}

$("#edit_product_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    formData.append('variant_1' , JSON.stringify(variant_1))
    formData.append('deleted_image' , removed_image)
    formData.append('variant_2' , JSON.stringify(variant_2))
    formData.append('details_variant' , JSON.stringify(p.details_variant))
    
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/product/edit',
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
                    location.href = "admin/product";
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

$("#create_product_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    formData.append('variant_1' , JSON.stringify(variant_1))
    formData.append('variant_2' , JSON.stringify(variant_2))
    formData.append('details_variant' , JSON.stringify(p.details_variant))
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/product/create',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){
            console.log(data)
            console.log(data.status)
            swal.hideLoading()
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
                    location.href = "admin/product";
                });
            } else if(data.message === 'Undefined index: picture') {
                swal.fire({
                    text: "Cek ulang semua data!",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                }).then(function() {
                    $('.from-prevent-multiple-submits').removeAttr('disabled');
                });
            } else {
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
                }).then(function() {
                    $('.from-prevent-multiple-submits').removeAttr('disabled');
                });
            }
        }
    });
});

$(".delete_image").on("click", function (event) {
    // console.log($(this).parent().find('div:nth-child(1)').css('background-image', 'url("assets/media/misc/ic_add.png")'));
    var span = $(this)
    var id = span.data('id')
    // console.log(id);
    var token = $('meta[name="csrf-token"]').attr('content');
    // let id = $(this).val();
    $.ajax(
        {
            headers: { 'X-CSRF-TOKEN': token },
            method: "POST",
            url: '/admin/product/delete-image',
            data: {
                image_id : id,
                _token : token
            }
        }
    ).then(function() {
        location.reload();
        $('')
    });
});


$("#upload_product_form").on("submit", function (event) {
    event.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    var formData = new FormData(this);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type : 'POST',
        data: formData,
        url  : '/admin/product/mass/upload',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            swal.showLoading();
        },
        success: function(data){
            swal.hideLoading()
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
                    location.href = "admin/product";
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
    init_log_data_table();
});
