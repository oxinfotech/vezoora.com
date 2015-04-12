<?php
try
{
	@require_once 'includes/import.php';
	$attributeSets = $proxy->call($sessionId, 'product_attribute_set.list');
	$set = current($attributeSets);
	echo LOCAL_FILE_PRODUCT;
	if($csv = importProducts(LOCAL_FILE_PRODUCT, 'product'))
	{
		$attributeList=$GLOBALS["attributeList"];
		$data = $csv['product'];
		$count= $csv['count'];
		$startPoint = '0';
		for($i = $startPoint; $i < $count; $i++)
		{
			$productData=array(); $images=array(); $stockdata=array(); $bundleOptionsValues=array();
			foreach($attributeList as $attributeCode)
			{
				$productData[$attributeCode]=trim($data[$attributeCode][$i]);
				if(in_array($attributeCode,$stockDataAttribute))
				{
					$stockdata[$attributeCode] = $data[$attributeCode][$i];
				}
				if($attributeCode=="category_ids" && $data[$attributeCode][$i]!="")
				{
					$productData[$attributeCode]=explode(",",$data[$attributeCode][$i]);
				}
				if($attributeCode=="associated_skus" && $data[$attributeCode][$i]!="" && $data['product_type'][$i]=="configurable")
				{
					$productData[$attributeCode]=explode(",",$data[$attributeCode][$i]);
				}
				if($attributeCode=="configurable_attributes" && $data[$attributeCode][$i]!="" && $data['product_type'][$i]=="configurable")
				{
					$productData[$attributeCode]=explode(",",$data[$attributeCode][$i]);
				}
				if(in_array($attributeCode,$imageArray) && $data[$attributeCode][$i]!="")
				{
					$images[]=$data[$attributeCode][$i];
				}
				if(in_array($attributeCode,$bundleAttribute) && $data[$attributeCode][$i]!="")
				{
					if(in_array($attributeCode,$bundleAttribute1) && $data[$attributeCode][$i]!="")
					{
						$bundleOptionsValues[1][]=$data[$attributeCode][$i];
					}
					else if(in_array($attributeCode,$bundleAttribute2) && $data[$attributeCode][$i]!="")
					{
						$bundleOptionsValues[2][]=$data[$attributeCode][$i];
					}
					else if(in_array($attributeCode,$bundleAttribute3) && $data[$attributeCode][$i]!="")
					{
						$bundleOptionsValues[3][]=$data[$attributeCode][$i];
					}
				}
				if($attributeCode=="grouped_product_skus" && $data[$attributeCode][$i]!="" && $data['product_type'][$i]=="grouped")
				{
					$groupedProductSkus=explode(",",$data[$attributeCode][$i]);
				}
                                if($attributeCode=="option_title" || $attributeCode=="option_values" || $attributeCode=="option_values_price" || $attributeCode=="is_option_required")
                                {
                                    $customOptionsArray=array('option_title'=>$data['option_title'][$i],"option_values"=>$data['option_values'][$i],"option_values_price"=>$data['option_values_price'][$i],"is_option_required"=>$data['is_option_required'][$i]);
                                }
			}

			$productData['stock_data']=$stockdata;
			$sku=$data['sku'][$i];
			try
			{
				$proxy->call($sessionId, 'product.create', array($data['product_type'][$i], $set['set_id'], $sku , $productData));
				if(count($images)>0) $message .=createImage($images,$sku,$proxy,$sessionId)." : ";
				if($data['product_type'][$i]=="bundle") $message .=mapProductsToBundle($sku,$bundleOptionsValues)." : ";
				if($data['product_type'][$i]=="grouped") $message .=mapProductsToGroup($sku,$groupedProductSkus)." : ";
                                if($customOptionsArray) $message .=createCustomOption($customOptionsArray,$sku,$proxy,$sessionId)." : ";
				$message .= "Product Created ".$sku."\n";
			}
			catch(Exception $e)
			{
				if($e->getMessage()!='The value of attribute "SKU" must be unique')
				{
					try
					{
						$proxy->call($sessionId, 'product.create', array($sku, $productData));
						if(count($images)>0) $message .=createImage($images,$sku,$proxy,$sessionId)." : ";
						if($data['product_type'][$i]=="bundle") $message .=mapProductsToBundle($sku,$bundleOptionsValues)." : ";
						if($data['product_type'][$i]=="grouped") $message .=mapProductsToGroup($sku,$groupedProductSkus)." : ";
                                                if($customOptionsArray) $message .=createCustomOption($customOptionsArray,$sku,$proxy,$sessionId)." : ";
						$message .= "Product Created : ".$sku."\n";
					}
					catch(Exception $e)
					{
						$message .= 'Error in Product Updation:'.$sku." and error is ".$e->getMessage()."\n";
					}
				}
				else
				{
					try
					{
						$proxy->call($sessionId, 'product.update', array($sku, $productData,'','sku'));
						if(count($images)>0) $message .=createImage($images,$sku,$proxy,$sessionId)." : ";
						if($data['product_type'][$i]=="bundle") $message .=mapProductsToBundle($sku,$bundleOptionsValues)." : ";
						if($data['product_type'][$i]=="grouped") $message .=mapProductsToGroup($sku,$groupedProductSkus)." : ";
						$message .= "Product Updated : ".$sku."\n";
					}
					catch(Exception $e)
					{
						$message .= 'Error in Product Updation:'.$sku." and error is ".$e->getMessage()."\n";
					}
				}
			}
			unset($bundleOptionsValues);
		}
		echo $finalmessage = "Update Product Skus : \n".$message;
		updateLog($finalmessage,LOCAL_FILE_PRODUCT_RESPONSE);
	}
}
catch(Exception $e)
{
	echo "Please Check the all the settings, For More Information : The error is , ".$e->getMessage();
}

