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

try {

    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    /**
     * Create table 'cms_block_additional'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('cms/block') . '_additional')
        ->addColumn('block_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Block ID')
        ->addColumn('additional', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => true,
        ), 'Block JSON data')
        ->addForeignKey($installer->getFkName('cms_block_additional', 'block_id', 'cms/block', 'block_id'),
            'block_id', $installer->getTable('cms/block'), 'block_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

    $installer->getConnection()->createTable($table);

    $installer->endSetup();

} catch (Exception $e) {
    // Silence is golden
    Mage::logException($e);
}
