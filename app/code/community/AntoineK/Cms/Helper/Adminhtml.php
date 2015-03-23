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
 * Adminhtml Helper
 * @package AntoineK_Cms
 */
class AntoineK_Cms_Helper_Adminhtml extends Mage_Core_Helper_Abstract
{

// Antoine Kociuba Tag NEW_CONST

// Antoine Kociuba Tag NEW_VAR

    /**
     * Prepare CMS entity form with additional fieldsets/fields
     *
     * @param $block
     * @param string $entity
     */
    public function prepareForm(Mage_Adminhtml_Block_Widget_Form $block, $entity = 'block')
    {
        if ($definitions = Mage::helper('antoinek_cms')->getAdditionalFieldsetDefinitions($entity)) {

            $object = Mage::registry('cms_' . $entity);

            /**
             * Make 'content' form element not mandatory
             */
            $block->getForm()->getElement('content')->setRequired(false);

            /**
             * Loop through defined fieldsets
             */
            foreach ($definitions as $fieldsetId => $data) {

                if (!isset($data['label'])) {
                    Mage::logException(new Exception('Missing label declaration for CMS ' . $entity . ' fieldset "' . $fieldsetId . '"'));
                    continue;
                }

                /** @var Varien_Data_Form_Element_Fieldset $fieldset */
                $fieldset = $block->getForm()->addFieldset($fieldsetId, [
                        'legend' => $this->_prepareFieldsetLabel($data),
                        'class' => 'fieldset-wide'
                    ]
                );

                /**
                 * Loop through defined fields
                 */
                foreach ($data['fields'] as $id => $definition) {
                    if (!isset($definition['type'])) {
                        Mage::logException(new Exception('Missing type declaration for CMS field "' . $id . '"'));
                        continue;
                    }

                    /**
                     * Add field
                     */
                    $config = array_merge([
                        'name' => $fieldsetId . '[' . $id . ']',
                        'value' => $object->getData($fieldsetId, $id)
                    ], $this->_prepareConfig($definition, $fieldset));

                    $fieldset->addField($fieldsetId . '_' . $id, $definition['type'], $config);
                }
            }
        }
    }

    /**
     * Prepare field/fieldset configuration
     *
     * @param $definition
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     *
     * @return mixed
     */
    protected function _prepareConfig($definition, Varien_Data_Form_Element_Fieldset $fieldset)
    {
        /**
         * Translator base definition
         */
        $translatorHelper = Mage::helper('core');
        $attributesToTranslate = [];

        foreach ($definition as $key => $data) {

            /**
             * Translator definition
             */
            if ($key == '@' && isset($data['translate'])) {
                $attributesToTranslate = explode(' ', $data['translate']);

                if (isset($data['module'])) {
                    $translatorHelper = Mage::helper($data['module']);
                }

                continue;
            }

            /**
             * Grab special values
             */
            if (is_array($data) || $data instanceof ArrayAccess) {
                if (isset($data['@'])) {
                    if (isset($data['@']['config'])) {
                        /**
                         * Config call
                         */
                        $definition[$key] = Mage::app()->getStore()->getConfig($data['@']['config']);
                    } elseif (isset($data['@']['helper'])) {
                        /**
                         * Helper method call
                         */
                        $helper = explode('::', $data['@']['helper']);
                        $definition[$key] = Mage::helper($helper[0])->{$helper[1]}();
                        unset($helper);
                    } elseif (isset($data['@']['model'])) {
                        /**
                         * Model method call
                         */
                        $model = explode('::', $data['@']['model']);
                        $definition[$key] = Mage::getSingleton($model[0])->{$model[1]}();
                        unset($model);
                    }
                }
            }

            /**
             * Translate
             */
            if (in_array($key, $attributesToTranslate)) {
                $definition[$key] = $translatorHelper->__($definition[$key]);
            }
        }

        /**
         * Special types handling
         */
        switch ($definition['type']) {

            /**
             * Wysiwyg editor field
             */
            case 'editor':
                $definition['config'] = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
                break;

            /**
             * Media chooser field
             * @see https://github.com/antoinekociuba/AntoineK_MediaChooserField
             */
            case 'mediachooser':
                if (Mage::helper('core')->isModuleEnabled('AntoineK_MediaChooserField')) {
                    $fieldset->addType('mediachooser', 'AntoineK_MediaChooserField_Data_Form_Element_Mediachooser');
                } else {
                    $definition['type'] = 'text';
                }
                break;
        }

        return $definition;
    }

    /**
     * Translate fieldset label, if necessary
     *
     * @param $data
     *
     * @return string
     */
    protected function _prepareFieldsetLabel($data)
    {
        $label = $data['label'];

        if (isset($data['@']) && isset($data['@']['translate'])) {
            $label = isset($data['@']['module'])
                ? Mage::helper($data['@']['module'])->__($label)
                : Mage::helper('core')->__($label);
        }

        return $label;
    }

// Antoine Kociuba Tag NEW_METHOD

}