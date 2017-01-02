<?php
class Pixlogix_Flexibleforms_Block_Flexibleforms extends Mage_Core_Block_Template {

    //Add Flexibleforms link to Top Link block
    //@return Pixlogix_Flexibleforms_Block_Flexibleforms
    public function addFlexibleformsTopLink() {
        $parentBlock = $this->getParentBlock(); //Create an object for getParentBlock() method
        $enabledFlexibleforms = $this->helper('flexibleforms')->enabledFlexibleforms(); //Check if flexibleforms module is enabled
        $enabledTopLink = $this->helper('flexibleforms')->enabledTopLink(); //Check if toplink is enabled
        if ($parentBlock && $enabledFlexibleforms && $enabledTopLink) {
            $text = $this->__('Flexibleforms');
            //Top link Display Text
            $url = 'flexibleforms';
            $position = 5;
            // @param string $text
            // @param string $url
            // @param string $text
            // @param boolean $prepare
            // @param array $urlParams
            // @param int $position
            // @return Mage_Page_Block_Template_Links
            $parentBlock->addLink($text, $url , $text, $prepare=true, $urlParams=array(), $position , null, 'class="top-link-flexibleforms"');
        }
        return $this;
    }

    //Add Flexibleforms link to Fooler Link block
    //@return Pixlogix_Flexibleforms_Block_Flexibleforms
    public function addFlexibleformsFooterLink() {
        $parentBlock = $this->getParentBlock(); //Create an object for getParentBlock() method
        $enabledFlexibleforms = $this->helper('flexibleforms')->enabledFlexibleforms(); //Check if flexibleforms module is enabled
        $enabledFooterLink = $this->helper('flexibleforms')->enabledFooterLink(); //Check if footerlink is enabled
        if ($parentBlock && $enabledFlexibleforms && $enabledFooterLink) {
            $text = $this->__('Flexibleforms');
            //Top link Display Text
            $url = 'flexibleforms';
            $position = 5;
            // @param string $text
            // @param string $url
            // @param string $text
            // @param boolean $prepare
            // @param array $urlParams
            // @param int $position
            // @return Mage_Page_Block_Template_Links
            $parentBlock->addLink($text, $url , $text, $prepare=true, $urlParams=array(), $position , null, 'class="footer-link-flexibleforms"');
        }
        return $this;
    }

    public function addJsCss(){
        $parentBlock = $this->getParentBlock();
        $enabledFlexibleforms = $this->helper('flexibleforms')->enabledFlexibleforms(); //Check if flexibleforms module is enabled
        $enabledjQuery = $this->helper('flexibleforms')->enabledjQuery(); //Check if jQuery library file is enabled
        if($enabledFlexibleforms){
            $parentBlock->addItem('skin_css','css/flexibleforms/flexibleforms.css');
            if($enabledjQuery){
                $parentBlock->addItem('skin_js','js/flexibleforms/jquery-2.1.1.min.js');
            }
            $parentBlock->addItem('skin_js','js/flexibleforms/jquery-noconflict.js');
            $parentBlock->addItem('skin_js','js/flexibleforms/stars.js');
        }
    }
}?>