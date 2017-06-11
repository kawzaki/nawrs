$(document).foundation();


$( document ).ready(function() {	
	// global vars
	var products = new Array();

	// observe url text input
	$("#prodcturl").on('change paste', function() {
		// getProductInfo();
		var prod_url	= $("#prodcturl").val()
		getProductInfo(prod_url, products);
	});

	// observe addToCartBtn.
	$("#addToCartBtn").on('click', function(event) {
		console.log("firefox works");
		event.preventDefault();
		addToCart();
	});

	// get product info 
	function getProductInfo(prod_url, products){
		$.getJSON( "data/product.json", { name: "John", time: "2pm" } )
		  .done(function( json ) {
			console.log(json);
			products["title"]	= json.title;
			products["price"]	= json.price;
			products["qty"]		= json.qty;
			products["weight"]	= json.weight;
			products["size"]	= json.size;
			products["ASIN"]	= json.ASIN;
			products["img_url"]	= json.img_url;
			console.log( "products array: " + products );
			$("#prod_title").text(json.title)
			$("#prod_price").text(json.price)
			$("#prod_qty").text(json.qty)
			$("#prod_weight").text(json.weight)
			$("#prod_size").text(json.size)
			$("#prod_ASIN").text(json.ASIN)
			$("#prod_img_url").attr('src', json.img_url); 
			$("#prod_info").show();
		  })
		  .fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			console.log( "Request Failed: " + err );
		});
	}
	
	// add product to cart
	function addToCart()
	{
		console.log( "products array: " + products );
		newProduct = "<tr>"
						+ "<td><img src=\""+ products["img_url"] + "\" class=\"thumbnail\" /> </td>"
						+ "<td>"+ products["title"]		+"</td>"
						+ "<td>"+ products["qty"]		+"</td>"
						+ "<td>"+ products["weight"]	+"</td>"
						+ "<td>"+ products["size"]		+"</td>"
						+ "<td>"+ products["price"]		+"</td>"						
						+ "</tr>";
						
		$('#cart > tbody:last-child').append(newProduct);
		$("#addToCartBtn").notify("success");
	}
	
});
