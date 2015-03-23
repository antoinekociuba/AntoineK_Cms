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
 * Rewrite_Block Block
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Block_Rewrite_Block extends Mage_Core_Block_Template
{

// Antoine Kociuba Tag NEW_CONST

// Antoine Kociuba Tag NEW_VAR

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = '';

        if (($block = $this->getCmsBlock())
            && $block->getId()
            && $block->getIsActive()
        ) {

            $html = $this->getFilteredContent();
            $this->addModelTags($block);
        }

        /**
         * If a template is defined, use it for rendering
         */
        if ($this->getTemplate()) {
            $this->setContent($html);
            return parent::_toHtml();
        }

        /**
         * Otherwise, classic behavior with simple HTML output
         */
        return $html;
    }

    /**
     * Get CMS block
     *
     * @return AntoineK_Cms_Model_Rewrite_Block|mixed
     */
    public function getCmsBlock()
    {
        if (!$block = $this->getData('cms_block')) {
            if ($blockId = $this->getBlockId()) {

                /** @var AntoineK_Cms_Model_Rewrite_Block $block */
                $block = Mage::getModel('cms/block')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($blockId);

                $this->setData('cms_block', $block);
            }
        }

        return $block;
    }

    /**
     * Retrieve block content as HTML
     *
     * @return string
     */
    public function getFilteredContent()
    {
        if (!$html = $this->getData('filtered_content')) {

            /** @var AntoineK_Cms_Helper_Data $helper */
            $helper = Mage::helper('antoinek_cms');

            $html = $helper->filter($this->getCmsBlock()->getContent());

            $this->setData('filtered_content', $html);
        }

        return $html;
    }

// Antoine Kociuba Tag NEW_METHOD

}