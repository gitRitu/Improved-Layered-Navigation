/**
 *  Dotsquares Filter Category Dropdown Component
 */

define([
    'jquery',
    'dsShopbyFiltersSync'
], function ($) {
    'use strict';

    $.widget('mage.dsShopbyFilterCategoryDropdown', $.mage.dsShopbyFilterAbstract, {
        options: {},
        classes: {
            itemRemoved: 'dsshopby-item-removed'
        },

        /**
         * @private
         * @return {void}
         */
        _create: function () {
            var self = this,
                element = self.element;

            element.click(function (e) {
                e.preventDefault();
                e.stopPropagation();

                element.parent().addClass(self.classes.itemRemoved);
                element.trigger('sync');
                self.renderShowButton(e, element);
                self.apply(element.data('remove-url'), true);
            });
        }
    });

    return $.mage.dsShopbyFilterCategoryDropdown;
});
