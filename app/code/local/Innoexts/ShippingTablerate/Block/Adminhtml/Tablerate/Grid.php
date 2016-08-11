<?php
/**
 * Merchant Protocol
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Merchant Protocol Commercial License (MPCL 1.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://merchantprotocol.com/commercial-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@merchantprotocol.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.merchantprotocol.com for more information.
 *
 * @category    MP
 * @package     MP_ShippingTablerate
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * Table rates grid block
 *
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Website
     * 
     * @var Mage_Core_Model_Website
     */
    protected $_website;
    /**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('shippingtablerate');
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $helper = $this->_getHelper();
        parent::__construct();
        $this->setId('tablerateGrid');
        $this->setDefaultSort('pk');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->_exportPageSize = 10000;
        $this->setEmptyText($helper->__('No table rates found.'));
    }
    /**
     * Get website
     * 
     * @return Mage_Core_Model_Website
     */
    protected function getWebsite()
    {
        if (is_null($this->_website)) $this->_website = $this->_getHelper()->getWebsite();
        return $this->_website;
    }
    /**
     * Get website identifier
     * 
     * @return mixed
     */
    public function getWebsiteId()
    {
        return $this->_getHelper()->getWebsiteId($this->getWebsite());
    }
    /**
     * Prepare grid collection object
     *
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid
     */
    protected function _prepareCollection()
    {
        $websiteId = $this->getWebsiteId();
        $collection = Mage::getModel('shippingtablerate/tablerate')->getCollection();
        $select = $collection->getSelect();
        if ($websiteId) $select->where('website_id = ?', $websiteId);
        else $select->where('website_id = -1');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * Get destination country options
     * 
     * @return array
     */
    protected function getDestCountryOptions()
    {
        $helper = $this->_getHelper();
        $options = array();
        $countries = Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(false);
        if (isset($countries[0])) $countries[0] = array('value' => '0', 'label' => '*', );
        foreach ($countries as $country) {
            $options[$country['value']] = $country['label'];
        }
        return $options;
    }
    /**
     * Get condition name options
     * 
     * @return array
     */
    protected function getConditionNameOptions()
    {
        $options = array();
        $names = Mage::getModel('adminhtml/system_config_source_shipping_tablerate')->toOptionArray();
        foreach ($names as $name) {
            $options[$name['value']] = $name['label'];
        }
        return $options;
    }
    /**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
	/**
     * Prepare columns
     *
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $helper = $this->_getHelper();
        $this->addColumn('dest_country_id', array(
            'header'    => $helper->__('Dest Country'), 
            'align'     => 'left', 
            'index'     => 'dest_country_id', 
            'type'      => 'options', 
            'options'   => $this->getDestCountryOptions(), 
        ));
        $this->addColumn('dest_region', array(
            'header'        => $helper->__('Dest Region/State'), 
            'align'         =>'left',
            'index'         => 'dest_region', 
            'filter_index'  => 'region_table.code', 
            'filter'	    => 'shippingtablerate/adminhtml_tablerate_grid_column_filter_region', 
            'default'       => '*', 
        ));
        $this->addColumn('dest_zip', array(
            'header'        => $helper->__('Dest Zip/Postal Code'), 
            'align'         =>'left', 
            'index'         => 'dest_zip', 
            'filter'	    => 'shippingtablerate/adminhtml_tablerate_grid_column_filter_zip', 
            'renderer'	    => 'shippingtablerate/adminhtml_tablerate_grid_column_renderer_zip', 
            'default'       => '*', 
        ));
        $this->addColumn('condition_name', array(
            'header'        => $helper->__('Condition Name'), 
            'align'         => 'left', 
            'index'         => 'condition_name', 
            'type'          => 'options', 
            'options'       => $this->getConditionNameOptions(), 
        ));
        $this->addColumn('condition_value', array(
            'header'        => $helper->__('Condition Value'), 
            'align'         => 'left', 
            'index'         => 'condition_value', 
            'type'          => 'number', 
            'default'       => '0', 
        ));
        $store = $this->_getStore();
        $this->addColumn('price', array(
            'header'        => $helper->__('Price'), 
            'align'         => 'left', 
            'index'         => 'price', 
            'type'          => 'price', 
            'currency_code' => $store->getBaseCurrency()->getCode(), 
            'default'       => '0.00', 
        ));
        $this->addColumn('cost', array(
            'header'        => $helper->__('Cost'), 
            'align'         => 'left', 
            'index'         => 'cost', 
            'type'          => 'price', 
            'currency_code' => $store->getBaseCurrency()->getCode(), 
            'default'       => '0.00', 
        ));
        $this->addColumn('note', array(
            'header'        => $helper->__('Notes'), 
            'index'         => 'note', 
            'getter'        => 'getShortNote', 
        ));
        $this->addExportType('*/*/exportCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportXml', $helper->__('Excel XML'));
        return $this;
    }
    /**
     * Get row URL
     * 
     * @param   Varien_Object $row
     * @return  string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('website' => $this->getWebsiteId(), 'tablerate_id' => $row->getId()));
    }
    /**
     * Get grid URL
     * 
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
    /**
     * Prepare mass action
     * 
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid
     */
    protected function _prepareMassaction()
    {
        $helper = $this->_getHelper();
        $this->setMassactionIdField('pk');
        $this->getMassactionBlock()->setFormFieldName('tablerate_id');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'       => $helper->__('Delete'), 
            'url'         => $this->getUrl('*/*/massDelete', array('website' => $this->getWebsiteId())), 
            'confirm'     => $helper->__('Are you sure?')
        ));
        return $this;
    }
}