function createImage($images,$sku,$proxy,$sessionId)
{
	$message='';
	for($img = 4;$img >= 0;$img--)
	{
		if($images[$img] != '') {
			$getImage   = PRODUCT_IMAGES_DIR.trim($images[$img]);
			$getName    = $img;
			$getSku     = $sku.' ';
	   
			$handle = @fopen($getImage, "r");
			
			if(strpos($handle, "Resource id") !== false) { 
				$newImage = array(
				'file' => array(
					'name' => $getName,
					'content' => base64_encode(file_get_contents($getImage)),
					'mime'    => 'image/jpeg'
				),
				'label'    => $getName,
				'position' => $img,
				'types'    => array('small_image','image','thumbnail'),
				'exclude'  => 0
				);
				try
				{
					$proxy->call($sessionId, 'product_media.create', array($getSku, $newImage));
				}
				catch(Exception $e)
				{
					$message .= "Error adding image to $getSku: " . $e->getMessage() . "\n" ;
				}
			}
			else
			{
				$message .= "Couldn't find the image: ".$getImage. "\n" ;
			}
		}
	}
	return $message;
}

function mapProductsToBundle($bundle_sku,$seletiondatas)
{
	try
	{
		$productid = Mage::getModel('catalog/product')->getIdBySku(trim($bundle_sku));
		$productCheck = Mage::getModel('catalog/product')->load($productid);
		Mage::register('product', $productCheck);
		$j=0;
		foreach ($seletiondatas as $seletiondata)
		{
			$option_title=$seletiondata[0];
			$option_type=$seletiondata[1];
			$option_skus=$seletiondata[2];
			$option_status=$seletiondata[3];
			$partsArray=explode(",",$option_skus);
			
			if($option_status=="add")
			{
				$optionRawData[$j] = array(
					'required' => 0,
					'option_id' => '',
					'position' => 0,
					'type' => $option_type,
					'title' => $option_title,
					'default_title' => $option_title,
					'delete' => '',
				);
				$k=0;
				//$selectionRawData[0] = array();
				foreach($partsArray as $pn)
				{
					$product_id = Mage::getModel("catalog/product")->getIdBySku($pn);
					$selectionRawData[$j][] = array(
						'product_id' => $product_id,
						'selection_qty' => 1,
						'selection_can_change_qty' => 0,
						'position' => $k,
						'is_default' => 0,
						'selection_id' => '',
						'selection_price_type' => 0,
						'selection_price_value' => 0.0,
						'option_id' => '',
						'delete' => ''
					);
					$k++;
				}
			}
			if($option_status=="delete")
			{
				$optionRawData[] = array(
					'required' => 0,
					'option_id' => '',
					'position' => 0,
					'type' => $option_type,
					'title' => $option_title,
					'default_title' => $option_title,
					'delete' => 1,
				);
				$k=0;
				//$selectionRawData[0] = array();
				foreach($partsArray as $pn)
				{
					$product_id = Mage::getModel("catalog/product")->getIdBySku($pn);
					$selectionRawData[$j][] = array(
						'product_id' => $product_id,
						'selection_qty' => 1,
						'selection_can_change_qty' => 0,
						'position' => $k,
						'is_default' => 0,
						'selection_id' => '',
						'selection_price_type' => 0,
						'selection_price_value' => 0.0,
						'option_id' => '',
						'delete' => 1
					);
					$k++;
				}
			}
			$j++;
		}
		//echo "selectionRawData"; print_r($selectionRawData);
		//echo "optionRawData"; print_r($optionRawData);
		
		$productCheck->setCanSaveConfigurableAttributes(true);
		$productCheck->setCanSaveCustomOptions(true);
		// Set the Bundle Options & Selection Data
		$productCheck->setBundleSelectionsData($selectionRawData);
		$productCheck->setBundleOptionsData($optionRawData);
		$productCheck->setCanSaveBundleSelections(true);
		$productCheck->setAffectBundleProductSelections(true);
		$productCheck->save();
		
		$updatedskus = "Bundle Product Mapped : ".$bundle_sku;
		//Unset of Variables
		Mage::unregister('product');
		unset($partsArray);
		unset($selectionRawData);
		unset($optionRawData);
		unset($productCheck);
		return $updatedskus;
	}
	catch(Exception $e)
	{
		return "Error in Mapped Bundle product : ".$e->getMessage();
	}
}

