<?php

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		// show form
		echo "form of the request";
		?>

		<form method="post">
		<input type="text" name="amzproduct">
		<button>Submit</button>
		</form>		
		<?
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		/* Example usage of the Amazon Product Advertising API */
		include("class.amazon.rest.php");
	
		$amzproduct_ASIN	= $_POST["amzproduct"];
		if( strlen($amzproduct_ASIN) > 10)
		{
			$amzproduct_ASIN	=	amazon_get_asin_code($amzproduct_ASIN);
		}
		
		//
		// Instanitate an OBJECT
		// $obj = new AmazonProductAPI();
		require_once 'amazon_keys.inc';
		$obj = new AmazonProductAPI($public_key, $private_key);
			
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
			echo "no price for this item, might be out of stock!<br>";
		}
		
		// display the retrived basic information about this item
		displayProductBasicInfo($productInfo);
		
		
		// in case this product has differe sizes and colors
		// how many colors/sizes/etc.. we can get this product ?
		// =====================================================================================
		
		// get parent ASIN 
		$parent_ASIN	= $oProdcut->Items->Item->ParentASIN;
		
		$parent			= getItemParentByASIN($parent_ASIN);
		
		$itemVariation	= getItemVariation($parent);	
		
		//
		// display values to ensure everything is okay.
		// needs a for loop itemVariation[index] echo "<br><b> $i - ". $parent->Items->Item->Variations->Item[$i]->ASIN ."</b><br> ";
		echo "<br>we have {$itemVariation['totalItemsAvailable']} items available <br>";
		echo "<br><br>for $amzproduct_ASIN parent is $parent_ASIN and we have VariationDimension array size: [". $itemVariation['varAttributeCount'] ."].<br>";
	
		//print_r($itemVariation);
    }
	
?>