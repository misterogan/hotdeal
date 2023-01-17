<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function setCookie(Request $request) {
        if ( isset($_GET['utm_id']) && trim($_GET['utm_id']) != "") {
            $utm_id = trim($_GET['utm_id']);
            setcookie('utm_id', $utm_id, time() + 60 * 60 * 24 * 365); // the validity period of ~ 1 year
        }

        if ( isset($_GET['utm_source']) && trim($_GET['utm_source']) != "") {
            $utm_source = trim($_GET['utm_source']);
            setcookie('utm_source', $utm_source, time() + 60 * 60 * 24 * 365); // the validity period of ~ 1 year
        }

        if ( isset($_GET['utm_campaign']) && trim($_GET['utm_campaign']) != "") {
            $utm_campaign = trim($_GET['utm_campaign']);
            setcookie('utm_campaign', $utm_campaign, time() + 60 * 60 * 24 * 365); // the validity period of ~ 1 year
        }

        if ( isset($_GET['utm_term']) && trim($_GET['utm_term']) != "") {
            $utm_term = trim($_GET['utm_term']);
            setcookie('utm_term', $utm_term, time() + 60 * 60 * 24 * 365); // the validity period of ~ 1 year
        }
        if ( isset($_GET['utm_medium']) && trim($_GET['utm_medium']) != "") {
            $utm_medium = trim($_GET['utm_medium']);
            setcookie('utm_medium', $utm_medium, time() + 60 * 60 * 24 * 365); // the validity period of ~ 1 year
        }
     }
     
     public function getCookie(Request $request) {
        echo $_COOKIE['utm_source'];
        echo $_COOKIE['utm_campaign'];
     }
}
