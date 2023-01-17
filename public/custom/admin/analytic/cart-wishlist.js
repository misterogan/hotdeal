$(document).ready(function() {
    _cart_wishlist();
    _by_product_cart_wishlist();
});

var _cart_wishlist = function () {
    const apexChart = "#_cart_wishlist";
    var options = {
        series: [{
            name: "Desktops",
            data: []
        }],
        noData : {
            text : 'Loading'
        },
        chart: {
            type: 'area',
            zoom: {
                enabled: false
            }
        },
        dataLabels: { 	
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: [],
            labels: {
                show: false,
            },
        },
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();

    $.ajax({
        url: "/admin/analytic/cart-wishlist",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            date_from: $("#date_from").val(),
            date_until: $("#date_until").val(),
        },
        success: function(data) {
            chart.updateSeries([
                {
                    name: 'Cart',
                    data: data.cart
                },
                {
                    name: 'Wishlist',
                    data: data.wishlist
                }
            ])
              chart.updateOptions({
                    xaxis: {
                        categories: data.timestamp,
                    }
                })
        }
    });
}

var _by_product_cart_wishlist = function () {
    const apexChart = "#_by_product_cart_wishlist";
    var options = {
        series: [{
            name: "Desktops",
            data: []
        }],
        noData : {
            text : 'Loading'
        },
        chart: {
            type: 'area',
            zoom: {
                enabled: false
            }
        },
        dataLabels: { 	
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: [],
            labels: {
                show: false,
            },
        },
    };

    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();

    $.ajax({
        url: "/admin/analytic/cart-wishlist",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            console.log(data)
            chart.updateSeries([
                {
                    name: 'Cart',
                    data: data.cartProducts
                },
                {
                    name: 'Wishlist',
                    data: data.wishlistProducts
                }
            ])
              chart.updateOptions({
                    xaxis: {
                        categories: data.products,
                    }
                })
        }
    });
}

