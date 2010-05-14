# Name
**CouponGenerator** - Create and manage single-use coupons for Magento stores.

# Usage
    <?php

    $client = new SoapClient('http://yourmagentohost/api/?wsdl', array("trace" => 1));
    $session = $client->login('username', 'password');
    
    // Set the <ParentCouponID> to your parent coupon's ID.
    $pId = <ParentCouponID>;
    
    // Make a SOAP call to the service, passing in options as an array.  Change the obvious values in between the <>
    $id = $client->call($session, 'coupongenerator.clonerule', array($pId, '<NEWCOUPONNAME>', '<NEWCOUPONCODE>', '<EXPIREDATE>'));
    
    // Retrieve your new coupon and print out the object that you are returned.  (This should just be a single array().)
    $result = $client->call($session, 'coupongenerator.info', $id);
    print_r($result);
    
    // Close the session now that we are done.
    $client->endSession($session);
    ?>
  
# Description
The CouponGenerator is designed for creating single-use coupons for Magento stores.  It allows coupons to be generated for a single customer.  It uses all data from the parent coupon, so care must be taken while creating the parent coupons.  

For the purpose of this document, 'coupon' is used interchangeably with 'sales rule' in the Magento world.

# Store Setup Best Practices
Since the CouponGenerator uses already existing coupons from which to clone new single-use coupons, it is important that the parent coupon is set up properly.

## Parent Setup
* **Name** - The parent coupon name should use a meaningful prefix to set it apart from other coupons.  The name of the coupon should be as descriptive as possible, while still being short enough to not mess with UIs (your custom UI and/or the magento admin UI).
** Example: _TEMPLATE:Fifty Percent Off Underwear
* **Description** - The description field is very important for letting your users know what the coupon does.  It is important to remember that the parent coupon's description field will be inherited directly by the new coupon.  This field is currently not overridden by the cloning process.
* **Status** - The parent coupon's status should be set to 'Inactive' so that the parent coupon cannot be used.
* **Public in RSS Feed** - This should be set to 'No' so that the parent coupon is not publicly visible.
* **Uses per coupon** - Set this to how many cloned coupons you would like to allow.  The cloning process will decrement this number, and then not allow any more coupons to be cloned when it reaches zero.  You can always add more coupons to the parent if you would like to allocate more.
* **To Date** - If you would like all cloned To Dates to be inherited from the parent, set this date and do not pass it as part of your SOAP call.

## Caveats
* The cloned coupon code must be unique.
* The new coupon name does not need to be unique, but it should be something that does not clutter the store and can be sorted out of the list of results.  A good example format is **__ONEUSE:Coupon Name**.  This will cause the cloned coupons to be listed at the very end of the coupon list when sorted by name.

## Packaging
Packaging this application for distribution is relatively simple:
	cd CouponGenerator
	tar cvzf app js CouponGenerator-<version_number>.tgz

## Installation
Copy the CouponGenerator-<version_number>.tgz to the customer's Magento installation root and extract.  Simple as that!
