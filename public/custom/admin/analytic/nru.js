$(document).ready(function() {
   drawing_gender_chart();
});


function drawing_gender_chart(){
   $.ajax({
       url: "/admin/analytic/get/user/bio",
       type: "GET",
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },
       success: function(data) {
           var datos = data;
           var ctx = document.getElementById('canvas').getContext('2d');

           myChart = new Chart(ctx, {
               type: 'line',
               data : {
                   labels: [
                      'male','female','blank'
                   ],
                   datasets: [{
                       label: 'My First Dataset',
                       data: [data.male,data.female,data.blank],
                       backgroundColor: [
                           'rgb(255, 99, 132)',
                           'rgb(54, 162, 235)',
                           'rgb(224,255,255)'
                       ],
                       hoverOffset: 4
                   }]
               },
               options : {
                   responsive: true,
                   title: {
                       display: true,
                       position: "top",
                       text: "Last Week Registered Users -  Day Wise Count",
                       fontSize: 12,
                       fontColor: "#111"
                   },
                   legend: {
                       display: true,
                       position: "bottom",
                       labels: {
                           fontColor: "#333",
                           fontSize: 12
                       }
                   },

               },
               // chartArea: {width: 400, height: 300}
           });
       }
   });
}
