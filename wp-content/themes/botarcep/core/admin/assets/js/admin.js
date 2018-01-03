jQuery(document).ready(function ($) {
    $('.delete-confirm').on('click', function (e) {
        e.preventDefault();
        if (confirm('Voulez-vous supprimer ?')) {
            window.location.href = $(this).attr('url');
        }
    });
    $('.subscriber-rubrique-select').select2();

    $('.suscriber-meta-form').on('submit', function(e){
        e.preventDefault();
        var mydata=$('.suscriber-meta-form').serialize();
        $.post({
            url: client_data.ajax_url,
            data: {
                action: 'set_subscriber_meta_data',
                values:mydata,
            },
            dataType: 'JSON',
            beforeSend: function () {
                $('.meta-submit-btn').attr('disabled', '');
            },
            success: function (response) {
                console.log(response);
                $('.meta-submit-btn').removeAttr('disabled');
                $('#meta_update_message').removeClass('hidden');
                setTimeout(function(){
                    $('#meta_update_message').addClass('hidden');
                }, 3000);
            }

        });
    });

    $('.rubrique-form').on('submit', function (e) {
        e.preventDefault();
        var rubriques = $('.subscriber-rubrique-select').val();
        var mydata=$('.rubrique-form').serialize();
        console.log(mydata);
        // return;
        $.post({
            url: client_data.ajax_url,
            data: {
                action: 'set_subscriber_data',
                values:mydata,
                rubrique:rubriques
            },
            dataType: 'JSON',
            beforeSend: function () {
                $('.rubrique-submit-btn').attr('disabled', '');
            },
            success: function (response) {
                console.log(response);
                $('.rubrique-submit-btn').removeAttr('disabled');
                $('#rubrique_update_message').removeClass('hidden');
                setTimeout(function(){
                    $('#rubrique_update_message').addClass('hidden');
                }, 3000);
            }

        });
    });
});


