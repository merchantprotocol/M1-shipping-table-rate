<?php
/**
 * Innoexts
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_ShippingTablerate
 * @copyright   Copyright (c) 2011 Innoexts (http://www.innoexts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Website switcher block
 * 
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Block_Adminhtml_Website_Switcher extends Mage_Adminhtml_Block_Template
{
    /**
     * Website
     * 
     * @var Mage_Core_Model_Website
     */
    protected $_website;
    /**
     * Website variable name
     * 
     * @var string
     */
    protected $_websiteVarName = 'website';
    /**
     * Whether has default option or not
     * 
     * @var boolean
     */
    protected $_hasDefaultOption = false;
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
        $this->setTemplate('shippingtablerate/website/switcher.phtml');
        $this->setUseConfirm(true);
        $this->setUseAjax(true);
        $this->setDefaultWebsiteName($helper->__('All Websites'));
    }
    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites()
    {
        return $this->_getHelper()->getWebsites();
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
     * Set/Get whether the switcher should show default option
     * 
     * @param bool $hasDefaultOption
     * @return bool
     */
    public function hasDefaultOption($hasDefaultOption = null)
    {
        if (null !== $hasDefaultOption) $this->_hasDefaultOption = $hasDefaultOption;
        return $this->_hasDefaultOption;
    }
    /**
     * Get switch URL
     * 
     * @return string
     */
    public function getSwitchUrl()
    {
        if ($url = $this->getData('switch_url')) return $url;
        return $this->getUrl('*/*/*', array('_current' => true, $this->_websiteVarName => null));
    }
}