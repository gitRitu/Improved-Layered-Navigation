var config = {
    map: {
        '*': {
            navigationJs: 'Dotsquares_Megamenu/js/navigation_js'
        }
    },
    config: {
        mixins: {
            'mage/menu': {
                'Dotsquares_Megamenu/js/menu-mixin': true
            },
            'Magento_Theme/js/view/breadcrumbs': {
                'Dotsquares_Megamenu/js/breadcrumbs-mixin': true
            }
        }
    }
};
