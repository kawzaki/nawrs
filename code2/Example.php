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
		include("amazon_api_class.php");

		$obj = new AmazonProductAPI();
		
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
		
		print_r($result);
		
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