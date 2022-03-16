<?php
namespace Dotsquares\Megamenu\Plugin\Category;

class DataProvider extends \Dotsquares\Megamenu\Plugin\Category\DataProvider\DataProvider
{

    /**
     * Rewrite this in all subclassess, provide the list with category attributes
     * @return array
     */
    protected function _getFieldsMap() {
        return [
            'dotsquares_options' => [
                'dotsquares_category_url',
                'dotsquares_category_url_newtab',
                'dotsquares_sc_layout',
                'dotsquares_sc_columns',
                'dotsquares_sc_title_position',
                'dotsquares_sc_image',
                'dotsquares_sc_hide'
            ],
            'dotsquares_megamenu' => [
                'dotsquares_mm_display_mode',
                'dotsquares_mm_columns_number',
                'dotsquares_mm_column_width',
                'dotsquares_mm_top_block_type',
                'dotsquares_mm_top_block_cms',
                'dotsquares_mm_top_block',
                'dotsquares_mm_right_block_type',
                'dotsquares_mm_right_block_cms',
                'dotsquares_mm_right_block',
                'dotsquares_mm_bottom_block_type',
                'dotsquares_mm_bottom_block_cms',
                'dotsquares_mm_bottom_block',
                'dotsquares_mm_left_block_type',
                'dotsquares_mm_left_block_cms',
                'dotsquares_mm_left_block',
                'dotsquares_mm_mob_hide_allcat',
                'dotsquares_mm_font_color',
                'dotsquares_mm_font_hover_color',
                'dotsquares_mm_show_arrows',
                'dotsquares_mm_dynamic_sc_flag',
                'dotsquares_mm_dynamic_sc_opts',
                'dotsquares_mm_image_enable',
                'dotsquares_mm_image_height',
                'dotsquares_mm_image_width',
                'dotsquares_mm_image_name_align',
                'dotsquares_mm_image',
                'dotsquares_mm_label_text',
                'dotsquares_mm_label_font_color',
                'dotsquares_mm_label_background_color',
                'dotsquares_mm_label_position',
                'dotsquares_mm_image_alt',
                'dotsquares_mm_image_radius',
                'dotsquares_mm_image_position'

            ]
        ];
    }


}
