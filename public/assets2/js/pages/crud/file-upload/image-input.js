'use strict';

// Class definition
var KTImageInputDemo = function () {
	// Private functions
	var initDemos = function () {
		// Example 1
		var avatar1 = new KTImageInput('kt_image_1');

        var avatar11 = new KTImageInput('kt_image_11');

        var avatar12 = new KTImageInput('kt_image_12');

        var avatar13 = new KTImageInput('kt_image_13');

        var avatar13 = new KTImageInput('kt_image_14');

        var avatar13 = new KTImageInput('kt_image_15');

        var avatar13 = new KTImageInput('kt_image_16');

        var avatar13 = new KTImageInput('kt_image_17');

        var avatar13 = new KTImageInput('kt_image_18');

        var avatar13 = new KTImageInput('kt_image_19');

        var avatar13 = new KTImageInput('kt_image_20');

        var avatar101 = new KTImageInput('kt_image_101');
        var avatar102 = new KTImageInput('kt_image_102');
        var avatar103 = new KTImageInput('kt_image_103');
        var avatar104 = new KTImageInput('kt_image_104');
        var avatar105 = new KTImageInput('kt_image_105');
        var avatar106 = new KTImageInput('kt_image_106');
        var avatar107 = new KTImageInput('kt_image_107');
        var avatar108 = new KTImageInput('kt_image_108');
        var avatar109 = new KTImageInput('kt_image_109');
        var avatar110 = new KTImageInput('kt_image_110');
        var avatar111 = new KTImageInput('kt_image_111');
        var avatar112 = new KTImageInput('kt_image_112');
        var avatar113 = new KTImageInput('kt_image_113');
        var avatar114 = new KTImageInput('kt_image_114');
        var avatar115 = new KTImageInput('kt_image_115');
        var avatar116 = new KTImageInput('kt_image_116');
        var avatar117 = new KTImageInput('kt_image_117');
        var avatar118 = new KTImageInput('kt_image_118');
        var avatar119 = new KTImageInput('kt_image_119');
        var avatar120 = new KTImageInput('kt_image_120');

        var avatar200 = new KTImageInput('kt_image_200');
        var avatar201 = new KTImageInput('kt_image_201');
        var avatar202 = new KTImageInput('kt_image_202');
        var avatar203 = new KTImageInput('kt_image_203');
        var avatar204 = new KTImageInput('kt_image_204');
        var avatar205 = new KTImageInput('kt_image_205');
        var avatar206 = new KTImageInput('kt_image_206');
        var avatar207 = new KTImageInput('kt_image_207');
        var avatar208 = new KTImageInput('kt_image_208');
        var avatar209 = new KTImageInput('kt_image_209');
        var avatar210 = new KTImageInput('kt_image_210');
        var avatar211 = new KTImageInput('kt_image_211');
        var avatar212 = new KTImageInput('kt_image_212');
        var avatar213 = new KTImageInput('kt_image_213');
        var avatar214 = new KTImageInput('kt_image_214');
        var avatar215 = new KTImageInput('kt_image_215');
        var avatar216 = new KTImageInput('kt_image_216');
        var avatar217 = new KTImageInput('kt_image_217');
        var avatar218 = new KTImageInput('kt_image_218');
        var avatar219 = new KTImageInput('kt_image_219');
        var avatar220 = new KTImageInput('kt_image_220');

		// Example 2
		var avatar2 = new KTImageInput('kt_image_2');

		// Example 3
		var avatar3 = new KTImageInput('kt_image_3');

		// Example 4
		var avatar4 = new KTImageInput('kt_image_4');

		avatar4.on('cancel', function(imageInput) {
			swal.fire({
                title: 'Image successfully canceled !',
                type: 'success',
                buttonsStyling: false,
                confirmButtonText: 'Awesome!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});

		avatar4.on('change', function(imageInput) {
			swal.fire({
                title: 'Image successfully changed !',
                type: 'success',
                buttonsStyling: false,
                confirmButtonText: 'Awesome!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});

		avatar4.on('remove', function(imageInput) {
			swal.fire({
                title: 'Image successfully removed !',
                type: 'error',
                buttonsStyling: false,
                confirmButtonText: 'Got it!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});

		// Example 5
		var avatar5 = new KTImageInput('kt_image_5');

		avatar5.on('cancel', function(imageInput) {
			swal.fire({
                title: 'Image successfully changed !',
                type: 'success',
                buttonsStyling: false,
                confirmButtonText: 'Awesome!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});

		avatar5.on('change', function(imageInput) {
			swal.fire({
                title: 'Image successfully changed !',
                type: 'success',
                buttonsStyling: false,
                confirmButtonText: 'Awesome!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});

		avatar5.on('remove', function(imageInput) {
			swal.fire({
                title: 'Image successfully removed !',
                type: 'error',
                buttonsStyling: false,
                confirmButtonText: 'Got it!',
                confirmButtonClass: 'btn btn-primary font-weight-bold'
            });
		});
	}

	return {
		// public functions
		init: function() {
			initDemos();
		}
	};
}();

KTUtil.ready(function() {
	KTImageInputDemo.init();
});
