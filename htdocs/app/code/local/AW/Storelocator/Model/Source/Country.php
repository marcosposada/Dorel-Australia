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


class AW_Storelocator_Model_Source_Country
{

    public function toOptionArray()
    {
        return Mage::getSingleton('directory/country')->getResourceCollection()->toOptionArray();

    }

    public function toFlatArray()
    {
        $options = $this->toOptionArray();
        $flat = array();
        foreach ($options as $value) {
            if (empty($value['value'])) {
                continue;
            }
            $flat[$value['value']] = $value['label'];
        }
        return $flat;
    }


}
