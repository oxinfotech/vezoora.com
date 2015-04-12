<?php
@require_once 'includes/config.php';
@require_once 'includes/dataSource.php';
@require_once 'includes/csvAccess.php';
@require_once 'includes/sendMail.php';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$attributeSets = $proxy->call($sessionId, 'product_attribute_set.list');
$set = current($attributeSets);

$message = '';
$updatedskus = '';
//Processing the Simple Product
$starttime = date('Y-m-d H:i:s', mktime(date('H')+5,date('i')+30,date('s'),date('m'),date('d'),date('Y')));
sendMail('product_simple_start','',$starttime);
//Read the downloaded the file and parse them and add it in the database
if($csv = importProducts(LOCAL_FILE_SIMPLE, 'simple')) {
	$data =  $csv['product'];
	$startPoint = '0';
	
	for($i = $startPoint; $i < $csv['count']; $i++) {
		
		//Fetch all the data
		$sku		= $data['sku'][$i];
		$name		= $data['name'][$i];
		$price		= $data['price'][$i];
		$qty		= $data['qty'][$i];
		$weight		= $data['weight'][$i];
		$size		= $data['size'][$i];
		$style		= $data['style'][$i];
		$shortdes	= $data['short_description'][$i];
		$description	= $data['long_description'][$i];
					
		$is_in_stock = ($qty > 0)?1:0;
		$stockdata = array('manage_stock' => 1,'qty' => $qty,'is_in_stock' => $is_in_stock);
		$newProductData = array(
				'name'              => $name,
				'websites'          => array('base'),
				'short_description' => $shortdes,
				'description'       => $description,
				'status'            => 1,
				'weight'            => $weight,
				'tax_class_id'      => "0",
				'price'             => $price,
				'stock_data'        => $stockdata,
				'size'              => $size,
				'style'             => $style,
				'visibility'        => 1
			);
		
		try {
			$proxy->call($sessionId, 'product.create', array('simple', $set['set_id'], $sku, $newProductData));
			$updatedskus .= $sku."\n";
		}
		catch(Exception $e){
			if($e->getMessage() === 'The value of attribute "SKU" must be unique') {
				try{
					$sku .= ' '; 
					$proxy->call($sessionId, 'product.update', array($sku, $newProductData));
					$updatedskus .= $sku."\n";
				}
				catch(Exception $e) {
					$message .= 'Error in SIMPLE Product Updation:'.$sku."\n";
				}
			}
			else
				$message .= 'Error in SIMPLE Product Creation:'.$sku."\n";
		}
	}
	$finalmessage = "Update Simple Product Skus:\n".$updatedskus."\n\n".$message;
	sendMail('product_simple_end',$finalmessage,$starttime);
}
else {
	sendMail('product_simple_csv_error','','');
}

