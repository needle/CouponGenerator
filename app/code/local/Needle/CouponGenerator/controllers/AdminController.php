<?php
class Needle_CouponGenerator_AdminController extends Mage_Adminhtml_Controller_Action
{	 
	function randomString($length = 5, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
		$chars_length = (strlen($chars) - 1);
		$string = $chars{rand(0, $chars_length)};

		for ($i = 1; $i < $length; $i = strlen($string))
		{
			$r = $chars{rand(0, $chars_length)};
			if ($r != $string{$i - 1}) 
				$string .=	$r;
		}

		return $string;
	}
	
	public function indexAction()
	{
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('coupongenerator/admin_main'))
			->renderLayout();
	}
	
	/**
	 * Export grid to CSV format
	 */
	public function exportCsvAction()
	{
		$fileName	= 'coupons.csv';
		$content	= $this->getLayout()->createBlock('coupongenerator/admin_main_grid')
			->getCsvFile();

		$this->_prepareDownloadResponse($fileName, $content);
	}
	
	public function newAction()
	{
        // $this->loadLayout()
        // ->_addContent($this->getLayout()->createBlock('coupongenerator/admin_new'))
        // ->renderLayout();
        
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('coupongenerator/admin_new'));
        $this->renderLayout();
	}
	
	public function postAction()
	{
		if ($data = $this->getRequest()->getPost()) 
		{
			$api = Mage::getModel('coupongenerator/api');
			$numCoupons		= $this->getRequest()->getPost('quantity');
			$sourceRuleID	= $this->getRequest()->getPost('rule_id');
			$couponPrefix	= $this->getRequest()->getPost('prefix');
			$namePrefix		= $this->getRequest()->getPost('name_prefix');
			$expireDate		= $this->getRequest()->getPost('expires');		
			$strLen			= $this->getRequest()->getPost('rand_len'); 
			$parentRule		= $api->info($sourceRuleID);
			$parentName		= $parentRule['name'];
			 
			try 
			{
				for ($i = 0; $i < $numCoupons; $i++)
				{
					$newName = $namePrefix . ":" . substr($parentName, 10);
					$couponCode = $couponPrefix . "-" . $this->randomString($strLen);
					$api->cloneRule($sourceRuleID, $newName, $couponCode, $expireDate);
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('coupongenerator')
					->__('Coupons were generated successfully!'));
				$this->getResponse()->setRedirect($this->getUrl('*/*/'));
				return;
			} 
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->getResponse()->setRedirect($this->getUrl('*/*/'));
		return;
	}

	public function massDeleteAction()
	{
		$ruleIds = $this->getRequest()->getParam('rule');
		if (!is_array($ruleIds)) 
		{
			 Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select rule(s)'));
		} 
		else 
		{
			try 
			{
				$rule = Mage::getModel('salesrule/rule');
				foreach ($ruleIds as $ruleId) 
				{
					$rule->load($ruleId)
						->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('adminhtml')->__(
						'Total of %d record(s) were successfully deleted', count($ruleIds)
					)
				);
			} 
			catch (Exception $e) 
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}

		$this->_redirect('*/*/index');
	}
	
	public function deleteAction()
	{
		$ruleId = $this->getRequest()->getParam("rule_id");
		try 
		{
			$rule = Mage::getModel("salesrule/rule");
			$rule->load($ruleId)->delete();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule was deleted'));
		}
		catch (Exception $e)
		{
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index');
	}
}