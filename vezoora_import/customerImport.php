<?php
try
{
	@require_once 'includes/import.php';
	echo LOCAL_FILE_CUSTOMER;
	if($csv = importProducts(LOCAL_FILE_CUSTOMER, 'customer'))
	{
		$attributeCustomerList=$GLOBALS["attributeCustomerList"];
		$data = $csv['customer'];
		$count= $csv['count'];
		$startPoint = '0';
		for($i = $startPoint; $i < $count; $i++)
		{
			foreach($attributeCustomerList as $attributeCode)
			{
				$customerData[$attributeCode]=trim($data[$attributeCode][$i]);
			}
			$customerAttributeDefault=$GLOBALS["customerAttributeDefault"];
			foreach($customerAttributeDefault as $attributeCode=>$value)
			{
				$customerData[$attributeCode] = $value;
			}
			try
			{
				$customer = Mage::getModel("customer/customer");
				$customer->setWebsiteId(1);
				$customer->loadByEmail($data['email'][$i]);
				
				if(count($customer->getData())>0)
				{
					try
					{
						$proxy->call($sessionId, 'customer.update', array('customerId' => $customer->getId(), 'customerData' => $customerData));
						$message .= "Customer Updated ".$data['email'][$i]."\n";
					}
					catch(Exception $e)
					{
						$message .= 'Error in Customer Updation :'.$data['email'][$i]." and error is ".$e->getMessage()."\n";
					}
				}
				else
				{
					try
					{
						$proxy->call($sessionId,'customer.create',array($customerData));
						$message .= "Customer Created ".$data['email'][$i]."\n";
					}
					catch(Exception $e)
					{
						$message .= 'Error in Customer Creation :'.$data['email'][$i]." and error is ".$e->getMessage()."\n";
					}
				}
			}
			catch(Exception $e)
			{
				$message .= 'Error in Customer :'.$data['email'][$i]." and error is ".$e->getMessage()."\n";
			}
		}
		echo $finalmessage = "Update Customer Names :\n".$message;
		updateLog($finalmessage,LOCAL_FILE_CATEGORY_RESPONSE);
	}
}
catch(Exception $e)
{
	echo "Please Check the all the settings, For More Information : The error is , ".$e->getMessage();
}
?>