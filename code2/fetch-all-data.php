<?php

	$links 	= file("links.txt");

	//die(var_dump($links));
	/* Example usage of the Amazon Product Advertising API */
	include("class.amazon.rest.php");

	//
	// Instanitate an OBJECT
	// $obj = new AmazonProductAPI();
	require_once 'amazon_keys.inc';
	$obj = new AmazonProductAPI($public_key, $private_key);
	
	foreach($links as $link)
	{
		$amzproduct_ASIN	=	amazon_get_asin_code($link);
			
		$oProdcut			= getProductObject( $amzproduct_ASIN );
		$productInfo		= parseProductXML( $oProdcut );
		
		// saveJson( jsonifyArray ($productInfo) );
		$filepath			= "../data/$amzproduct_ASIN.json";
		file_put_contents($filepath, jsonifyArray ($productInfo) );
	

		// check if price is in the ListPrice
		if($productInfo['Price'] == 0)
		{
			$result = $obj->getItemByAsinOffers($amzproduct_ASIN);
			//print_r($result);
			// echo "no price for this item, might be out of stock!<br>";
		}
				
		// in case this product has differe sizes and colors
		// how many colors/sizes/etc.. we can get this product ?
		// =====================================================================================
		
		// get parent ASIN 
		$parent_ASIN	= $oProdcut->Items->Item->ParentASIN;

		$parent			= getItemParentByASIN($parent_ASIN);
		
		$itemVariation	= getItemVariation($parent);	
		
		//
		// display values to ensure everything is okay.
		echo "<br>we have {$itemVariation['totalItemsAvailable']} items available <br>";
		echo "<br><br>for $amzproduct_ASIN parent is $parent_ASIN and we have VariationDimension array size: [". $itemVariation['varAttributeCount'] ."].<br>";
	}	
		//print_r($itemVariation);
    
?>