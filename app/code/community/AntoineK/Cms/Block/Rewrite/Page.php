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
class AntoineK_Cms_Block_Rewrite_Page extends Mage_Cms_Block_Page
{
    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml()
    {
        $html = parent::_toHtml();

        /**
         * If a template is defined, let's use it
         */
        if ($template = $this->getTemplate()) {
            return $this->getLayout()
                ->createBlock('core/template')
                ->setTemplate($template)
                ->setCmsPage($this->getPage())
                ->setCmsPageContent($html)
                ->toHtml();
        }

        return $html;
    }
}