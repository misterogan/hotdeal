/*
    
WhatsApp:
https://api.whatsapp.com/send?text=[post-title] [post-url]

Facebook:
https://www.facebook.com/sharer.php?u=[post-url]

Twitter:
https://twitter.com/share?url=[post-url]&text=[post-title]&via=[via]&hashtags=[hashtags]

*/

// const whatsappBtn = document.querySelector('.whatsapp-btn');
// const facebookBtn = document.querySelector('.facebook-btn');
// const twitterBtn = document.querySelector('.twitter-btn');

// function init() {
//     let postUrl = encodeURI(document.location.href);
//     let postTitle = encodeURI("Hai, dapatkan barang ini hanya di hotdeal: ");

//     whatsappBtn.setAttribute(
//         "href", 
//         `https://api.whatsapp.com/send?text=${postTitle} ${postUrl}`
//     );

//     facebookBtn.setAttribute(
//         "href", 
//         `https://www.facebook.com/sharer.php?u=${postUrl}`
//     );

//     twitterBtn.setAttribute(
//         "href", 
//         `https://twitter.com/share?url=${postUrl}&text=${postTitle}`
//     );
// }

// init();


// var AddThisJSLoaded = false; //AddThis not loaded yet
// var clickedOnShare = false; //share button is not clicked

// //replace with your publisher ID
// //it's in your profile setting
// var AddThisPubID = "ra-5b056b31a239b6eb";
// var AddThisJS = "//s7.addthis.com/js/300/addthis_widget.js#pubid=" + AddThisPubID;

// function share(button) {
//   if (navigator.share) {
//     //check if Web Share API is supported by the browser    
//     var url = document.location.href;
//     var title = document.getElementsByTagName("title")[0].innerHTML;
//     var description = document
//       .querySelector("meta[name=description]")
//       .getAttribute("content");

//     navigator.share({
//       title: title,
//       text: description,
//       url: url
//     });
//   } else {
//     if (!AddThisJSLoaded && !clickedOnShare) {
//       //when AddThis not loaded and share button isn't clicked
//       clickedOnShare = true; //share button is clicked
//       showLoading(button);
//       shareByAddThis(button);
//     } else {
//       toggleAddThisButtons(button);
//     }
//   }
// }

// function shareByAddThis(button) {
//   var script = document.createElement("script");
//   script.async = true;
//   script.src = AddThisJS;

//   script.onload = function() {
//     clickedOnShare = false; //AddThis JS is loaded
//     addthis.user.ready(function(data) {
//       AddThisJSLoaded = true; //AddThis loaded and ready to use
//       hideLoading(button);
//       showAddThisButtons(button);
//     });
//   };

//   script.onerror = function() {
//     clickedOnShare = false; //AddThis JS failed to load
//     hideLoading(button);
//   };

//   document.body.appendChild(script);
// }

// function showLoading(button) {
//   button.classList.add("loading");
// }

// function hideLoading(button) {
//   button.classList.remove("loading");
// }

// function showAddThisButtons(button) {
//   button.classList.add("showAddThisButtons");
// }

// function toggleAddThisButtons(button) {
//   button.classList.toggle("showAddThisButtons");
// }
