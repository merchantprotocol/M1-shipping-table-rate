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
 * Table rate collection
 * 
 * @category   Innoexts
 * @package    Innoexts_ShippingTablerate
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_ShippingTablerate_Model_Mysql4_Tablerate_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * directory/country table name
     *
     * @var string
     */
    protected $_countryTable;

    /**
     * directory/country_region table name
     *
     * @var string
     */
    protected $_regionTable;
    /**
     * Constructor
     */
    protected function _construct() {
        $this->_init('shippingtablerate/tablerate');
        $this->_countryTable    = $this->getTable('directory/country');
        $this->_regionTable     = $this->getTable('directory/country_region');
    }
    /**
     * Initialize select, add country iso3 code and region name
     *
     * @return void
     */
    public function _initSelect()
    {
        parent::_initSelect();
        $this->_select->joinLeft(
                array('country_table' => $this->_countryTable), 
                'country_table.country_id = main_table.dest_country_id', 
                array('dest_country' => 'iso2_code'))
            ->joinLeft(
                array('region_table' => $this->_regionTable),
                'region_table.region_id = main_table.dest_region_id',
                array('dest_region' => 'code'));
    }
    /**
     * Add website filter to collection
     *
     * @param int $websiteId
     * @return Innoexts_ShippingTablerate_Model_Mysql4_Tablerate_Collection
     */
    public function setWebsiteFilter($websiteId)
    {
        return $this->addFieldToFilter('website_id', $websiteId);
    }
}
