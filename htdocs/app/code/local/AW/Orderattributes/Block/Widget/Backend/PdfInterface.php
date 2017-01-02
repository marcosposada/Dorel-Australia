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
 * @package    AW_Orderattributes
 * @version    1.0.4
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


interface AW_Orderattributes_Block_Widget_Backend_PdfInterface
{
    /**
     * @return mixed
     */
    public function getLabel();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param AW_Orderattributes_Model_Attribute_TypeInterface $type
     *
     * @return AW_Orderattributes_Block_Widget_Backend_PdfAbstract
     */
    public function setTypeModel(AW_Orderattributes_Model_Attribute_TypeInterface $type);

    /**
     * @return AW_Orderattributes_Model_Attribute_TypeInterface
     */
    public function getTypeModel();

    /**
     * @param mixed $value
     *
     * @return AW_Orderattributes_Block_Widget_Backend_PdfInterface
     */
    public function setValue($value);
}