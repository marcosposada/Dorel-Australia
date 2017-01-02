<?php
/**
 * Pixlogix Flexibleforms
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @copyright  Copyright (c) 2015 Pixlogix Flexibleforms (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Collection block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Collection extends Mage_Core_Block_Template
{
    /**
     * Set Collection of forms data
     *
     * @return Pixlogix_Flexibleforms_Block_Collection
     */
    public function __construct()
    {
        parent::__construct();
		$productInquiryFormId= Mage::getModel('flexibleforms/flexibleforms')->getProductInquiryFormId();
        
		$collection = Mage::getModel('flexibleforms/flexibleforms')->getCollection();
        $collection->addFieldToFilter( 'form_status', array('eq' => 1 ) );
		$enabledProductInquiry =Mage::getModel('flexibleforms/flexibleforms')->enabledProductInquiry();
		if($productInquiryFormId && $enabledProductInquiry):
			$collection->addFieldToFilter( 'form_id', array('neq' => $productInquiryFormId ) );
		endif;
		
		
        $storeId = Mage::app()->getStore()->getStoreId();
        if($storeId!=0)
        {
            $arrStoreId = array($storeId);
            $collection->addFieldToFilter( 'store_id', array('finset' => $arrStoreId ) );
        }
		
        $this->setCollection($collection);
    }

    /**
     * _prepareLayout for Collection
     *
     * @return Pixlogix_Flexibleforms_Block_Collection
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * set pager html
     *
     * @return Pixlogix_Flexibleforms_Block_Collection
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}