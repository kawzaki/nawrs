$(document).foundation();


$( document ).ready(function() {	
	// global vars
	// 
	var cart		= new Array();	
	cart.totalCost	= 0;
	
	var product 	= new Array();
	
	// observe url text input
	$("#prodcturl").on('change paste', function() {
		// getProductInfo();
		var prod_url	= $("#prodcturl").val()
		getProductInfo(prod_url, product);
	});

	// observe addToCartBtn.
	$("#addToCartBtn").on('click', function(event) {
		event.preventDefault();
		addToCart();
	});

	// observe table to remove item from cart
	function observeRemoveBtn(){	
		$("#cart .removeItem").on("click",function(event) {
			event.preventDefault();
			console.log("caught the delete click");
			var tr = $(this).closest('tr');
			tr.css("background-color","#FF3700");
			tr.fadeOut(400, function(){
				tr.remove();
			});
		});	
	}
	
	// get product info 
	function getProductInfo(prod_url, products){
		$.getJSON( "data/product"+prod_url+".json", { name: "John", time: "2pm" } )
		  .done(function( json ) {
			console.log(json);
			
			// why do i need this ?
			product["title"]	= json.title;
			product["price"]	= json.price;
			product["qty"]		= json.qty;
			product["weight"]	= json.weight;
			product["size"]		= json.size;
			product["ASIN"]		= json.ASIN;
			product["img_url"]	= json.img_url;
			product["height"]	= json.height;
			product["iLength"]	= json.itemLength;
			product["width"]	= json.width;
			
			// shipping rate 
			product["shippingRate"] = calShippingRate(product);
			console.log( "products array: " + products );
			
			// show fetched product info 
			$("#prod_title").text(json.title)
			$("#prod_price").text(json.price)
			$("#prod_qty").text(json.qty)
			$("#prod_weight").text(json.weight)
			$("#prod_size").text(json.itemLength + "x"+ json.height +"x"+ json.width)
			$("#prod_ASIN").text(json.ASIN)
			$("#prod_img_url").attr('src', json.img_url); 
			$("#prod_info").slideDown();
			
		  })
		  .fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			console.log( "Request Failed: " + err );
		});
	}
	
	// add product to cart
	function addToCart()
	{
		console.log( "products array: " + product );
		newProduct = "<tr>"
						+ "<td>"+ parseInt(cart.length +1)	+"</td>"
						+ "<td><img src=\""+ product["img_url"] + "\" class=\"thumbnail\" /> </td>"
						+ "<td>"+ product["weight"]			+"</td>"
						+ "<td>"+ product["size"]			+"</td>"
						+ "<td>"+ product["price"]			+"</td>"						
						+ "<td>"+ product["shippingRate"]	+" lb </td>"						
						+ "<td> <i class='fi-x removeItem button warninng'></i></td>"
						+ "</tr>";
						
		$('#cart > tbody:last-child').append(newProduct);
		$("#addToCartBtn").notify("success", {className:"success"});
		$("#itemsList").slideDown();
		observeRemoveBtn();
		updateInvoice(product);
	}
	

	// update UI for cart and invoice
	function updateInvoice(){
		// add product to cart
		cart.push(product);
		// parse price as decimal int
		cart.totalCost 	+= parseInt(product["price"], 10);
		console.log( cart.totalCost)
		// update invoice
		$("#itemsCount").html( cart.length);
		$("#itemsCost").html( cart.totalCost);
	}
	
	// calculation item shipping cost
	function calShippingRate(product)
	{
		// dimensional cost = (L * H * W ) / 139  (inch)
		// dimensional cost = (L * H * W ) / 5000 (cm)
		
		dimWeight = Math.ceil((product["iLength"] * product["height"] * product["width"]) / 139); 
		return dimWeight;	
	}
	
});
