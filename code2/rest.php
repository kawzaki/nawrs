<?php

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		// show message
		die("Not allowed");
	}
	elseif ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		/* Example usage of the Amazon Product Advertising API */
		include("amazon_api_class.php");
		require_once 'amazon_keys.inc';
		
		$obj = new AmazonProductAPI($public_key, $private_key);
		
		try
		{
			$amzproduct	= $_POST["amzproduct"];
			$result = $obj->getItemByAsin($amzproduct);
		   // $result = $obj->searchProducts("X-Men Origins",
		   // AmazonProductAPI::DVD,
		   // "TITLE");
										   
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
		
		//print_r($result);
		
		// format the reply:
		$json_result = array(
				"title"		=> $result->Items->Item->ItemAttributes->Title								,
				"price"		=> $result->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice		, 
				"img_url"	=> $result->Items->Item->LargeImage->URL									, 
				"ASIN"		=> $result->Items->Item->ASIN
				);	  
		//var_dump($json_result);
		$json 	= json_encode($json_result, JSON_UNESCAPED_UNICODE+ JSON_UNESCAPED_SLASHES);
		var_dump($json);
	  
		// Get title, price and image URL from the response
		$title 		= $result->Items->Item->ItemAttributes->Title;
		$price 		= $result->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice;
		$image_url	= $result->Items->Item->LargeImage->URL;
		
		// Print the title, price, ASIN and image URL to screen
		echo "$asin Title: $title<br />$asin Price: $price<br />$asin Image URL: $image_url<br /><br />";
		
		echo "<br>";
		echo "Sales Rank : {$result->Items->Item->SalesRank}<br>";
		echo "ASIN : {$result->Items->Item->ASIN}<br>";
		echo "<br><img src=\"" . $result->Items->Item->MediumImage->URL . "\" /><br>";
		echo "<br>".$result->Items->Item->ItemAttributes->ListPrice->FormattedPrice . "<br>";
    }
	
?>