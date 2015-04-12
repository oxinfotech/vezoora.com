<?php
try
{
	require_once 'includes/import.php';

	$categoriesArray = Mage::getModel('catalog/category')
        ->getCollection()
        ->addAttributeToSelect('name')
        ->addAttributeToSort('path', 'asc')
        ->addFieldToFilter('is_active', array('eq'=>'1'))
        ->load()
        ->toArray();


    foreach ($categoriesArray as $categoryId => $category)
	{
        if (isset($category['name'])) {
            $categories[] = array(
                'label' => $category['name'],
                'level'  =>$category['level'],
                'value' => $categoryId
            );
        }
    }
	
	foreach($categories as $value)
	{
		echo "<ul>";
		foreach($value as $key => $val)
		{
			if($key=='label')
			{
				$catNameIs = $val;
			}
			if($key=='value')
			{
				$catIdIs = $val;
			}
			if($key=='level')
			{
				$catLevelIs = $val;
				$b ='';
				for($i=1;$i<$catLevelIs;$i++)
				{
					$b = $b."\t";
				}
			}
		}
		echo "<li>"."category id \t ".$catIdIs." \t and name is \t ".$b.$catNameIs."</li>";	
		echo "</ul>";
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
}
?>