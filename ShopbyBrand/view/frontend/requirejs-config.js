var config = {
    map: {
        '*': {
            dsBrandsSearch: 'Dotsquares_ShopbyBrand/js/components/dsbrands-search',
            dsBrandsFilterInit: 'Dotsquares_ShopbyBrand/js/components/dsbrands-filter-init',
            dsBrandsFilter: 'Dotsquares_ShopbyBrand/js/brand-filter'
        }
    },
    paths: {
        'swiper': 'Dotsquares_ShopbyBase/js/swiper.min',
    },
    shim: {
        'swiper': {
            deps: ['jquery']
        }
    },
    config: {
        mixins: {
            'mage/menu': {
                'Dotsquares_ShopbyBrand/js/lib/mage/dsbrands-menu-mixin': true
            }
        }
    }
};
