<?php
try
{
require_once 'settings.php';
@ini_set('display_errors','ON');
@ini_set('soap.wsdl_cache_enable' , 0);
@ini_set('soap.wsdl_cache_ttl' , 0);
@ini_set("memory_limit","1024M");

define("DOC_ROOT",$_SERVER['DOCUMENT_ROOT']);
$rootPath=DOC_ROOT."/";
if(SITE_FOLDER!="") $rootPath=DOC_ROOT."/".SITE_FOLDER."/";
define("ROOT_PATH", $rootPath);
define("IMAGE_PATH", ROOT_PATH.'vezoora_import/source/images/');
define("CSV_PATH", ROOT_PATH.'vezoora_import/source/csv/');
define("RESPONSE_PATH", ROOT_PATH.'vezoora_import/source/response/');

require_once ROOT_PATH.'app/Mage.php';

umask(0);

$base_url =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
$proxy = new SoapClient($base_url.'index.php/api/soap/?wsdl',array('trace' => 1));
$sessionId = $proxy->login(WS_MAGENTO_SOAP_USER, WS_MAGENTO_SOAP_PASS);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$unwantedAttributeArray=array("gift_message_available",
							"is_recurring",
							"msrp_display_actual_price_type",
							"msrp_enabled",
							"options_container",
							"price_view",
							"weight_type");
$date=date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
define("LOCAL_FILE_PRODUCT", CSV_PATH.'catalog/product-'.$date.'.csv');
define("LOCAL_FILE_CATEGORY", CSV_PATH.'catalog/category-'.$date.'.csv');
define("LOCAL_FILE_CUSTOMER", CSV_PATH.'customer/customer-'.$date.'.csv');
define("LOCAL_FILE_PRODUCT_RESPONSE", RESPONSE_PATH.'product-'.$date.'.txt');
define("LOCAL_FILE_CATEGORY_RESPONSE", RESPONSE_PATH.'category-'.$date.'.txt');
define("LOCAL_FILE_CUSTOMER_RESPONSE", RESPONSE_PATH.'customer-'.$date.'.txt');
define("PRODUCT_IMAGES_DIR", IMAGE_PATH.$date."/");
$stockDataAttribute=array('manage_stock','qty','is_in_stock');
$imageArray=array('image1','image2','image3','image4','image5');
$bundleAttribute1=array('option_title1','option_type1','option_skus1','option_status1');
$bundleAttribute2=array('option_title2','option_type2','option_skus2','option_status2',);
$bundleAttribute3=array('option_title3','option_type3','option_skus3','option_status3');
$bundleAttribute=array_merge($bundleAttribute1,$bundleAttribute2,$bundleAttribute3);


// Define Attribute Details
/*******************************
Please Don't change anything in Compulsory Fields and Optional Fields. 
For more attribute to be adding to the csv means, add it in this array ($attributeCodeClientRequest);
*********************************/
/*Compulsory Fields for Product Import*/
$attributeCodeCompulsory=array('product_type', // simple,configurable,bundle,virtual,grouped,downloadable
					'name',
					'description',
					'short_description',
					'sku',
					'weight',
					'status',
					'visibility',
					'price',
					'tax_class_id',
					'manage_stock',
					'qty',
					'is_in_stock',
					'category_ids',// with comma separated
					'grouped_product_skus', // Fill if the product type is grouped
					'associated_skus', // Fill if the product type is configurable, simple sku with comma separated
					'configurable_attributes', // Fill if the product type is configurable, atribute id with comma separated.
					'option_title1', // Fill if the product type is bundle
					'option_type1',// select,radio,checkbox, multi - Give values like this
					'option_skus1',// with comma separated
					'option_status1',// give add or delete
					'option_title2', // Fill if the product type is bundle
					'option_type2',
					'option_skus2',// with comma separated
					'option_status2',// give add or delete
					'option_title3', // Fill if the product type is bundle
					'option_type3',
					'option_skus3',// with comma separated
					'option_status3'// give add or delete
					);
/*Optional Fields for Product Import*/
$attributeCodeOptional=array('news_from_date',
					'news_to_date',
					'url_key',
					'country_of_manufacture',
					'manufacturer',
					'color',
					'special_price',
					'special_from_date',
					'special_to_date',
					'meta_title',
					'meta_keyword',
					'meta_description',
					'image1',
					'image2',
					'image3',
					'image4',
					'image5'
					);

/*Category Attribute  for Category Import*/
/****************************************
Please dont change anything in the below mentioned arrays.
/*Compulsory Fields for Category Import*/
$categoryAttributeCompusory=array('parent_category_id',
						'name',
						'is_active',
						);
/*Optional Fields for Category Import*/
$categoryAttributeOptional=array('custom_design', // theme settings
						'custom_apply_to_products',//1 or 0
						'custom_design_from',
						'custom_design_to',
						'custom_layout_update',
						'description',
						'is_anchor', //1 or 0
						'meta_description',
						'meta_keywords',
						'meta_title',
						'page_layout', // one column / two column
						'url_key',
						'include_in_menu',// 1 or 0
                                                'store_id',
                                                'original_id'
						);
/*Default Fields for Category Import*/
$categoryAttributeDefault=array('position'=>1,
						'available_sort_by'=>'position',
						'default_sort_by'=>'position',
						'display_mode' => null,
						'landing_page' => null,
						);
/*Customer Attribute for Customer Import*/
//Please dont change anything in the below mentioned arrays.
/*Compulsory Fields for Customer Import*/
$customerAttributeCompusory=array('email','firstname','lastname','password');
/*Optional Fields for Customer Import*/
$customerAttributeOptional=array('prefix','suffix','dob','taxvat','gender','middlename');
/*Default Fields for Customer Import*/
$customerAttributeDefault=array('website_id'=>1,'store_id'=>1,'group_id'=>1);

@require_once 'dataSource.php';
@require_once 'csvAccess.php';
@require_once 'sendMail.php';
@require_once 'class.php';
}
catch(Exception $e)
{
	echo "Please Check the all the settings, For More Information : The error is , ".$e->getMessage();
}
?>
