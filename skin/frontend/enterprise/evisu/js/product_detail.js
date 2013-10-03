/* Product Detail Page Scripts */
var MailToFriend = {
    showModalWindow : function(url){
        console.log(url);
        jQuery.arcticmodal({
            type: 'ajax',
            url: url,
            ajax: {
                type: 'GET',
                cache: false,
                dataType: 'html',
                success: function(data, el, responce) {
                    //console.log(responce);
                    var content = jQuery('<div class="box-modal">' +
                        '<div class="box-modal_close arcticmodal-close">X</div>' +
                        '<p><b /></p><p />' +
                        '</div>');
                    //$('B', h).html(responce.title);
                    jQuery('P:last', content).html(jQuery(responce).find('.col-main'));
                    data.body.html(content);
                }
            }
        });
    }
};

jQuery(function($){
    // mail to friend //
    jQuery('#send-to-friend-btn').on('click', function() {
        console.log(jQuery(this).attr('href'));
        MailToFriend.showModalWindow(jQuery(this).attr('href'));
        return false;
    });

});
