<?php
@ini_set('display_errors','ON');
@ini_set('soap.wsdl_cache_enable' , 0);
@ini_set('soap.wsdl_cache_ttl' , 0);
@ini_set("memory_limit","1024M");



define("ROOT_PATH", 'D:/xampp/htdocs/magento/');

define("WS_MAGENTO_USER", 'admin');
define("WS_MAGENTO_PASS", 'admin@123');
require_once ROOT_PATH.'app/Mage.php';
umask(0);
echo $fromDate=date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
exit;
		$collection = Mage::getModel('customer/customer')->getCollection();
                  //->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$fromDate));
				  $result = array();
	    foreach ($collection as $customer) {
	        $result[] = $customer->toArray();
			print_r($result);
	    }
		exit;

// Get the resource model
$resource = Mage::getSingleton('core/resource');
 
// Retrieve the read connection
$readConnection = $resource->getConnection('core_read');
 
// Retrieve the write connection
$writeConnection = $resource->getConnection('core_write');

 $resource   = Mage::getSingleton('core/resource');
    $readConnection       = $resource->getConnection('dash_read');
    $results    = $readConnection->query('SELECT * FROM ox_dashing_customer');
     
    print_r($results); exit;
/*$base_url =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
$proxy = new SoapClient($base_url.'index.php/api/soap/?wsdl',array('trace' => 1));
$sessionId = $proxy->login(WS_MAGENTO_USER, WS_MAGENTO_PASS);

$attributeSets = $proxy->call($sessionId, 'product_attribute_set.list');
$set = current($attributeSets);
*/
try
{
/*Update Bundle Item Product*/
/*echo "in"; echo "<pre>";
$bundle_id=9; $bundle_item_id=10;

$bundled = Mage::getModel('catalog/product');
        $bundled->load($bundle_id);
		//print_r($bundled->getData());
$data=$bundled->getData();
$data['affect_product_custom_options']=1;
$data['bundle_options'] =array(0 => array('title' => "sample", 'option_id' =>"",'delete' => "",'type' => "select", 'required' => 1, 'position' => 0));
$data['affect_bundle_product_selections'] = 1;
$data['bundle_selections'] =array(0 => array (0 => array( 'selection_id' => "",
                            'option_id' => "", 'product_id' => 6, 'delete' => "",
							'selection_price_value' => 0.00, 'selection_price_type' => 0,
                            'selection_qty' => 1, 'selection_can_change_qty' => 1,
                            'position' => 0),
							1 => array( 'selection_id' => "",
							'option_id' => "", 'product_id' => 8, 'delete' => "",
                            'selection_price_value' => 0.00, 'selection_price_type' => 0,
                            'selection_qty' => 1, 'selection_can_change_qty' => 1,
                            'position' => 0)
							));
							//print_r($data);
$update=Mage::getModel('catalog/product')->load($bundle_id);
$update->getResource()->save($update);

    $bundled_product = new Mage_Catalog_Model_Product();
    $bundled_product->load(9);
    $selectionCollection = $bundled_product->getTypeInstance(true)->getSelectionsCollection(
        $bundled_product->getTypeInstance(true)->getOptionsIds($bundled_product), $bundled_product);
    $bundled_items = array();
    foreach($selectionCollection as $option)
    {
        $bundled_items[] = $option->product_id;
    }
    print_r($bundled_items);

exit;
//delete option
/*$productCheck = Mage::getModel('catalog/product')->load($bundle_id);
$productCheck->getData();
$optionRawData = array();
$optionRawData[0] = array(
'required' => 1,
'option_id' => 0,
'position' => 0,
'type' => 'select',
'title' => 'FooOption',
'default_title' => 'FooOption',
'delete' => 1,
);
 
$selectionRawData = array();
$selectionRawData[0] = array();
$selectionRawData[0][] = array(
'product_id' => 6,
'selection_qty' => 1,
'selection_can_change_qty' => 1,
'position' => 0,
'is_default' => 1,
'selection_id' => '',
'selection_price_type' => 0,
'selection_price_value' => 0.0,
'option_id' => '',
'delete' => 1
);
$productCheck->setCanSaveConfigurableAttributes(false);
$productCheck->setCanSaveCustomOptions(true);
// Set the Bundle Options & Selection Data
$productCheck->setBundleOptionsData($optionRawData);
$productCheck->setBundleSelectionsData($selectionRawData);
$productCheck->setCanSaveBundleSelections(true);
$productCheck->setAffectBundleProductSelections(true);
 
$productCheck->save();
echo $productCheck->getId();
exit;*/

/*
 $bundled_product = new Mage_Catalog_Model_Product();
    $bundled_product->load($bundle_id);
    $selectionCollection = $bundled_product->getTypeInstance(true)->getSelectionsCollection(
        $bundled_product->getTypeInstance(true)->getOptionsIds($bundled_product), $bundled_product
    );
    $bundled_items = array();
    foreach($selectionCollection as $option)
    {
        $bundled_items[] = $option->product_id;
    }
    print_r($bundled_items);
*/



/*Create Virutual product*/
/*$storeID = 1;
$websiteIDs = array(1);
$cats=array(2,3,4);
$sku		= "sample-virtual-product3";
$name		= "sample virtual product3";
$price		= 900;
$qty		= 100;
$weight		= 4;
$shortdes	= "sample virtual product3";
$description	= "sample virtual product3";
					
$is_in_stock = ($qty > 0)?1:0;
$stockdata = array('manage_stock' => 1,'qty' => $qty,'is_in_stock' => $is_in_stock);
$newProductData = array(
		'name'              => "sample virtual product3",
		'websites'          => array(1),
		'categories'	    => $cats,
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
echo $proxy->call($sessionId, 'product.create', array('virtual', $set['set_id'], $sku, $newProductData));
echo $updatedskus = $sku."\n";*/
/*Create bundle product*/
$storeID = 1;
$websiteIDs = array(1);
$cats=array(2,3,4);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$productCheck = Mage::getModel('catalog/product');
 
$p = array(
'sku_type' => 0,
'sku' => 'abc2',
'name' => "BarProduct",
'description' => 'Foo',
'short_description' => 'Bar',
'type_id' => 'bundle',
'attribute_set_id' => 4,
'weight_type' => 0,
'visibility' => 4,
'price_type' => 0,
'price_view' => 0,
'status' => 1,
'created_at' => strtotime('now'),
'category_ids' => $cats,
'store_id' => $storeID,
'website_ids' => $websiteIDs
);
 
$productCheck->setData($p);
Mage::register('product', $productCheck);
 
$optionRawData = array();
$optionRawData[0] = array(
'required' => 1,
'option_id' => '',
'position' => 0,
'type' => 'select',
'title' => 'FooOption',
'default_title' => 'FooOption',
'delete' => '',
);
 
$selectionRawData = array();
$selectionRawData[0] = array();
$selectionRawData[0][] = array(
'product_id' => 6,
'selection_qty' => 1,
'selection_can_change_qty' => 1,
'position' => 0,
'is_default' => 1,
'selection_id' => '',
'selection_price_type' => 0,
'selection_price_value' => 0.0,
'option_id' => '',
'delete' => ''
);
$selectionRawData[0][] = array(
'product_id' => 8,
'selection_qty' => 1,
'selection_can_change_qty' => 1,
'position' => 0,
'is_default' => 1,
'selection_id' => '',
'selection_price_type' => 0,
'selection_price_value' => 0.0,
'option_id' => '',
'delete' => ''
);
Mage::register('productCheck', $productCheck);
Mage::register('current_product', $productCheck);
$productCheck->setCanSaveConfigurableAttributes(false);
$productCheck->setCanSaveCustomOptions(true);
// Set the Bundle Options & Selection Data
$productCheck->setBundleOptionsData($optionRawData);
$productCheck->setBundleSelectionsData($selectionRawData);
$productCheck->setCanSaveBundleSelections(true);
$productCheck->setAffectBundleProductSelections(true);
 
$productCheck->save();
echo $productCheck->getId();
}
catch(Exception $e)
{
echo $e->getMessage();
}
//https://gist.github.com/tim-bezhashvyly