<?php
try
{
	require_once 'includes/import.php';

	$productAttrs = Mage::getResourceModel('catalog/product_attribute_collection');
	foreach ($productAttrs as $productAttr)
	{
		$attribute_code=$productAttr->getAttributeCode();
		if(!in_array($attribute_code,$unwantedAttributeArray))
		{
			$attribute_details = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attribute_code);
			$options = $attribute_details->getSource()->getAllOptions(false);
			if(count($options)>0)
			{
				echo $productAttr->getFrontendLabel()." ( Attribute Id : ".$productAttr->getId()." )";
				echo "<ul>";
				foreach($options as $option)
				{
					if($option["value"]!="") echo "<li>".$option["value"]." : ".$option["label"]."</li>";
				}
				echo "</ul>";
			}
		}
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
}
?>
