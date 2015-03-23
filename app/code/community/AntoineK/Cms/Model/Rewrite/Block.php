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
 * Rewrite_Block Model
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Model_Rewrite_Block extends Mage_Cms_Model_Block
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'cms_block';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'cms_block';

    /**
     * Get filtered data
     *
     * @param string $key
     * @param null $index
     *
     * @return mixed|string
     */
    public function getFilteredData($key = '', $index = null)
    {
        if (!$filteredData = $this->getData('filtered_' . $key . '_' . $index)) {
            $filteredData = $this->_getHelper()->filter(
                $this->getData($key, $index)
            );

            $this->setData('filtered_' . $key . '_' . $index, $filteredData);
        }

        return $filteredData;
    }

    /**
     * Get helper
     *
     * @return AntoineK_Cms_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('antoinek_cms');
    }
}