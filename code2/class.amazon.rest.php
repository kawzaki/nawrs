<?

	include("amazon_api_class.php");
		
	// ======================================================================================================
	// return JSON formatted object 
	// before returning any result, it must be formatted here.
	// ======================================================================================================	
	function response( $json_data)
	{
		header('Content-Type: application/json; charset=utf-8');
		die ($json_data);
	}
	
	// ======================================================================================================	
	// jsonifyArray
	// takes an array and encode it as json object
	// ======================================================================================================	
	function jsonifyArray($arrVar)
	{
		return $json_response = json_encode( $arrVar);
		//return $json_response = json_encode( $arrVar, JSON_UNESCAPED_UNICODE);
	}

	
	
	// ========================================================================================================= 
	//
	// ========================================================================================================= 
	function amazon_get_asin_code($url) 
	{
		global $debug;
		
		$debug = false;

		$result = "";

		$pattern = "([A-Z0-9]{10})(?:[/?]|$)";
		$pattern = escapeshellarg($pattern);

		preg_match($pattern, $url, $matches);

		if($debug) {
			var_dump($matches);
		}

		if($matches && isset($matches[1])) {
			$result = $matches[1];
		} 

		return $result;
	}

	// ========================================================================================================= 
	//
	// ========================================================================================================= 
	function getProductObject( $amzproduct_ASIN ) 
	{
		global $obj;

		try
		{
			$result = $obj->getItemByAsin($amzproduct_ASIN);
										   
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}

		/// print_r($result);
		return $result;
	}
	
	


	// ========================================================================================================= 
	// parse XML resonse and extrac the needed info
	// ========================================================================================================= 
	function parseProductXML( $xmlProduct )
	{
		// 
		// Get title, price and image URL from the response		
		//
		// PackageDimensions and weight

		$height 			= 	$xmlProduct->Items->Item->ItemAttributes->PackageDimensions->Height;
		$height				= 	number_format((float) ($height / 100), 2, '.', '');
					
		$length 			= 	$xmlProduct->Items->Item->ItemAttributes->PackageDimensions->Length;
		$length				= 	number_format((float) ($length / 100), 2, '.', '');
			
		$width 				= 	$xmlProduct->Items->Item->ItemAttributes->PackageDimensions->Width;
		$width				= 	number_format((float) ($width / 100), 2, '.', '');
		
		// package dimensional weight 
		$pkgDimWeight		= 	ceil(($length*$width * $height)/139);
		
		// convert package dimensional weight to KG
		$pkgDimWeightKG		= 	($pkgDimWeight/2.2);
		
		// round to xx.xx format
		$pkgDimWeightKG		= 	round( $pkgDimWeightKG *2) / 2;
		
		// check it is not 0, otherwise default is 0.5 kg
		$pkgDimWeightKG		= 	($pkgDimWeightKG > 0) ? $pkgDimWeightKG : 0.5;
				
		$pkgCostPerKG		= 	( ($pkgDimWeightKG/0.5) * 25);
			
		//
		// Item Dimensions and weight
		
		if (isset($xmlProduct->Items->Item->ItemAttributes->ItemDimensions))
		{
			$itemHeight 	= 	$xmlProduct->Items->Item->ItemAttributes->ItemDimensions->Height;
			$itemHeight		= 	number_format((float) ($itemHeight / 100), 2, '.', '');
					
			$itemLength 	= 	$xmlProduct->Items->Item->ItemAttributes->ItemDimensions->Length;
			$itemLength		= 	round(number_format((float) ($itemLength / 100), 2, '.', ''), 1);
		
			$itemWidth 		= 	$xmlProduct->Items->Item->ItemAttributes->ItemDimensions->Width;
			$itemWidth		= 	number_format((float) ($itemWidth / 100), 2, '.', '');
		
			$itemWeight 	= 	$xmlProduct->Items->Item->ItemAttributes->ItemDimensions->Weight;
			$itemWeight		= 	number_format((float) ($itemWeight / 100), 2, '.', '');
			
			// cost of shipping based on actual item weight
			// convert to kg and round the number: 
			$itemWeightKg	= 	($itemWeight/2.2);
			$itemWeightKg	= 	round( $itemWeightKg *2) / 2;
			
			$itemWeightCost	= 	( ($itemWeightKg/0.5) * 25);

		
			// Dimensional Weight for item:
			$dimWeight		= 	ceil(($itemLength*$itemWidth * $itemHeight)/139);
	
			// convert to kg and round the number, ensure not 0:
			$dimWeightKg	= 	($dimWeight/2.2);
			$dimWeightKg	= 	round( $dimWeightKg *2) / 2;
			
		}

		// dimensional weight calculation (if not given, assume 0.5kg
		$dimWeightKg		= ($dimWeightKg > 0) ? $dimWeightKg : 0.5;
		$dimCostPerKG		= ( ($dimWeightKg/0.5) * 25);
		
		// compare shipping cost Pure Item WeightDimensional VS Package Dimensional Weight
		$calShipCost		= ( $itemWeightKg > $pkgDimWeightKG) ? "Item Weight: " . $itemWeightCost : "Pkg Dim Weight: " . $pkgCostPerKG;
		
		$priceOffer			= "" . $xmlProduct->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice;
		$price 				= $xmlProduct->Items->Item->ItemAttributes->ListPrice->Amount;	
		$price				= number_format(($price/ 100), 2, '.', '');
		$image_url			= "" . $xmlProduct->Items->Item->MediumImage->URL;
				
		$isPrime			= ($xmlProduct->Items->Item->Offers->Offer->OfferListing->IsEligibleForPrime || 0) ? "1" : "0";	

		$size 				= (string) $xmlProduct->Items->Item->ItemAttributes->Size;
		$size 				= ( empty($size) ) ?  "- - -" : $size;
	
		$Color 				= (string) $xmlProduct->Items->Item->ItemAttributes->Color;
		$Color 				= ( empty($Color) ) ?  "- - -" : $Color;
				
		$Product			= array( "ASIN"				=>	(string) $xmlProduct->Items->Item->ASIN,
									 "Title" 			=>	(string) $xmlProduct->Items->Item->ItemAttributes->Title,
									 "Brand" 			=>	(string) $xmlProduct->Items->Item->ItemAttributes->Brand,
									 "ProductGroup" 	=>	(string) $xmlProduct->Items->Item->ItemAttributes->ProductGroup,
									 "ProductTypeName" 	=>	(string) $xmlProduct->Items->Item->ItemAttributes->ProductTypeName,
									 "Height"			=>	$height,
									 "Width"			=>	$width,
									 "Length"			=>	$length,
									 "ItemLength"		=>	$itemLength,
									 "ItemHeight"		=>	$itemHeight,
									 "ItemWidth"		=>	$itemWidth,
									 "ItemWeight"		=>	$itemWeight,
									 "ItemWeightKg"		=>	$itemWeightKg,
									 "PkgDimWeight"		=>	$pkgDimWeight,
									 "pkgDimWeightKG"	=>	$pkgDimWeightKG,
									 "PkgCostPerKG"		=>	$pkgCostPerKG,
									 "DimWeight"		=>	$dimWeight,
									 "DimWeightKg"		=>	$dimWeightKg,
									 "DimCostPerKG"		=>	$dimCostPerKG,
									 "CalShipCost"		=>	$calShipCost,
									 "Color"			=>	$Color,
									 "Size"				=>	$size,
									 "PriceOffer"		=>	$priceOffer,
									 "Price" 			=>  $price,
									 "Image_url"		=>	$image_url,
									 "IsPrime"			=>	$isPrime
								);
		return $Product;			                             
	}
	
	// ========================================================================================================= 
	// display product info 
	// ========================================================================================================= 
	function displayProductBasicInfo($productInfo)
	{
		//
		// Print the title, price, ASIN and image URL to screen
		echo "<li><b>Title:</b> {$productInfo['Title']}<li><b>Price: </b>{$productInfo['Price']}<li><b>Image URL:</b> {$productInfo['Image_url']}<br /><br />";
		
		echo "<h3>Shipping Package details:</h3> ";
		echo "<li>Dimensions: L: {$productInfo['Length']}  Width: {$productInfo['Width']}  H: {$productInfo['Height']} <br>";
		echo "<li>Dimensionsal weight: {$productInfo['PkgDimWeight']} <br>";
		echo "<li>Dimensionsal weight KG: {$productInfo['pkgDimWeightKG']} <br>";
		echo "<li>Cost per 0.5 KG: ". ($productInfo['pkgDimWeightKG']/0.5) ."x25= " .( ($productInfo['pkgDimWeightKG']/0.5) * 25);
		
		// shipping details
		echo "<h3>Shipping Item details:</h3>";
		echo "<li>Dimensions: L: {$productInfo['ItemLength']}  Width: {$productInfo['ItemWidth']} H: {$productInfo['ItemHeight']} Weight: {$productInfo['Weight']} <br>";
		echo "<li>Dimensionsal weight: {$productInfo['DimWeight']} <br>";
		echo "<li>Dimensionsal weight in KG: ". ($productInfo['DimWeightKg']) ."<br>";
		echo "<li>Dim cost per 0.5 KG: ". ($productInfo['DimWeightKg']/0.5) ."x25= " .( ($productInfo['DimWeightKg']/0.5) * 25)." <br>";
		echo "<li>Item Weight: {$productInfo['ItemWeight']}";
		echo "<li>Item Weight in KG: {$productInfo['ItemWeightKg']}";
		echo "<li>Item cost per 0.5 KG: ". ($productInfo['ItemWeightKg']/0.5) ."x25= " .( ($productInfo['ItemWeightKg']/0.5) * 25)." <br>";
		
		echo "<br><b>Suggested Shipping Cost:</b> Using {$productInfo['CalShipCost']} <br><br>";
		

		echo "<li>Color: {$productInfo['Color']}";

		echo "<li>ProductGroup: {$productInfo['ProductGroup']}";
		echo "<li>ProductTypeName: {$productInfo['ProductTypeName']}";
		echo "<br>";
		
		echo "<br>";
		echo "<li><B>ASIN :</B> {$productInfo['ASIN']}<br>";
		echo "<br><img src=\"" . $productInfo['Image_url']  . "\" /><br>";
		echo "<li>price \${$productInfo['Price']} and with offer {$productInfo['PriceOffer']} <br>";
		echo "<li>isPrime {$productInfo['IsPrime']} <br>";

		
	}		
	
	// ========================================================================================================= 
	// get parent object by ASIN code
	// =========================================================================================================
	function getItemParentByASIN($parent_ASIN)
	{
		global $obj;
		
		try
		{
			$parent		= $obj->getItemByAsinVariationMatrix($parent_ASIN);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		//print_r($parent);	
		return $parent;
	}		
	
	// ========================================================================================================= 
	// get the avialable options for an item, returning: 
	//	parent
	//		optionsCount	= varAttributeCount
	//		itemsCount		= totalItemsAvailable
	//		items[] = (
	//				asin
	//				image
	//				price
	//				attributes	
	//			)
	// ========================================================================================================= 
	function getItemVariation($parent)
	{
		//
		// get variation attribute
		$VariationDimension	= $parent->Items->Item->Variations->VariationDimensions->VariationDimension;
		$varAttributeCount	= count($VariationDimension);
				
		$parentResult['varAttributeCount'] = $varAttributeCount;

		//
		// check parent ASIN 
		if( $varAttributeCount != 0 )
		{		
			//echo "<br> we have $varAttributeCount different options <br>";
			foreach($VariationDimension as $attribute => $val)
			{
				echo "<br>attribute: ". $val;
				$attributes[]	=	$val;
			}
			
			// total items available:
			$totalItemsAvailable					= $parent->Items->Item->Variations->TotalVariations;
			$parentResult['totalItemsAvailable'] 	= $totalItemsAvailable;

			// 
			// different items have different ASIN 
			// display their ASIN and attributes 
			for($i=0; $i < $totalItemsAvailable; $i++)
			{
									
				$asinPrice 									= $parent->Items->Item->Variations->Item[$i]->ItemAttributes->ListPrice->Amount;
				$asinPrice									= number_format(($asinPrice/ 100), 2, '.', '');
			
				$parentResult['items'][$i]['ASIN'] 			= $parent->Items->Item->Variations->Item[$i]->ASIN;
				$parentResult['items'][$i]['Image_url'] 	= $parent->Items->Item->Variations->Item[$i]->ImageSets->ImageSet[0]->MediumImage->URL;
				$parentResult['items'][$i]['Price'] 		= $asinPrice;

				// 
				// get attributes:
				$VariationAttribute 						= $parent->Items->Item->Variations->Item[$i]->VariationAttributes; 
				
				// if more then one attribute, it is an array:
				if( count($varAttributeCount) > 0 ) 
				{
					// if array, iterate through them:
					foreach( $VariationAttribute->VariationAttribute as $attr)
					{
						$parentResult['items'][$i]["{$attr->Name}"] 	= 		$attr->Value;
					}
				}
				else
				{	
					// if only one attribute, just use it: 
					$attr_name											=		$parent->Items->Item->Variations->Item[$i]->VariationAttributes->VariationAttribute->Name;
					$attr_val											=	 	$parent->Items->Item->Variations->Item[$i]->VariationAttributes->VariationAttribute->Value;
					$parentResult['items'][$i]["{$attr->Name}"] 		= 		$attr->Value;
				}
			}
		}	
		return $parentResult;			
	}
		
		
?>