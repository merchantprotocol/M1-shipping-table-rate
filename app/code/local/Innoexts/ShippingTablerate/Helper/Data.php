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
 * Shipping tablerate data helper
 * 
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Table rates
     * 
     * @var array
     */
    protected $_tablerates;
    /**
     * Countries
     * 
     * @var array
     */
    protected $_countries;
    /**
     * Regions
     * 
     * @var array
     */
    protected $_regions;
    /**
     * ISO2 countries
     * 
     * @var array
     */
    protected $_countriesISO2Codes;
    /**
     * ISO3 countries
     * 
     * @var array
     */
    protected $_countriesISO3Codes;
    /**
     * Regions
     * 
     * @var array
     */
    protected $_regionsCodes;
    
    /**
     * Get table rates
     * 
     * @return array
     */
    public function getTablerates()
    {
        if (is_null($this->_tablerates)) {
            $this->_tablerates = array();
            $tablerateCollection = Mage::getResourceModel('shippingtablerate/tablerate_collection');
            foreach ($tablerateCollection as $tablerate) {
                $this->_tablerates[$tablerate->getId()] = $tablerate;
            }
        }
        return $this->_tablerates;
    }
    /**
     * Retrieve table rate by id
     * 
     * @param int $tablerateId
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
    public function getTablerateById($tablerateId)
    {
        $tablerates = $this->getTablerates();
        if (isset($tablerates[$tablerateId])) return $tablerates[$tablerateId];
        else return null;
    }
    
    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites()
    {
        return Mage::app()->getWebsites();
    }
    /**
     * Get default website
     * 
     * @return Mage_Core_Model_Website
     */
    public function getDefaultWebsite()
    {
        $website = null;
        $websites = $this->getWebsites();
        if (count($websites)) $website = array_shift($websites);
        return $website;
    }
    /**
     * Get website
     * 
     * @return Mage_Core_Model_Website
     */
    public function getWebsite()
    {
        $website = null;
        $websiteId = (int) Mage::app()->getFrontController()->getRequest()->getParam('website', 0);
        if ($websiteId) $website = Mage::app()->getWebsite($websiteId);
        if (!$website) $website = $this->getDefaultWebsite();
        return $website;
    }
    /**
     * Get website identifier
     * 
     * @param $website|null Mage_Core_Model_Website
     * @return mixed
     */
    public function getWebsiteId($website = null)
    {
        if (is_null($website)) $website = $this->getWebsite();
        return ($website) ? $website->getId() : null;
    }
    /**
     * Get countries
     * 
     * @return array
     */
    public function getCountries()
    {
        if (is_null($this->_countries)) {
            $countries = array();
            $countryCollection = Mage::getResourceModel('directory/country_collection');
            foreach ($countryCollection as $country) {
                $countries[$country->getId()] = $country;
            }
            $this->_countries = $countries;
        }
        return $this->_countries;
    }
    /**
     * Get country
     * 
     * @param string $countryId
     * @return Mage_Directory_Model_Country
     */
    public function getCountry($countryId)
    {
        $countries = $this->getCountries();
        if (isset($countries[$countryId])) return $countries[$countryId];
        else return null;
    }
    /**
     * Get regions
     * 
     * @return array
     */
    public function getRegions()
    {
        if (is_null($this->_regions)) {
            $regions = array();
            $regionCollection = Mage::getResourceModel('directory/region_collection');
            foreach ($regionCollection as $region) {
                $regions[$region->getId()] = $region;
            }
            $this->_regions = $regions;
        }
        return $this->_regions;
    }
    /**
     * Get region
     * 
     * @param string $regionId
     * @return Mage_Directory_Model_Region
     */
    public function getRegion($regionId)
    {
        $regions = $this->getRegions();
        if (isset($regions[$regionId])) return $regions[$regionId];
        else return null;
    }
    /**
     * Load countries codes
     * 
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
    protected function _loadCountriesCodes()
    {
        if (is_null($this->_countriesISO2Codes) || is_null($this->_countriesISO3Codes)) {
            $this->_countriesISO2Codes = $this->_countriesISO3Codes = array();
            foreach ($this->getCountries() as $country) {
                $this->_countriesISO2Codes[$country->getId()] = $country->getIso2Code();
                $this->_countriesISO3Codes[$country->getId()] = $country->getIso3Code();
            }
        }
        return $this;
    }
    /**
     * Load regions codes
     * 
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
    protected function _loadRegionsCodes()
    {
        if (is_null($this->_regionsCodes)) {
            $this->_regionsCodes = array();
            foreach ($this->getRegions() as $region) {
                $this->_regionsCodes[$region->getCountryId()][$region->getRegionId()] = $region->getCode();
            }
        }
        return $this;
    }
    /**
     * Get countries ISO2 codes
     * 
     * @return array
     */
    public function getCountriesISO2Codes()
    {
        if (is_null($this->_countriesISO2Codes)) $this->_loadCountriesCodes();
        return $this->_countriesISO2Codes;
    }
    /**
     * Get countries ISO2 codes
     * 
     * @return array
     */
    public function getCountriesISO3Codes()
    {
        if (is_null($this->_countriesISO3Codes)) $this->_loadCountriesCodes();
        return $this->_countriesISO3Codes;
    }
    /**
     * Get regions codes
     * 
     * @param string $countryId
     * @return array
     */
    public function getRegionsCodes($countryId = null)
    {
        if (is_null($this->_regionsCodes)) $this->_loadRegionsCodes();
        return ($countryId && isset($this->_regionsCodes[$countryId])) ? $this->_regionsCodes[$countryId] : array();
    }
}
