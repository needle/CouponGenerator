<?php
class Needle_CouponGenerator_Block_Admin_New extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
 
        $this->_blockGroup = 'coupongenerator';
        $this->_mode = 'new';
        $this->_controller = 'admin';
        
        $this->_updateButton('save', 'label', Mage::helper('customer')->__('Generate!'));
    }
 
    public function getHeaderText()
    {
        return Mage::helper('coupongenerator')->__('Generate New Bulk Coupon');
    }
    
    public function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addJs('needle/needle.js');   
        return parent::_prepareLayout();
    }
}