//Processing the Configure products
$starttime = date('Y-m-d H:i:s', mktime(date('H')+5,date('i')+30,date('s'),date('m'),date('d'),date('Y')));
$message = '';
$updatedskus = '';
sendMail('product_config_start','',$starttime);
//Read the downloaded the file and parse them and add it in the database
if($csv = importProducts(LOCAL_FILE_CONFIGURE, 'configure')) {
	$data =  $csv['product'];
	$startPoint = '0';
	
	//Brand array
	$brandCollection = array("5" => "Basics", "4" => "Genesis", "3" => "Probase");
	
	for($i = $startPoint; $i < $csv['count']; $i++) {
		
		//Fetch all the data
		$sku		= $data['sku'][$i];
		$categoryids	= $data['categoryid'][$i];
		$name		= $data['name'][$i];
		$price		= $data['price'][$i];
		$discountprice	= $data['discountprice'][$i];
		$discountpercent= $data['discountpercent'][$i];
		$color		= $data['color'][$i];
		$brand		= $data['brand'][$i];
		$product	= $data['product'][$i];
		$design		= $data['design'][$i];
		$fabric		= $data['fabric'][$i];
		$fit		= $data['fit'][$i];
		$occasion	= $data['occasion'][$i];
		$neck		= $data['neck'][$i];
		$washcare	= $data['washcare'][$i];
		$shortdes	= $data['short_description'][$i];
		$description	= $data['long_description'][$i];
		$simpleproducts	= $data['simple_products'][$i];
		$cuff		= $data['cuff'][$i];
		$garment_styleno= $data['garment_styleno'][$i];
		$item_type	= $data['itemtype'][$i];
		$category_name	= $data['category'][$i];
		$lyrics		= $data['lyrics'][$i];
		$front_pocket	= $data['front_pocket'][$i];
		$back_pocket	= $data['back_pocket'][$i];
		$garment_type	= $data['garmenttype'][$i];
		$season		= $data['season'][$i];
		$placket	= $data['placket'][$i];
		$season_order	= $data['season_order'][$i];
		$images[0]	= $data['image_1'][$i];
		$images[1]	= $data['image_2'][$i];
		$images[2]	= $data['image_3'][$i];
		$images[3]	= $data['image_4'][$i];
		$images[4]	= $data['image_5'][$i];
		$product_description	= $data['product_description'][$i];
		$related_code 	= $data['related_code'][$i];
		$size_chart 	= $data['size_chart'][$i];
		$is_trouser 	= $data['is_trouser'][$i];
		
		//Make the category array
		$categoryid = explode(",", $categoryids);
					
		//Make the simple products array
		$simpleproduct = explode(",", $simpleproducts);

		//Set Discount Price Empty if there is no price
		if($discountprice == ' ' || $discountprice == '0' || $discountprice == '0.00' || $discountprice == '0,00')
			$discountprice = '';
					
		$stockdata = array('manage_stock' => 1,'use_config_manage_stock' => 1, 'is_in_stock' => 1);
		$attributes_data = array('use_default' => 1, 'position' => 0, 'label' => 'size');
		$newProductData = array(
				'name'              => $name,
				'websites'          => array('base'),
				'categories'	    => $categoryid,
				'short_description' => $shortdes,
				'description'       => $description,
				'product_description' => $product_description,
				'related_code' => $related_code,
				'size_chart' => $size_chart,
				'has_options'	    => 1,
				'status'            => 1,
				'tax_class_id'      => "0",
				'price'             => $price,
				'special_price'     => $discountprice,
				'stock_data'        => $stockdata,
				'color'             => $color,
				'brand'             => $brand,
				'product'           => $product,
				'design'            => $design,
				'fabric'            => $fabric,
				'fit'     	    => $fit,
				'occasion'          => $occasion,
				'neck'              => $neck,
				'washcare'          => $washcare,
				'cuff'              => $cuff,
				'garment_styeno'    => $garment_styleno,
				'itemtype'          => $item_type,
				'category_name'     => $category_name,
				'lyrics'            => $lyrics,
				'front_pocket'      => $front_pocket,
				'back_pocket'       => $back_pocket,
				'garment_type'      => $garment_type,
				'season'            => $season,
				'placket'           => $placket,
				'season_order'      => $season_order,
				'associated_skus'   => $simpleproduct,
				'visibility'        => 4
			);
		
		//print_r($newProductData);
		//echo "\n\n\n";
		//Trousers in 14 - shop by category, 87 - basics,40 -genesis, 86 - probase, 119 - sale
		$trouserCategory=array(14,87,40,86,119);

		try {
			if($productId=$proxy->call($sessionId, 'product.create', array('configurable', $set['set_id'], $sku, $newProductData))) {
				$updatedskus .= $sku."\n";
				//echo 'Created: '.$sku."\n";
				//Add the images
				for($img = 4;$img >= 0;$img--) {
					if($images[$img] != '') {
						$getImage   = IMAGE_PATH.trim($images[$img]);
						$getName    = $brandCollection[$brand].' '.$img;
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
						    try {
							$proxy->call($sessionId, 'product_media.create', array($getSku, $newImage));
						    }
						    catch(Exception $e) {
							$message .= "Error adding image to $getSku: " . $e->getMessage() . "\n" ;
						    }
						}
						else{
							$message .= "Couldn't find the image: ".$getImage. "\n" ;
						}
					}
				}
				
				/*Adding Custom Option*/
				//$is_trouser=array_intersect($trouserCategory,$categoryid);
				//if(count($is_trouser)>0)
				if($is_trouser)
				{
					$_product = Mage::getModel('catalog/product')->load($productId);
					$attVal = $_product->getOptions();
					if(count($attVal)==0)
					{
						try
						{
							$customTextFieldOption = array("title" => "Enter Trouser Height",
										       "type" => "field",
										       "is_require" => 0,
										       "sort_order" => 0,
										       "additional_fields" => array( array( "price" => '', "price_type" => "fixed", "sku" => "", "max_characters" => '' ) )
										       );
							$resultCustomTextFieldOptionAdd = $proxy->call( $sessionId, "product_custom_option.add", array( $productId, $customTextFieldOption ));
						}
						catch(Exception $e)
						{
							$message .= "Error adding custom option to $getSku: " . $e->getMessage() . "\n" ;
						}
					}
					unset($_product);
					unset($attVal);
					unset($is_trouser);
				}
				/*End of Adding Custom Option*/
			}
		}
		catch(Exception $e){
			if($e->getMessage() === 'The value of attribute "SKU" must be unique') {
				try{
					$sku .= ' '; 
					$proxy->call($sessionId, 'product.update', array($sku, $newProductData));
					$updatedskus .= $sku."\n";
					
					//echo 'Updated: '.$sku."\n";
					
					//Update the image if it doesn't exists
				}
				catch(Exception $e) {
					$message .= 'Error in CONFIG Product Updation:'.$sku."\n";
				}
			}
			else
				$message .= 'Error in CONFIG Product Creation:'.$e->getMessage()."\n";
		}
	}
	
	$finalmessage = "Update Configure Product Skus:\n".$updatedskus."\n\n".$message;
	sendMail('product_config_end',$finalmessage,$starttime);
}
else {
	sendMail('product_config_csv_error','','');
}
?>
