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
 * Data Helper
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Helper_Data extends Mage_Core_Helper_Abstract
{

// Antoine Kociuba Tag NEW_CONST

// Antoine Kociuba Tag NEW_VAR

    /**
     * Get additional cms entity fieldsets config definitions
     *
     * @param $entity
     *
     * @return array|string
     */
    public function getAdditionalFieldsetDefinitions($entity)
    {
        if ($config = Mage::getConfig()->getNode('global/cms/' . $entity . '/additional_fields')) {
            return $config->asArray();
        }

        return [];
    }

    /**
     * Filter given string
     *
     * @param $data
     *
     * @return string
     *
     * @throws Exception
     */
    public function filter($data)
    {
        /** @var Mage_Cms_Helper_Data $helper */
        $helper = Mage::helper('cms');

        /** @var Varien_Filter_Template $processor */
        $processor = $helper->getBlockTemplateProcessor();

        return $processor->filter($data);
    }

// Antoine Kociuba Tag NEW_METHOD

}