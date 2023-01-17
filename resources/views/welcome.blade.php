<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>{{$title}} </title>
        @env('production')
        <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','GTM-5W89XP7');
            </script>
        <!-- End Google Tag Manager -->
        @endenv
        <meta property="og:url" content="{{request()->url()}}">
        <meta property="og:type" content="commerce" />
        <meta property="og:title" content="{{$title}}" />
        <meta property="og:description" content="{{ $description }}" />
        <meta property="og:image" content="{{$image}}" />

        <meta name="title" content="{{$title}}">
        <meta name="description" content="{{ $description }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{request()->url()}}">
        <meta property="og:title" content="{{$title}}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:image" content="{{$image}}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{request()->url()}}">
        <meta property="twitter:title" content="{{$title}}">
        <meta property="twitter:description" content="{{ $description }}">
        <meta property="twitter:image" content="{{$image}}">
        <!-- Meta tag for facebook pixel code-->
        <meta name="facebook-domain-verification" content="hd9wrvx8ev9sp84jxvbj56cw5ar761" />
        <!-- End Meta Tag-->

        <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/png">
        <!-- Fonts -->
        @if (request()->segment(1) != 'seller')
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800&family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

            <link rel="stylesheet" href="{{asset('/css/app.css?v='.\App\Helpers\Utils::cdn_version())}}">
        @endif

        @if (request()->segment(1) == 'seller')
        {{-- SELLER CSS --}}
        <link rel="stylesheet" href="{{asset('seller/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('seller/css/plugins.css')}}">
        <script>var hostUrl = "assets/";</script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{asset('assets/seller/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('assets/seller/assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Vendors Javascript(used by this page)-->
		<script src="{{asset('assets/seller/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
		<!--end::Page Vendors Javascript-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="{{asset('assets/seller/assets/js/custom/widgets.js')}}"></script>
		<script src="{{asset('assets/seller/assets/js/custom/apps/chat/chat.js')}}"></script>
		<script src="{{asset('assets/seller/assets/js/custom/modals/create-app.js?v='.\App\Helpers\Utils::cdn_version())}}"></script>
		<script src="{{asset('assets/seller/assets/js/custom/modals/upgrade-plan.js')}}"></script>
        {{-- END SELLER CSS --}}
        @endif
        <script src="{{asset('/js/app.js?v='.\App\Helpers\Utils::cdn_version())}}" defer></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-40J5HFEYRC"></script>
        {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=GTM-5W89XP7"></script> --}}
        <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyC-zPIfwqqllqd4f4yngXK8dLV_WCaddK0"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-40J5HFEYRC');
        </script>

        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1558651724508158'); 
            fbq('track', 'PageView');
        </script>
    <noscript>
    <img height="1" width="1" 
    src="https://www.facebook.com/tr?id=1558651724508158&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    </head>
    <body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-enabled">
        @env('production')
            <!-- Google Tag Manager (noscript) -->
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5W89XP7"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endenv
        <script>
            window.fbAsyncInit = function() {
            FB.init({
                appId      : '379978210247237',
                cookie     : true,  // enable cookies to allow the server to access 
                                    // the session
                xfbml      : true,  // parse social plugins on this page
                version    : 'v14.0' // Specify the Graph API version to use
            });
            }
        </script>
        <div id="app">
            <app></app>
        </div>
    </body>
</html>
