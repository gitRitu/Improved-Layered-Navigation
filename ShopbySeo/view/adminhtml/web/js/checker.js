define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'domReady'
], function ($, modalConfirm) {
    'use strict';

    $.widget('mage.dsConfigChecker', {
        options: {
            contentSelector: '#ds_checker_message',
            fieldsSelector: '#dotsquares_shopby_seo_url_special_char, #dotsquares_shopby_seo_url_option_separator'
        },

        _create: function () {
            $(this.options.fieldsSelector).on('change', function (e) {
                var specialChar = $('#dotsquares_shopby_seo_url_special_char').val(),
                    separator = $('#dotsquares_shopby_seo_url_option_separator').val();
                if (specialChar == separator) {
                    modalConfirm({
                        title: $.mage.__('Attention'),
                        content: $(this.options.contentSelector).html(),
                    });
                }
            }.bind(this));
        }

    });

    return $.mage.dsConfigChecker;
});
