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
 * Form block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Block_Form extends Mage_Core_Block_Template
{
    /**
     * _prepareLayout for Collection
     *
     * @return Pixlogix_Flexibleforms_Block_Collection
     */
    protected function _prepareLayout()
    {
		parent::_prepareLayout();
    }
    /**
     * Get Form data by form Id
     *
     * @return Pixlogix_Flexibleforms_Block_Form
     */
    public function getFormData( $formId )
    {
        $collection = Mage::getModel('flexibleforms/flexibleforms')
                        ->getCollection()
                        ->addFieldToFilter('form_status', array('eq' => 1 ))
                        ->addFieldToFilter('form_id', array('eq' => $formId ))
                        ->getFirstItem();
        return $collection;
    }
    // Form Action to submit frontend data
    public function getFormAction()
    {
        $formAction = $this->getUrl( 'flexibleforms/index/submit', array('_secure'=>true) );
        return $formAction;
    }

    // "Text" field html
    public function getTextHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/text')->setData('field', $field)->setTemplate('flexibleforms/text.phtml')->toHtml();
    }

    // "Textarea" field html
    public function getTextareaHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/textarea')->setData('field', $field)->setTemplate('flexibleforms/textarea.phtml')->toHtml();
    }

    // "Email" field html
    public function getEmailHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/email')->setData('field', $field)->setTemplate('flexibleforms/email.phtml')->toHtml();
    }

    // "Number" field html
    public function getNumberHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/number')->setData('field', $field)->setTemplate('flexibleforms/number.phtml')->toHtml();
    }

    // "Url" field html
    public function getUrlHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/url')->setData('field', $field)->setTemplate('flexibleforms/url.phtml')->toHtml();
    }

    // "Password" field html
    public function getPasswordHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/password')->setData('field', $field)->setTemplate('flexibleforms/password.phtml')->toHtml();
    }

    // "Select" field html
    public function getSelectHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/select')->setData('field', $field)->setTemplate('flexibleforms/select.phtml')->toHtml();
    }

    // "Multiselect" field html
    public function getMultiselectHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/multiselect')->setData('field', $field)->setTemplate('flexibleforms/multiselect.phtml')->toHtml();
    }

    // "Checkbox" field html
    public function getCheckboxHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/checkbox')->setData('field', $field)->setTemplate('flexibleforms/checkbox.phtml')->toHtml();
    }

    // "Radio" field html
    public function getRadioHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/radio')->setData('field', $field)->setTemplate('flexibleforms/radio.phtml')->toHtml();
    }

    // "Date" field html
    public function getDateHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/date')->setData('field', $field)->setTemplate('flexibleforms/date.phtml')->toHtml();
    }

    // "Time" field html
    public function getTimeHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/time')->setData('field', $field)->setTemplate('flexibleforms/time.phtml')->toHtml();
    }

    // "Datetime" field html
    public function getDatetimeHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/datetime')->setData('field', $field)->setTemplate('flexibleforms/datetime.phtml')->toHtml();
    }

    // "Image" field html
    public function getImageHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/image')->setData('field', $field)->setTemplate('flexibleforms/image.phtml')->toHtml();
    }

    // "File" field html
    public function getFileHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/file')->setData('field', $field)->setTemplate('flexibleforms/file.phtml')->toHtml();
    }

    // "Send Copy to Me" field html
    public function getSendcopytomeHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/sendcopytome')->setData('field', $field)->setTemplate('flexibleforms/sendcopytome.phtml')->toHtml();
    }

    // "Country" field html
    public function getCountryHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/country')->setData('field', $field)->setTemplate('flexibleforms/country.phtml')->toHtml();
    }

    // "State" field html
    public function getStateHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/state')->setData('field', $field)->setTemplate('flexibleforms/state.phtml')->toHtml();
    }

    // "Terms" field html
    public function getTermsHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/terms')->setData('field', $field)->setTemplate('flexibleforms/terms.phtml')->toHtml();
    }

    // "Star" field html
    public function getStarHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/star')->setData('field', $field)->setTemplate('flexibleforms/star.phtml')->toHtml();
    }

	// "Hidden" field html
    public function getHiddenHtml($field)
    {
        echo $this->getLayout()->createBlock('flexibleforms/hidden')->setData('field', $field)->setTemplate('flexibleforms/hidden.phtml')->toHtml();
    }
}