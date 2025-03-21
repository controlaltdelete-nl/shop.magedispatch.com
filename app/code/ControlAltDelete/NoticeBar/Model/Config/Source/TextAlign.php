<?php

declare(strict_types=1);

namespace ControlAltDelete\NoticeBar\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class TextAlign implements OptionSourceInterface
{
    public const ALIGN_LEFT = 'left';
    public const ALIGN_CENTER = 'center';
    public const ALIGN_RIGHT = 'right';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::ALIGN_LEFT, 'label' => __('Left')],
            ['value' => self::ALIGN_CENTER, 'label' => __('Center')],
            ['value' => self::ALIGN_RIGHT, 'label' => __('Right')]
        ];
    }
}