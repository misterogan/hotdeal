import Vue from "vue";
import moment from 'moment'
Vue.use(moment)

Vue.filter("capitalize",  function(value)  {
  if  (!value)  {
    return  "";
  }
  value  =  value.toString();
  return  value.charAt(0).toUpperCase()  +  value.slice(1);
});

Vue.filter("RupiahFormat",  function(value)  {

  if(isNaN(value)){
    return 'Rp 0';
  }
  let val = (value/1).toFixed(0).replace('.', ',')
    return 'Rp '+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
});

Vue.filter("PointFormat",  function(value)  {

  if(isNaN(value)){
    return '0';
  }
  let val = (value/1).toFixed(0).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
});

Vue.filter("DateFormat",  function(date)  {
  return moment(date).format('DD , MMMM YYYY');
});

Vue.filter("FormatHours",  function(date)  {
  return moment(date).format('kk : mm');
});

Vue.filter("NumberFormat",  function(value)  {

  if(isNaN(value)){
    return 'Rp 0';
  }
  let val = (value/1).toFixed(0).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
});

// Vue.filter("COPY_TEXT" , function(text){
    

// })

Vue.filter("JSONSTRINGIFY" , function(value , key){
    let data = JSON.parse(value)
    return data[key];
})

Vue.filter("JSONSTRINGIFY_LOGISTIC" , function(value , key){
    let data = JSON.parse(value)
    let logistic = data['logistic']
    return logistic[key];
})

Vue.filter("JSONSTRINGIFY_INSURANCE" , function(value , key){
    let data = JSON.parse(value)
    let logistic = data['consignee']['courier']['rate_id']['detail']
    return logistic[key];
})

Vue.filter("SHIPPING" , function(value , key){
  let data = JSON.parse(value)
  return data.logistic.name;
})


// Vue.mixin({
//   methods : {
//     ResponseValidation(response){
//         if(response.status === 400){
//           alert('Silahkan login terlebih dahulu');
//         }
//     }
//   }
// })
