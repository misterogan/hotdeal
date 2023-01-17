<template>
    <button class="button" @click="logInWithFacebook"><img src="img/login_fb.svg" alt=""></button>
</template>

<script>
    export default {
        name:"FacebookLogin",
        methods: {
            async logInWithFacebook() {
                await this.loadFacebookSDK(document, "script", "facebook-jssdk");
                await this.initFacebook();
                window.FB.login(function(response) {
                    if (response.authResponse) {
                        alert("You are logged in &amp; cookie set!");
                        // Now you can redirect the user or do an AJAX request to
                        // a PHP script that grabs the signed request from the cookie.
                        if (response.status === "connected") {
                            window.FB.api('/me', { locale: 'id_ID', fields: 'name, email' },
                                function(res) {
                                }
                            );
                        }
                    } else {
                        alert("User cancelled login or did not fully authorize.");
                    }
                }, {scope: 'public_profile,email'});
                return false;
            },
            async initFacebook() {
                window.fbAsyncInit = function() {
                    window.FB.init({
                        appId: "4359320090854673", //You will need to change this
                        cookie: true, // This is important, it's not enabled by default
                        version: "v13.0"
                    });
                };
            },
            async loadFacebookSDK(d, s, id) {
                var js,
                    fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }
        }
    };
</script>
