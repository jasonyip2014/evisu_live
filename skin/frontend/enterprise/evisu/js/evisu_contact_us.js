var ContactUs = {
    config : [],

    init : function(config)
    {
        this.config = config;
    },

    sendData : function(form)
    {
        var self = this;
        var msg   = form.serialize();
        jQuery('sbm-btn').attr('disabled','disabled');
        jQuery('form-ajax-loader').show();
        jQuery.ajax(
        {
            type: 'post',
            url: form.attr('action'),
            data: msg,

            success: function(data)
            {
                jQuery('#ajax-result').html(data);
                jQuery('sbm-btn').removeAttr('disabled');
                jQuery('form-ajax-loader').hide();
            },
            error:  function(xhr, str)
            {
                jQuery('#ajax-result').html('Unknown error: ' + str);
                jQuery('sbm-btn').removeAttr('disabled');
                jQuery('form-ajax-loader').hide();
            }
        });
    }
};

jQuery(function($)
{
    jQuery('#contactForm').submit(function()
    {
        if(contactForm.validator.validate())
        {
            ContactUs.sendData(jQuery(this));
        }
        return false;
    });
});