$(function(){
    $('.minus-btn').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('.qty');
        var value = parseInt($input.val());

        if (value > 1) {
            value = value - 1;
        } else {
            value = 0;
        }

        $input.val(value);

    });

    $('.plus-btn').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('.qty');
        var value = parseInt($input.val());

        if (value < 100) {
        value = value + 1;
        } else {
            value =100;
        }

        $input.val(value);
        });

});

$(function() {
    const shareButton = document.querySelector('.btn-share');
    const shareDialog = document.querySelector('.share-dialog');
    const closeButton = document.querySelector('.close-button');

    if (shareButton!==null){
        shareButton.addEventListener('click', function(e) {
            if (navigator.share) {
            navigator.share({
                title: 'WebShare API Demo',
                url: 'https://hotdeal.dev/product-detail/nihi-tali-pendek-rajut-anyam'
                }).then(() => {
                console.log('Thanks for sharing!');
                })
                .catch(console.error);
                } else {
                    shareDialog.classList.add('is-open');
                }
            });
        }
    if(closeButton!==null){
        closeButton.addEventListener('click', event => {
            shareDialog.classList.remove('is-open');
        });
    }
});
