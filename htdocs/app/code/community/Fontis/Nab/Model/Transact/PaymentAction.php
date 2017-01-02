<?php
/**
 * Fontis NAB Transact Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so they can send you a copy immediately.
 *
 * @category   Fontis
 * @package    Fontis_Nab
 * @author     Chris Norton
 * @copyright  Copyright (c) 2008 Fontis Pty. Ltd. (http://www.fontis.com.au)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Fontis_Nab_Model_Transact_PaymentAction
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Fontis_Nab_Model_Transact::PAYMENT_ACTION_AUTH_CAPTURE,
                'label' => Mage::helper('nab')->__('Authorise and Capture')
            ),
        );
    }
}
