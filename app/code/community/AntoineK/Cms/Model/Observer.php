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
 * Observer Model
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Model_Observer extends Mage_Core_Model_Abstract
{

// Antoine Kociuba Tag NEW_CONST

// Antoine Kociuba Tag NEW_VAR

    /**
     * Add additional field to CMS entity form
     *
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function adminhtmlBlockHtmlBefore(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Cms_Block_Edit_Form) {
            Mage::helper('antoinek_cms/adminhtml')->prepareForm($block);
        }

        return $this;
    }

    /**
     * Bind additional CMS block data
     *
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function cmsBlockLoadAfter(Varien_Event_Observer $observer)
    {
        $this->_bindAdditional($observer->getEvent()->getCmsBlock());

        return $this;
    }

    /**
     * Save additional CMS block data
     *
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function cmsBlockSaveAfter(Varien_Event_Observer $observer)
    {
        $this->_saveAdditional($observer->getEvent()->getCmsBlock());

        return $this;
    }

    /**
     * Save additional data
     *
     * @param Mage_Core_Model_Abstract $object
     * @param string $entity
     */
    protected function _saveAdditional(Mage_Core_Model_Abstract $object, $entity = 'block')
    {
        if ($definitions = Mage::helper('antoinek_cms')->getAdditionalFieldsetDefinitions($entity)) {

            $additional = [
                $entity . '_id' => $object->getId()
            ];

            /**
             * Loop through defined fieldsets and build data array
             */
            foreach ($definitions as $fieldsetId => $data) {
                $additional['additional'][$fieldsetId] = $object->getData($fieldsetId);
            }

            /**
             * JSON encode
             */
            $additional['additional'] = Mage::helper('core')->jsonEncode($additional['additional']);

            /**
             * Save additional data
             */
            $this->_getConnection('write')
                ->insertOnDuplicate(
                    $this->_getResource()->getTableName('cms_' . $entity . '_additional'),
                    $additional
                );

        }
    }

    /**
     * Bind additional data to object
     *
     * @param Mage_Core_Model_Abstract $object
     * @param string $entity
     */
    protected function _bindAdditional(Mage_Core_Model_Abstract $object, $entity = 'block')
    {
        $select = $this->_getConnection()
            ->select()
            ->from(
                $this->_getResource()->getTableName('cms_' . $entity . '_additional'),
                'additional'
            )
            ->where($entity . '_id = :' . $entity . '_id');

        $binds = array(
            ':' . $entity . '_id' => (int)$object->getId()
        );

        /**
         * Bind additional data
         */
        if ($additionalData = $this->_getConnection()->fetchOne($select, $binds)) {
            $object->addData(
                Mage::helper('core')->jsonDecode($additionalData)
            );
        }
    }

    /**
     * Get connection
     *
     * @param string $type
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getConnection($type = 'read')
    {
        return $this->_getResource()
            ->getConnection('core_' . $type);
    }

    /**
     * Get resource
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _getResource()
    {
        return Mage::getSingleton('core/resource');
    }

// Antoine Kociuba Tag NEW_METHOD

}