var config = {
    map: {
        '*': {
            dsShopbyFilterAbstract: 'Dotsquares_Shopby/js/dsShopby',
            dsShopbyFilterItemDefault: 'Dotsquares_Shopby/js/components/dsShopbyFilterItemDefault',
            dsShopbyFilterDropdown: 'Dotsquares_Shopby/js/components/dsShopbyFilterDropdown',
            dsShopbyFilterFromTo: 'Dotsquares_Shopby/js/components/dsShopbyFilterFromTo',
            dsShopbyFilterHideMoreOptions: 'Dotsquares_Shopby/js/components/dsShopbyFilterHideMoreOptions',
            dsShopbyFilterAddTooltip: 'Dotsquares_Shopby/js/components/dsShopbyFilterAddTooltip',
            dsShopbyFilterCategoryDropdown: 'Dotsquares_Shopby/js/components/dsShopbyFilterCategoryDropdown',
            dsShopbyFilterCategory: 'Dotsquares_Shopby/js/components/dsShopbyFilterCategory',
            dsShopbyFilterContainer: 'Dotsquares_Shopby/js/components/dsShopbyFilterContainer',
            dsShopbyFilterSearch: 'Dotsquares_Shopby/js/components/dsShopbyFilterSearch',
            dsShopbyFilterMultiselect: 'Dotsquares_Shopby/js/components/dsShopbyFilterMultiselect',
            dsShopbyFilterSwatch: 'Dotsquares_Shopby/js/components/dsShopbyFilterSwatch',
            dsShopbyFilterSlider: 'Dotsquares_Shopby/js/components/dsShopbyFilterSlider',
            dsShopbyFilterFlyout: 'Dotsquares_Shopby/js/components/dsShopbyFilterFlyout',
            dsShopbySwiperSlider: 'Dotsquares_Shopby/js/components/dsShopbySwiperSlider',
            dsShopbySwatchTooltip: 'Dotsquares_Shopby/js/components/dsShopbySwatchTooltip',
            dsShopbyFilterCollapse: 'Dotsquares_Shopby/js/components/dsShopbyFilterCollapse',
            dsShopbySwatchesChoose: 'Dotsquares_Shopby/js/dsShopbySwatchesChoose',
            dsShopbyFiltersSync: 'Dotsquares_Shopby/js/dsShopbyFiltersSync',
            dsShopbyApplyFilters: 'Dotsquares_Shopby/js/dsShopbyApplyFilters',
            dsShopbyTopFilters: 'Dotsquares_Shopby/js/dsShopbyTopFilters',
            dsShopbyAjax: 'Dotsquares_Shopby/js/dsShopbyAjax'
        }
    },
    deps: [
        'Dotsquares_Shopby/js/dsShopbyResponsive',
        'Dotsquares_Shopby/js/dsShopby'
    ],
    paths: {
        'swiper': 'Dotsquares_ShopbyBase/js/swiper.min'
    },
    shim: {
        'swiper': {
            deps: ['jquery']
        }
    }
};
