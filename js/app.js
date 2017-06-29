$(document).foundation();

var mycart;
$( document ).ready(function() {	
	// global vars
	// 
	var cart			= new Array();	
	cart.itemsCost		= 0;
	cart.shippingCost	= 0;
	cart.invoiceCost	= 0;
	
	// this will hold the json of item
	var product 	= {}; 
	
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
		$("#items .removeItem").on("click",function(event) {
			event.preventDefault();
			console.log("caught the delete click : "+ event.target.id);

						
			// update invoice
			removeFromInvoice( event.target.id );
			
			var tr = $(this).closest('tr');
			tr.css("background-color","#FF3700");
			tr.fadeOut(400, function(){
				tr.remove();
			});			
		});	
	}
	
	// get product info 
	function getProductInfo(prod_url, products){
		prod_url = $.trim(prod_url);
		console.log("prod_url: " + prod_url);
		if( prod_url.length < 3) {console.log("empty asin");return false;}
		$.getJSON( "data/"+ prod_url +".json", { name: "John", time: "2pm" } )
		  .done(function( json ) {
			console.log(json);
			
			product 				= json;
			
			// convert USD to SAR
			PriceSAR				= (parseFloat(json.Price,10) * 3.75).toFixed(1);
			json.PriceSAR 			= PriceSAR;
			
			// show fetched product info 
			$(	"#prod_title"		).text(json.Title)
			$(	"#prod_price"		).text(json.Price +"\nSAR"+ PriceSAR)
			$(	"#prod_weight"		).text(json.ItemWeight)
			$(	"#prod_dimensions"	).text(json.ItemLength + "x"+ json.ItemHeight +"x"+ json.ItemWidth)
			$(	"#prod_ASIN"		).text(json.ASIN)
			$(	"#prod_color"		).text(json.Color)
			$(	"#prod_size"		).text(json.Size)
			$(	"#prod_shipping"	).text(json.DimCostPerKG)
			$(	"#prod_img_url"		).attr('src', json.Image_url); 
			$(	"#prod_info"		).slideDown();
			
		  })
		  .fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			console.log( "Request Failed: " + err );
		});
	}
	
	// add product to cart
	function addToCart()
	{
		console.log( "products json: " + product );
		product.id 		= product.ASIN;
		newProduct = "<tr>"
						+ "<td><img src=\""+ product.Image_url + "\" class=\"thumbnail\" /> </td>"
						+ "<td>"+ product.Color			+"</td>"
						+ "<td>"+ product.Size			+"</td>"
						+ "<td>"+ product.PriceSAR		+" SAR</td>"						
						+ "<td>"+ product.DimCostPerKG	+" SAR </td>"						
						+ "<td> <i class='fi-x removeItem button warninng' id='"+ product.ASIN +"'></i></td>"
						+ "</tr>";
						
		$('#items > tbody:last-child').append(newProduct);
		$("#addToCartBtn").notify("success", {className:"success"});
		$("#itemsList").slideDown();
		observeRemoveBtn();
		addToInvoice(product);
	}
	

	// update UI for cart and invoice
	function addToInvoice(){
		// add product to cart
		cart.push(product);
		
		// items total cost
		cart.itemsCost 	+= parseFloat(product.PriceSAR, 10);
		cart.itemsCost	= parseFloat(cart.itemsCost.toFixed(4)) ;
		console.log( cart.itemsCost)
		
		// items total shipping cost
		cart.shippingCost 	+= parseFloat(product.DimCostPerKG, 10);
		cart.shippingCost	= parseFloat(cart.shippingCost.toFixed(4)) ;
		console.log( cart.totalCost)
		
		cart.invoiceCost	= cart.itemsCost + cart.shippingCost;
		cart.invoiceCost	= parseFloat(cart.invoiceCost.toFixed(4));
		
		// update invoice
		$("#itemsCount").html( cart.length);
		$("#itemsCost").html( cart.itemsCost);
		$("#shippingCost").html( cart.shippingCost);
		$("#invoiceCost").html( cart.invoiceCost);
		
		mycart = cart;
	}
	
		
	// update UI for cart and invoice
	function removeFromInvoice( ASIN ){
			
		// find item from array
		// this may not work in old browsers
		item	=	cart.find(x => x.ASIN === ASIN);
		console.log("removing: " + ASIN  + " its price is: "+ item.Price);
		
		// subtract price from total (decimal float)
		console.log( "totalCost = " + cart.totalCost +" -" + parseFloat(item.Price, 10));
		cart.totalCost 	-= parseFloat(item.Price, 10);
		cart.totalCost	= parseFloat(cart.totalCost.toFixed(4));
		console.log( "new totalCost: " + cart.totalCost)
		
		// remove product from cart
		mycart.removeItem("ASIN", ASIN)
		
		// update invoice
		$("#itemsCount").html( cart.length);
		$("#itemsCost").html( cart.totalCost);
	}


	// remove objects from array using search for value:
	Array.prototype.removeItem = function (key, value) {
		if (value == undefined)
			return false;
		
		for (var i in this) {
			if (this[i][key] == value) {
				this.splice(i, 1);
			}
		}
		return true;
	};	
});
