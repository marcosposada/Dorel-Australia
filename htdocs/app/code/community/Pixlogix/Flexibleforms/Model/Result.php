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
 * Flexibleforms Result model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleforms
 * @author     Pixlogix Team <support@pixlogix.com>
 */
class Pixlogix_Flexibleforms_Model_Result extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleforms/result');
    }

    public function getNewFileName($destFile)
    {
        $fileInfo = pathinfo($destFile);
        if (file_exists($destFile))
        {
                $index = 1;
                $baseName = $fileInfo['filename'] . '.' . $fileInfo['extension'];
                while( file_exists($fileInfo['dirname'] . DIRECTORY_SEPARATOR . $baseName) )
                {
                        $baseName = $fileInfo['filename']. '_' . $index . '.' . $fileInfo['extension'];
                        $index ++;
                }
                $destFileName = $baseName;
        } else {
                return $fileInfo['basename'];
        }
        return $destFileName;
    }

    public function getFieldByFormId($formId)
    {
            $collection =  Mage::getModel('flexibleforms/fields')->addFieldToFilter('form_id', $formId);
            return $collection;
    }

    public function getValueByFieldId($fieldId)
    {
            $collection =  Mage::getModel('flexibleforms/fields')->getCollection()->addFieldToFilter('form_id', $formId);
            return $collection->getFirstItem();
    }

    public function getOptionArray($_fieldId)
    {
            $fieldOptions = Mage::getModel('flexibleforms/fields')->getCollection();
            $fieldOptions->addFieldToFilter('field_id', array('eq'=>$_fieldId));
            $fieldOptions->setOrder('position','asc');
            //To retrive field value on unsuccess submission
            $field_value = Mage::helper('flexibleforms');

            $fieldArray = array();
            foreach($fieldOptions as $fieldOption)
            {
                    $fieldArray['serializeOptions'] = $fieldOption->getOptionsValue();
                    $fieldArray['required'] 	    = $fieldOption->getRequired();
                    $fieldArray['field_id'] 	    = $fieldOption->getFieldId();
                    $fieldArray['type']             =$fieldOption->getType();
            }
            if($fieldArray['type']=='sendcopytome')
            {
                $arrControlOption=array('1'=>'Yes','2'=>'No');
                return $arrControlOption;
                exit;
            }
            $arrayOptions = unserialize($fieldArray['serializeOptions']);
            $arrayCount	  = count($arrayOptions);
            if($arrayCount > 0)
            {
                    // To detect first index with null value
                    $arrayOptions = Mage::getModel('flexibleforms/flexibleforms')->getOptionControl($arrayOptions);
                    $arrControlOption=array();
                    foreach($arrayOptions as $key => $arrayOption)
                    {
                        if(strpos($key,'{{selected}}'))
                        {
                           $key=trim(str_replace('{{selected}}','',$key));
                           $arrControlOption[$key]=$key;
                        }
                        else
                        {
                            $arrControlOption[$key]=trim($key);
                        }
                    }
            }
            return $arrControlOption;
    }
    public function getCountryArray()
    {
        $_countries = Mage::getResourceModel('directory/country_collection')
                            ->loadData()
                            ->toOptionArray(false);

        foreach($_countries as $_country):
            $arrOptions[$_country['label']]= $_country['label'];
        endforeach;
        return $arrOptions;
    }

    public function resultFilter($formId,$field_id,$value)
    {
        $resultCollection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('form_id', $formId);
        $resultIds=array();
	foreach($resultCollection as $result){
            $valueArr=array();
            $valueArr = unserialize($result->getValue());
            //For filture multicheckbox option start
            foreach($valueArr as $key => $a){
                if(count($a) > 0 && is_array($a))
                {
                    $valueArr[$key] = implode(',',$a);
                }
            }
            //For filture multicheckbox option end
            $swipArr= array_flip($valueArr);
            if(in_array($field_id,$swipArr))
            {
                //Filter login for multiselect and checkbox
                $fieldCollection = Mage::getModel('flexibleforms/fields')->getCollection()
                        ->addFieldToFilter('form_id', $formId)
                        ->addFieldToFilter('field_id', $field_id)
                        ->getFirstItem();
                if($fieldCollection->getType()=='multiselect' || $fieldCollection->getType()=='checkbox')
                {
                    $arrOption = @explode(',',$valueArr[$field_id]);
                    if(in_array($value,$arrOption))
                    {
                        $resultIds[]= $result->getResultId();
                    }
                }
                else if($fieldCollection->getType()=="sendcopytome")
                {
                    if($value==1)
                    {
                        if(!empty($value) && $value == $valueArr[$field_id])
                        {
                            $resultIds[]= $result->getResultId();
                        }
                    }
                    else if($value==2)
                    {
                        if(!empty($value) && $value == $valueArr[$field_id])
                        {
                            
                            $resultIds[]= $result->getResultId();
                        }
                    }
                }
                else if($fieldCollection->getType()=='date' || $fieldCollection->getType()=='datetime')
                {
                    if(is_array($value))
                    {
                        if( isset($value['orig_from']) && !empty($value['orig_from']) && isset($value['orig_to']) && !empty($value['orig_to']) )
                        {
                            if( (date('d-m-Y',strtotime($value['orig_from'])) <= date('d-m-Y',strtotime($valueArr[$field_id]))) && (date('d-m-Y',strtotime($value['orig_to'])) >= date('d-m-Y',strtotime($valueArr[$field_id])))){

                               $resultIds[]= $result->getResultId(); 
                            }
                        }
                        else if(isset($value['orig_from']) && !empty($value['orig_from']) )
                        {
                           if(date('d-m-Y',strtotime($value['orig_from'])) <= date('d-m-Y',strtotime($valueArr[$field_id]))){
                                $resultIds[]= $result->getResultId();
                            }
                        }
                        else if(isset($value['orig_to']) && !empty($value['orig_to']) )
                        {

                            if(date('d-m-Y',strtotime($value['orig_to'])) >= date('d-m-Y',strtotime($valueArr[$field_id]))){
                                $resultIds[]= $result->getResultId();
                            }
                        }
                    }
                }
        	else if(strpos($valueArr[$field_id], $value)===0)
		{
                    $resultIds[]= $result->getResultId();
		}
            }
	}
        return $resultIds;
    }
    public function removeFiles($formId,$serializevalue)
    {
        //Files path
        $pathImage = Mage::getBaseDir().DS.'media/flexibleforms/images'.DS;
        $pathFiles = Mage::getBaseDir().DS.'media/flexibleforms/files'.DS;
        //Field forms collection
        $flexibleformsfields = Mage::getModel('flexibleforms/fields')->getCollection()
                                ->addFieldToFilter('form_id', array('eq' => $formId ))
                                ->addFieldToFilter(array('type','type'), array('file','image'));
        //Retrive result from result table
        $unserlizedArray = unserialize($serializevalue);
        $fieldId=0;
        foreach($flexibleformsfields as $fields):
            $fieldId= $fields->getFieldId();
            $fieldType = $fields->getType();
            $filename =($fieldType == 'file')? $pathFiles.$unserlizedArray[$fieldId] :$pathImage.$unserlizedArray[$fieldId] ;
            if(file_exists($filename)):
                unlink($filename);
            endif;
        endforeach;
        return true;
    }
	public function filterProductNameColumn($value)
	{
		$collectionp = Mage::getModel('catalog/product')->getCollection();
		$collectionp= $collectionp->addAttributeToFilter('name', array('like' => '%'.$value.'%'));
		foreach($collectionp as $p)
		{
			$productIds[] = $p['entity_id'];
		}
		if(count($productIds)):
			foreach($productIds as $pIds):
				$resultCollection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('product_id',$pIds);

				foreach($resultCollection as $result):
					$resultIds[] = $result->getResultId();
				endforeach;

			endforeach;
		endif;
		return $resultIds;
	}
	public function filterProductSkuColumn($value)
	{
		$collectionp = Mage::getModel('catalog/product')->getCollection();
		$collectionp= $collectionp->addAttributeToFilter('sku', array('like' => '%'.$value.'%'));
		foreach($collectionp as $p)
		{
			$productIds[] = $p['entity_id'];
		}
		if(count($productIds)):
			foreach($productIds as $pIds):
				$resultCollection = Mage::getModel('flexibleforms/result')->getCollection()->addFieldToFilter('product_id',$pIds);

				foreach($resultCollection as $result):
					$resultIds[] = $result->getResultId();
				endforeach;

			endforeach;
		endif;
		return $resultIds;
	}
    
}