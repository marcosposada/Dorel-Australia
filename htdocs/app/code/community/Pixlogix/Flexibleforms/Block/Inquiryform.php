<?php 
class Pixlogix_Flexibleforms_Block_Inquiryform extends Mage_Core_Block_Template
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
	public function getFormData( $formId )
    {
		$formId = ($this->getData('id')) ? $this->getData('id') : 0;
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
}
?>