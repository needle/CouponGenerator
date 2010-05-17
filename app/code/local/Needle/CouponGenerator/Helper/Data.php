<?php
 
class Needle_CouponGenerator_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Clone a salesrule to a single-use salesrule. This version of the method
     * differs from the one in Api in that it does not do anything to the parent coupon.
     *
     * @param int $sourceSalesruleId 
     * @param string $newName
     * @param string $couponCode
     * @param string $expireDate
     * @return int
     */
    public function cloneRule($sourceSalesruleId, $newName, $couponCode, $expireDate)
    {
        $numUsesPerCoupon = 1;
        $numUsesPerCustomer = 1;
        
        $parentrule = Mage::getModel('salesrule/rule')->load($sourceSalesruleId);


        if (!$parentrule->getId()) 
        {
            $this->_fault('not_exists');
        }
        $parentruleData = $parentrule->toArray();
   
        /**
         * Set up the new rule by creating a new model, setting the
         * $newRuleData (array) to the $parentruleData (array) values
         * and then overriding the attributes that we care about         
        */
        $newRule = Mage::getModel('salesrule/rule');
        $newRuleData = $parentruleData;
        unset($newRuleData['rule_id']);
        $newRuleData['name'] = $newName;
        $newRuleData['coupon_code'] = $couponCode;
        if(isset($expireDate))
            $newRuleData['to_date'] = $expireDate;
        $newRuleData['uses_per_coupon'] = '1';
        $newRuleData['uses_per_customer'] = '1';
        $newRuleData['is_active'] = '1';
        $newRuleData['times_used'] = '0';
        
        $newRule->setData($newRuleData);
        
        // Now try to save the child
        try 
        {
            $newRule->save();
        } 
        catch (Mage_Core_Exception $e) 
        {
            $this->_fault('data_invalid', $e->getMessage());
        }
        
        return $newRule->getId();
    } 
}