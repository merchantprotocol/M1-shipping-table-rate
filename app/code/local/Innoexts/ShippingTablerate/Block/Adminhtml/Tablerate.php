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
 * Table rates block
 * 
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate extends Mage_Adminhtml_Block_Widget_Grid_Container
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
     * Check is allowed action
     * 
     * @param   string $action
     * @return  bool
     */
    protected function isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/shipping/tablerates/'.$action);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $helper = $this->_getHelper();
        $this->_controller = 'adminhtml_tablerate';
        $this->_blockGroup = 'shippingtablerate';
        $this->_headerText = $helper->__('Shipping Table Rates');
        parent::__construct();
        $this->setTemplate('shippingtablerate/tablerate.phtml');
        if ($this->isAllowedAction('save')) {
            $this->_updateButton('add', 'label', $helper->__('Add New Rate'));
        } else {
            $this->_removeButton('add');
        }
    }
    /**
     * Get create URL
     * 
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new', array('website' => $this->getWebsiteId()));
    }
}
