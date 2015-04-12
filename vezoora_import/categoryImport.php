<?php
try
{
	@require_once 'includes/import.php';
	echo LOCAL_FILE_CATEGORY;
	if($csv = importProducts(LOCAL_FILE_CATEGORY, 'category'))
	{
            //echo "<pre>"; print_r($csv); exit;
		$attributeCategoryList=$GLOBALS["attributeCategoryList"];
		$data = $csv['category'];
		$count= $csv['count'];
		$startPoint = '0';
		for($i = $startPoint; $i < $count; $i++)
		{
			foreach($attributeCategoryList as $attributeCode)
			{
                            If($attributeCode=="store_id")
                            {
                                $storeId=trim($data[$attributeCode][$i]);
                            }
                            elseif($attributeCode=="original_id")
                            {
                                $originalId=trim($data[$attributeCode][$i]);
                            }
                            else
                            {
				$categoryData[$attributeCode]=trim($data[$attributeCode][$i]);
                            }
			}
			$categoryAttributeDefault=$GLOBALS["categoryAttributeDefault"];
			foreach($categoryAttributeDefault as $attributeCode=>$value)
			{
				$categoryData[$attributeCode] = $value;
			}
			try
			{
                            if($originalId=="" || $originalId==0 || $originalId=="0")
                            {
                                $_category = Mage::getModel('catalog/category')->loadByAttribute('name',$data['name'][$i]);
				if(method_exists($_category,"getId"))
				{
					$proxy->call($sessionId, 'catalog_category.update', array($_category->getId(), $categoryData));
					$message .= "Category Updated ".$data['name'][$i]."\n";
				}
				else
				{
					$proxy->call($sessionId, 'catalog_category.create', array($data['parent_category_id'][$i], $categoryData));
					$message .= "Category Created ".$data['name'][$i]."\n";
				}
                            }
                            else
                            {
                                $_category = Mage::getModel('catalog/category')->load($originalId);
                                $proxy->call($sessionId, 'catalog_category.update', array($_category->getId(), $categoryData,$storeId));
                                $message .= "Category Updated ".$data['name'][$i]."\n";
                            }
//				$_category = Mage::getModel('catalog/category')->loadByAttribute('name',$data['name'][$i]);
//				if(method_exists($_category,"getId"))
//				{
//					$proxy->call($sessionId, 'catalog_category.update', array($_category->getId(), $categoryData));
//					$message .= "Category Updated ".$data['name'][$i]."\n";
//				}
//				else
//				{
//					$proxy->call($sessionId, 'catalog_category.create', array($data['parent_category_id'][$i], $categoryData));
//					$message .= "Category Created ".$data['name'][$i]."\n";
//				}
			}
			catch(Exception $e)
			{
				$message .= 'Error in category Creation :'.$data['name'][$i]." and error is ".$e->getMessage()."\n";
			}
			unset($bundleOptionsValues);
		}
		echo $finalmessage = "Update Catelog Category Names :\n".$message;
		updateLog($finalmessage,LOCAL_FILE_CATEGORY_RESPONSE);
	}
}
catch(Exception $e)
{
	echo "Please Check the all the settings, For More Information : The error is , ".$e->getMessage();
}
?>