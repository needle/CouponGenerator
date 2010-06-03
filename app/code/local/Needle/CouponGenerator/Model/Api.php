<?php

class Needle_CouponGenerator_Model_Api extends Mage_Api_Model_Resource_Abstract
{
    public function items($filters)
     {
         $collection = Mage::getModel('salesrule/rule')->getCollection();
      
         if (is_array($filters)) 
         {
             try 
             {
                 foreach ($filters as $field => $value) 
                 {
                     $collection->addFieldToFilter($field, $value);
                 }
             } 
             catch (Mage_Core_Exception $e) 
             {
                 $this->_fault('filters_invalid', $e->getMessage());
                 // If we are adding filter on non-existent attribute
             }
         }
      
         $result = array();
         foreach ($collection as $salesrule) 
         {
             $result[] = $salesrule->toArray();
         }
      
         return $result;
     }
     
     /**
      * Retrieve salesrule data
      *
      * @param int $salesruleId
      * @param array $attributes
      * @return array
      */
     public function info($salesruleId, $attributes = null)
     {
         $salesrule = Mage::getModel('salesrule/rule')->load($salesruleId);

         if (!$salesrule->getId()) 
         {
             $this->_fault('not_exists');
         }

         return $salesrule->toArray();
     }
     
     /**
      * Clone a salesrule to a single-use salesrule
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
    
         if($parentruleData['uses_per_coupon'] < 1)
         {
             $this->_fault('no_uses_left', 'Parent coupon is out of uses.');    
             return 0;         
         }   
         
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
         {
             $newDate = Mage::app()->getLocale()->date($expireDate, Zend_Date::DATE_SHORT);
             $newRuleData['to_date'] = $newDate->toString('YYYY-MM-dd');
         }
         $newRuleData['uses_per_coupon'] = '1';
         $newRuleData['uses_per_customer'] = '1';
         $newRuleData['is_active'] = '1';
         $newRuleData['times_used'] = '0';
         
         /** Do some stuff to the original parent now that we have set the 
          * attributes on the child.
          * * Decrement uses_per_coupon
          * * Increment uses_per_customer
         */
         $parentruleData['uses_per_coupon']--;
         $parentruleData['uses_per_customer']++;
         
         // Set the data before trying to save it.
         $parentrule->setData($parentruleData);
         $newRule->setData($newRuleData);
         
         
         // Try to save the parent first
         try 
         {
             $parentrule->save();
         } 
         catch (Mage_Core_Exception $e) 
         {
             $this->_fault('data_invalid', $e->getMessage());
         }
         
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
            
    /**
     * Delete salesrule
     *
     * @param int $salesruleId
     * @return boolean
     */
    public function delete($salesruleId)
    {
        $salesrule = Mage::getModel('salesrule/rule')->load($salesruleId);

        if (!$salesrule->getId()) 
        {
            $this->_fault('not_exists');
        }

        try 
        {
            $salesrule->delete();
        } 
        catch (Mage_Core_Exception $e) 
        {
            $this->_fault('not_deleted', $e->getMessage());
        }

        return true;
    }   
}