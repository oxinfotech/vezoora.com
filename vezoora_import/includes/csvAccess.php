<?php
$attributeList=array_merge($attributeCodeCompulsory,$attributeCodeOptional,$attributeCodeClientRequest,$customoptions);
$attributeCategoryList=array_merge($categoryAttributeCompusory,$categoryAttributeOptional);
$attributeCustomerList=array_merge($customerAttributeCompusory,$customerAttributeOptional);
function importProducts($local_file, $type)
{
    $csv = new File_CSV_DataSource;
    if($csv->load($local_file)) {
        if($type === 'product')
		{
			$attributeList=$GLOBALS["attributeList"];
			foreach($attributeList as $attributeCode)
			{
				$product[$attributeCode] = $csv->getColumn($attributeCode);
			}
			$productDetails = array('product'=>$product,'count'=>$csv->countRows());
        }
		if($type === 'category')
		{
			$attributeCategoryList=$GLOBALS["attributeCategoryList"];
			foreach($attributeCategoryList as $attributeCode)
			{
				$category[$attributeCode] = $csv->getColumn($attributeCode);
			}
			$productDetails = array('category'=>$category,'count'=>$csv->countRows());
        }
		if($type === 'customer')
		{
			$attributeCustomerList=$GLOBALS["attributeCustomerList"];
			foreach($attributeCustomerList as $attributeCode)
			{
				$customer[$attributeCode] = $csv->getColumn($attributeCode);
			}
			$productDetails = array('customer'=>$customer,'count'=>$csv->countRows());
        }
        return $productDetails;
    }
    else
	{
        return false;
    }
}
?>
