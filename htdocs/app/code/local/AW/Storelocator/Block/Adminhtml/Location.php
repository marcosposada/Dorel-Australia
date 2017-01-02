<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Storelocator
 * @version    1.1.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Storelocator_Block_Adminhtml_Location extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_location';
        $this->_blockGroup = 'aw_storelocator';
        $this->_headerText = $this->__('Manage Locations');

        $this->_addButton('import_hours', array(
            'label'     => $this->__('Import'),
            'onclick'   => 'setLocation(\'' . $this->getImportUrl() .'\')',
            'class'     => 'go'
        ), 0, 100, 'header', 'header');

        parent::__construct();
    }

    public function getImportUrl()
    {
        return $this->getUrl('*/aw_storelocator_import/index');
    }
}