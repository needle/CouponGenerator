<?php
class Needle_CouponGenerator_Block_Admin_Main_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('coupongeneratorGrid');
        $this->_controller = 'coupongenerator';
    }
 
    protected function _prepareCollection()
    {
        $model = Mage::getModel('salesrule/rule');
        $collection = $model->getCollection();
        #$collection->addFieldToFilter('name', array('like' => '__ONEUSE:%'));
	$this->setCollection($collection);
 
        $this->addExportType('*/*/exportCsv', Mage::helper('coupongenerator')->__('CSV'));
        return parent::_prepareCollection();
    }
 
 
 
    protected function _prepareColumns()
    {
 
        $this->addColumn('rule_id', array(
            'header'        => Mage::helper('coupongenerator')->__('ID'),
            'align'         => 'right',
            'width'         => '50px',
            'filter_index'  => 'rule_id',
            'index'         => 'rule_id',
        ));
	
	$this->addColumn('name', array(
            'header'        => Mage::helper('coupongenerator')->__('Rule Name'),
            'align'         => 'left',
            'width'         => '50px',
            'filter_index'  => 'name',
            'index'         => 'name',
        ));
 
        $this->addColumn('coupon_code', array(
            'header'        => Mage::helper('coupongenerator')->__('Coupon Code'),
            'align'         => 'left',
            'width'         => '150px',
            'filter_index'  => 'coupon_code',
            'index'         => 'coupon_code',
            'type'          => 'text',
            'truncate'      => 50,
            'escape'        => true,
        ));

        $this->addColumn('description', array(
            'header'        => Mage::helper('coupongenerator')->__('Coupon Description'),
            'align'         => 'left',
            'filter_index'  => 'description',
            'index'         => 'description',
            'type'          => 'text',
            'escape'        => true,
        ));
 
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('coupongenerator')->__('Action'),
                'width'     => '150px',
                'type'      => 'action',
                'getter'	=> 'getRuleId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('coupongenerator')->__('Delete'),
                        'url'     => array(
                            'base'=>'*/*/delete'
                         ),
                         'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false, 
                'is_system' => true
        ));
 
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('rule');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('coupongenerator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('coupongenerator')->__('Are you sure?')
        ));
        return $this;
    }    
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $row->getTipId(),
        ));
    }
}