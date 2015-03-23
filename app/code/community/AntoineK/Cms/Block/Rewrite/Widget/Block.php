<?php
/**
 * This file is part of AntoineK_Cms for Magento.
 *
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author Antoine Kociuba <antoine.kociuba@gmail.com> <@antoinekociuba>
 * @category AntoineK
 * @package AntoineK_Cms
 * @copyright Copyright (c) 2015 Antoine Kociuba (http://www.antoinekociuba.com)
 */

/**
 * Rewrite_Widget_Block Block
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Block_Rewrite_Widget_Block extends Mage_Cms_Block_Widget_Block
{

// Antoine Kociuba Tag NEW_CONST

// Antoine Kociuba Tag NEW_VAR

    /**
     * OVERRIDE in order to set CMS block object to block, and not only content
     *
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return AntoineK_Cms_Block_Rewrite_Widget_Block
     */
    protected function _beforeToHtml()
    {
        /** OVERRIDE start */
        Mage_Core_Block_Template::_beforeToHtml();
        /** OVERRIDE end */

        $blockId = $this->getData('block_id');
        $blockHash = get_class($this) . $blockId;

        if (isset(self::$_widgetUsageMap[$blockHash])) {
            return $this;
        }
        self::$_widgetUsageMap[$blockHash] = true;

        if ($blockId) {
            $block = Mage::getModel('cms/block')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blockId);
            if ($block->getIsActive()) {

                /* @var $helper Mage_Cms_Helper_Data */
                $helper = Mage::helper('cms');
                $processor = $helper->getBlockTemplateProcessor();
                $this->setText($processor->filter($block->getContent()));

                /** OVERRIDE start */
                $this->setCmsBlock($block);
                /** OVERRIDE end */
            }
        }

        unset(self::$_widgetUsageMap[$blockHash]);
        return $this;
    }

// Antoine Kociuba Tag NEW_METHOD

}
