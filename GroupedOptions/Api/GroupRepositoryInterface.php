<?php

namespace Dotsquares\GroupedOptions\Api;

interface GroupRepositoryInterface
{
    const TABLE = 'dotsquares_grouped_options_group';
    const TABLE_OPTIONS = 'dotsquares_grouped_options_group_option';
    const TABLE_VALUES = 'dotsquares_grouped_options_group_value';

    /**
     * @param $groupCode
     * @return false or array
     */
    public function getGroupOptionsIds($groupCode);
}
