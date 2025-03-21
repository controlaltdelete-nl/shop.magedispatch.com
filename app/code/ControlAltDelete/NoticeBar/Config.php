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

namespace ControlAltDelete\NoticeBar;

use ControlAltDelete\NoticeBar\Model\Config\Source\TextAlign;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_ENABLED = 'noticebar/general/enabled';
    private const XML_PATH_NOTICE_TEXT = 'noticebar/general/notice_text';
    private const XML_PATH_TEXT_ALIGN = 'noticebar/general/text_align';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    public function isEnabled(?string $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getNoticeText(?string $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NOTICE_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getTextAlign(?string $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_TEXT_ALIGN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: TextAlign::ALIGN_LEFT;
    }
}
