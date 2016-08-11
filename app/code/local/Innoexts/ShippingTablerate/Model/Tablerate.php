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
 * Table rate model
 *
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Model_Tablerate extends Innoexts_ShippingTablerate_Model_Abstract
{
    /**
     * Prefix of model events names
     * 
     * @var string
     */
    protected $_eventPrefix = 'shippingtablerate_tablerate';
    /**
     * Parameter name in event
     * 
     * In observe method you can use $observer->getEvent()->getItem() in this case
     * 
     * @var string
     */
    protected $_eventObject = 'tablerate';
    /**
     * Model cache tag for clear cache in after save and after delete
     * 
     * When you use true - all cache will be clean
     * 
     * @var string || true
     */
    protected $_cacheTag = 'shippingtablerate_tablerate';
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('shippingtablerate/tablerate');
    }
    /**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('shippingtablerate');
    }
    /**
     * Get shortened notes
     * 
     * @param int $maxLength
     * @return string
     */
    public function getShortNote($maxLength = 50)
    {
        $string = Mage::helper('core/string');
        $note = $this->getData('note');
        return ($string->strlen($note) > $maxLength) ? $string->substr($note, 0, $maxLength).'...' : $note;
    }
    /**
     * Filter destination country
     * 
     * @param mixed $value
     * @return string
     */
    public function filterDestCountry($value)
    {
        $helper = $this->getHelper();
        if ($value && ($value != '*')) {
            $countriesISO2Codes = $helper->getCountriesISO2Codes();
            $countriesISO3Codes = $helper->getCountriesISO3Codes();
            if (isset($countriesISO2Codes[$value])) {
                $value = $value;
            } else if (in_array($value, $countriesISO2Codes)) {
                $value = array_search($value, $countriesISO2Codes);
            } elseif (in_array($value, $countriesISO3Codes)) {
                $value = array_search($value, $countriesISO3Codes);
            } else $value = '0';
        } else $value = '0';
        return $value;
    }
    /**
     * Filter destination region
     * 
     * @param mixed $value
     * @return string
     */
    public function filterDestRegion($value)
    {
        $helper = $this->getHelper();
        $countryId = $this->filterDestCountry($this->getData('dest_country_id'));
        if ($countryId && $value && ($value != '*')) {
            $regionsCodes = $helper->getRegionsCodes($countryId);
            if (is_array($regionsCodes)) {
                if (isset($regionsCodes[$value])) {
                    $value = $value;
                } else if (in_array($value, $regionsCodes)) {
                    $value = array_search($value, $regionsCodes);
                } else $value = '0';
            } else $value = '0';
        } else $value = '0';
        return $value;
    }
    /**
     * Filter destination zip
     * 
     * @param mixed $value
     * @return string
     */
    public function filterDestZip($value)
    {
        return ($value == '' || $value == '*') ? '' : $value;
    }
    /**
     * Filter condition name
     * 
     * @param mixed $value
     * @return string
     */
    public function filterConditionName($value)
    {
        $values = Mage::getSingleton('shipping/carrier_tablerate')->getCode('condition_name');
        return (isset($values[$value])) ? $value : null;
    }
    /**
     * Get destination country filter
     * 
     * @return Zend_Filter
     */
    protected function getDestCountryFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterDestCountry'), 
        )));
    }
    /**
     * Get destination region filter
     * 
     * @return Zend_Filter
     */
    protected function getDestRegionFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterDestRegion'), 
        )));
    }
    /**
     * Get destination zip filter
     * 
     * @return Zend_Filter
     */
    protected function getDestZipFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterDestZip'), 
        )));
    }
    /**
     * Get condition name filter
     * 
     * @return Zend_Filter
     */
    protected function getConditionNameFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterConditionName'), 
        )));
    }
    /**
     * Get condition value filter
     * 
     * @return Zend_Filter
     */
    protected function getConditionValueFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterFloat'), 
        )));
    }
    /**
     * Get price filter
     * 
     * @return Zend_Filter
     */
    protected function getPriceFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterFloat'), 
        )));
    }
    /**
     * Get cost filter
     * 
     * @return Zend_Filter
     */
    protected function getCostFilter()
    {
        return $this->getTextFilter()->appendFilter(new Zend_Filter_Callback(array(
            'callback' => array($this, 'filterFloat'), 
        )));
    }
    /**
     * Get filters
     * 
     * @return array
     */
    protected function getFilters()
    {
        return array(
            'dest_country_id'     => $this->getDestCountryFilter(), 
            'dest_region_id'      => $this->getDestRegionFilter(), 
            'dest_zip'            => $this->getDestZipFilter(), 
            'condition_name'      => $this->getConditionNameFilter(), 
            'condition_value'     => $this->getConditionValueFilter(), 
            'price'               => $this->getPriceFilter(), 
            'cost'                => $this->getCostFilter(), 
            'note'                => $this->getTextFilter(), 
        );
    }
    /**
     * Get validators
     * 
     * @return array
     */
    protected function getValidators()
    {
        return array(
            'dest_country_id'     => $this->getTextValidator(false, 0, 4), 
            'dest_region_id'      => $this->getIntegerValidator(false, 0), 
            'dest_zip'            => $this->getTextValidator(false, 0, 10), 
            'condition_name'      => $this->getTextValidator(true, 0, 20), 
            'condition_value'     => $this->getFloatValidator(false, 0), 
            'price'               => $this->getFloatValidator(false, 0), 
            'cost'                => $this->getFloatValidator(false, 0), 
            'note'                => $this->getTextValidator(false, 0, 512), 
        );
    }
	/**
     * Validate catalog inventory stock
     * 
     * @throws Mage_Core_Exception
     * @return bool
     */
    public function validate()
    {
        $helper = $this->getHelper();
        parent::validate();
        $errorMessages = array();
        $tablerate = Mage::getModel('shippingtablerate/tablerate')->loadByRequest($this);
        if ($tablerate->getId()) {
            array_push($errorMessages, $helper->__('Duplicate rate.'));
        }
        if (count($errorMessages)) Mage::throwException(join("\n", $errorMessages));
        return true;
    }
    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        $helper = $this->getHelper();
        $title = null;
        $country = $region = null;
        if ($this->getDestRegionId()) $region = $helper->getRegion($this->getDestRegionId());
        if ($this->getDestCountryId()) $country = $helper->getCountry($this->getDestCountryId());
        $zip = $this->getDestZip();
        $conditionNames = Mage::getSingleton('shipping/carrier_tablerate')->getCode('condition_name');
        $conditionName = $this->getConditionName();
        $conditionName = (isset($conditionNames[$conditionName])) ? $conditionNames[$conditionName] : '';
        $conditionValue = $this->getConditionValue();
        $title = implode(', ', array(
            (($region) ? $region->getName() : '*'), 
            (($zip) ? $zip : '*'), 
            (($country) ? $country->getName() : '*'), 
            (($conditionName) ? $conditionName : ''), 
            (($conditionValue) ? floatval($conditionValue) : '0'), 
        ));
        return $title;
    }
    /**
     * Load table rate by request
     * 
     * @param Varien_Object $request
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
    public function loadByRequest(Varien_Object $request)
    {
        $this->_getResource()->loadByRequest($this, $request);
        $this->setOrigData();
        return $this;
    }
}