function mapProductsToGroup($sku,$groupedProductSkus)
{
	try
	{
		$product_id = Mage::getModel("catalog/product")->getIdBySku($sku);
		$product = Mage::getModel("catalog/product")->load($product_id);
		$relationData = array();
		foreach($groupedProductSkus as $assSku)
		{
			$simpleProductId = Mage::getModel('catalog/product')->getIdBySku($assSku);
			$relationData[$simpleProductId] = array('qty' => 0, 'position' => 0);
		}
		$product->setGroupedLinkData($relationData);	
		$product->save();
		return "Mapped group product";
	}
	catch(Exception $e)
	{
		return "Error in Mapped group product : ".$e->getMessage();
	}
}

function createCustomOption($customOptionsArray,$sku,$proxy,$sessionId)
{
    try
    {
        $productId = Mage::getModel("catalog/product")->getIdBySku($sku);
        $optional_values=$customOptionsArray['option_values'];
        $optional_values_array=explode(",",$optional_values);
        $option_values_price=$customOptionsArray['option_values_price'];
        $optional_values_price_array=explode(",",$option_values_price);
        $i=0;
        foreach($optional_values_array as $option)
        {
            $additional_fields[]=array("title" => $option,
                    "price" => $optional_values_price_array[$i],
                    "price_type" => "fixed",
                    "sku" => "",
                    "sort_order" => 0);
            $i++;
        }
        $customDropdownOption = array(
            "title" => $customOptionsArray['option_title'],
            "type" => "radio",
            "is_require" => $customOptionsArray['is_option_required'],
            "sort_order" => 10,
            "additional_fields" => $additional_fields
        );
        
        if($customOptionsArray['option_title']!="")
        {
            $resultCustomTextFieldOptionAdd = $proxy->call($sessionId,"product_custom_option.add",array($productId,$customDropdownOption));
            return "Custom Option created. ";
        }
    } catch (Exception $e) {
        return "Error in Custom Option creation : ".$e->getMessage();
    }
}
?>