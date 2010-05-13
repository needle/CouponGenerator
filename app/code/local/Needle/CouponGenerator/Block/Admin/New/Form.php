<?php
class Needle_CouponGenerator_Block_Admin_New_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $model = Mage::getModel('salesrule/rule');
        $templates = $model->getCollection();
        $templates->addFieldToFilter('uses_per_coupon', array('gt' => 0));
        $templates->addFieldToFilter('name', array('like' => '_TEMPLATE:%'));

        $ruleKV = array();
        
        foreach ($templates as $t)
        {
            $ruleKV[$t['rule_id']] = substr($t['name'], 10);
        }
 
        $fieldset = $form->addFieldset('new_bulkcoupon', array('legend' => Mage::helper('coupongenerator')->__('Coupon Details')));
        
        $fieldset->addField('rule_id', 'select', array(
            'name'      => 'rule_id',
            'title'     => Mage::helper('coupongenerator')->__('Template Rule'),
            'label'     => Mage::helper('coupongenerator')->__('Template Rule'),
            'maxlength' => '250',
            'required'  => true,
            'values'    => $ruleKV
        ));

        $fieldset->addField('prefix', 'text', array(
            'name'      => 'prefix',
            'title'     => Mage::helper('coupongenerator')->__('Coupon Prefix'),
            'label'     => Mage::helper('coupongenerator')->__('Coupon Prefix'),
            'maxlength' => '50',
            'required'  => true,
        ));
        
        $fieldset->addField('name_prefix', 'text', array(
            'name'      => 'name_prefix',
            'title'     => Mage::helper('coupongenerator')->__('Rule Name Prefix'),
            'label'     => Mage::helper('coupongenerator')->__('Rule Name Prefix'),
            'maxlength' => '50',
            'required'  => true,
        ));         
         
        $fieldset->addField('quantity', 'text', array(
            'name'      => 'quantity',
            'title'     => Mage::helper('coupongenerator')->__('Quantity'),
            'label'     => Mage::helper('coupongenerator')->__('Quantity'),
            'maxlength' => '50',
			'class' => 'input-text required-entry validate-quantity',            
            'required'  => true,
        ));
        
        $fieldset->addField('rand_len', 'text', array(
            'name'      => 'rand_len',
            'title'     => Mage::helper('coupongenerator')->__('Random String Length'),
            'label'     => Mage::helper('coupongenerator')->__('Random String Length'),
            'maxlength' => '2',
			'class' => 'input-text required-entry validate-rand_len',
            'required'  => true,
        ));

	    $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
	        Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
	    );
        
        $fieldset->addField('expires', 'date', array(
            'name'      => 'expires',
            'title'     => Mage::helper('coupongenerator')->__('Expires'),
            'label'     => Mage::helper('coupongenerator')->__('Expires'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif')
        ));

        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/post'));
 
        $this->setForm($form);
    }
}