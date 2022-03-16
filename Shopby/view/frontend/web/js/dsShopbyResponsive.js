
define([
    'jquery',
    'matchMedia',
    'dsShopbyTopFilters',
    'mage/tabs',
    'domReady!'
], function ($, mediaCheck, dsShopbyTopFilters) {
    'use strict';

    mediaCheck({
        media: '(max-width: 768px)',
        entry: function () {
            dsShopbyTopFilters.moveTopFiltersToSidebar();
        },

        exit: function () {

            dsShopbyTopFilters.removeTopFiltersFromSidebar();
        }
    });
});
