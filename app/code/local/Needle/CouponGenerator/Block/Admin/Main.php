<?php
class Needle_CouponGenerator_Block_Admin_Main extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_addButtonLabel = Mage::helper('coupongenerator')->__('Generate Coupons!');
        parent::__construct();
 
        $this->_blockGroup = 'coupongenerator';
        $this->_controller = 'admin_main';
        $this->_headerText = Mage::helper('coupongenerator')->__('Single Use Coupons');
    }
}