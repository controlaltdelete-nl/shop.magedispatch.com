<?php
/*
 *     ______            __             __
 *    / ____/___  ____  / /__________  / /
 *   / /   / __ \/ __ \/ __/ ___/ __ \/ /
 *  / /___/ /_/ / / / / /_/ /  / /_/ / /
 *  \______________/_/\__/_/   \____/_/
 *     /   |  / / /_
 *    / /| | / / __/
 *   / ___ |/ / /_
 *  /_/ _|||_/\__/ __     __
 *     / __ \___  / /__  / /____
 *    / / / / _ \/ / _ \/ __/ _ \
 *   / /_/ /  __/ /  __/ /_/  __/
 *  /_____/\___/_/\___/\__/\___/
 *
 * Copyright www.controlaltdelete.dev
 */

declare(strict_types=1);

namespace ControlAltDelete\NoticeBar\Block;

use ControlAltDelete\NoticeBar\Config;
use Magento\Framework\View\Element\Template;

class NoticeBar extends Template
{
    public function __construct(
        Template\Context $context,
        private readonly Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getText(): string
    {
        return $this->config->getNoticeText();
    }

    public function getTextAlign(): string
    {
        return $this->config->getTextAlign();
    }

    protected function _toHtml(): string
    {
        if (!$this->config->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}
