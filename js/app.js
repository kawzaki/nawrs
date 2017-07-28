$(document).foundation();

var mycart;
$( document ).ready(function() {	

	// homepage slider
	if( $("#owl-homepage") )
	{
		$("#owl-homepage").owlCarousel({
		  navigation : false, // Show next and prev buttons
		  slideSpeed : 300,
		  paginationSpeed : 400,
		  singleItem:true,
		  transitionStyle : "backSlide",
		  autoPlay: true
		  // itemsTablet: false,
		  // itemsMobile : false 
		});
	}


	// global vars
	// 
	var cart			= new Array();	
	cart.itemsCost		= 0;
	cart.shippingCost	= 0;
	cart.invoiceCost	= 0;
	cart.itemsTax		= 0;

	// get asin from URL
	function getAsinFromUrl(url)
	{
		var regex = RegExp("www.amazon.com/([\\w-]+/)?(dp|gp/product)/(\\w+/)?(\\w{10})");
		m = url.match(regex);
		if (m) { 
			return m[4];
		}		
		return null;
	}
	
	// observe url text input
	$("#prodcturl").on('change paste', function() {
		// getProductInfo();
		var prod_url	= $("#prodcturl").val()
		product 		= getProductInfo(prod_url);
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
			// console.log("caught the delete click : "+ event.target.id);

			// update invoice
			removeFromInvoice( event.target.id );
			
			// save cart
			saveCart("remove", event.target.id);
						
			
			var tr = $(this).closest('tr');
			tr.css("background-color","#FF3700");
			tr.fadeOut(400, function(){
				tr.remove();
			});			
		});	
	}
	
	// get product info 
	function getProductInfo(prod_url){
		
		
		// parse the url for ASIN code
		prod_url = $.trim(prod_url);
		if(prod_url.length > 10 ) prod_url = getAsinFromUrl(prod_url);
		// console.log("prod_url: " + prod_url);
		if( prod_url.length < 3) {return false;}
		
		// get the data from server
		$.getJSON( "data/"+ prod_url +".json", { name: "amazon", time: Date.now() } )
		  .done(function( json ) {
			// console.log(json);
			
			// store result in product object
			product 				= json;
			
			// convert USD to SAR
			PriceSAR				= (parseFloat(json.Price,10) * 3.75).toFixed(1);
			json.PriceSAR 			= PriceSAR;
			json.TaxSAR				= (parseFloat(json.Tax,10) * 3.75).toFixed(1);
			
			// show fetched product info 
			$(	"#prod_title"		).text(json.Title)
			$(	"#prod_price"		).text(json.Price)
			$(	"#prod_price_sar"	).text(PriceSAR)
			$(	"#prod_weight"		).text(json.PkgDimWeight )
			$(	"#prod_dimensions"	).html(json.Length + "<b>x</b>"+ json.Height +"<b>x</b>"+ json.Width)
			$(	"#prod_ASIN"		).text(json.ASIN)
			$(	"#prod_color"		).text(json.Color)
			$(	"#prod_size"		).text(json.Size)
			$(	"#prod_shipping"	).text(json.ShippingCost)
			$(	"#prod_img_url"		).attr('src', json.Image_url); 
			$(	"#prod_info"		).slideDown();
			// reset url input
			$("#prodcturl").val("");
			$("#addToCartBtn").prop('disabled', false);
			return product;
			
		  })
		  .fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			// console.log( "Request Failed: " + err );
		});
	}
	
	// add product to cart
	function addToCart()
	{
		// avoid adding to cart before the item info is fetched
		// if( product.ASIN === undefined) return ;
		if( product === undefined) return ;
		
		var newItem = Object.assign({}, product); 
		newItem.ID 		= Date.now();
		
		// console.log( "product id: " + newItem.ID );
		newProduct = "<tr>"
						+ "<td><img src=\""+ newItem.Image_url + "\" class=\"thumbnail\" /> </td>"
						+ "<td>"+ newItem.Color			+"</td>"
						+ "<td>"+ newItem.Size			+"</td>"
						+ "<td>"+ newItem.PriceSAR		+" ريال</td>"						
						+ "<td>"+ newItem.TaxSAR		+" ريال</td>"						
						+ "<td>"+ newItem.ShippingCost	+" ريال </td>"						
						+ "<td> <i class='fi-x removeItem button alert' id='"+ newItem.ID +"'></i></td>"
						+ "</tr>";
						
		$('#items > tbody:last-child').append(newProduct);
		$("#addToCartBtn").notify("تم إضافته لسلة المشتريات", {className:"success"});
		$("#itemsList").slideDown();
		
		// save cart
		saveCart("add", newItem.ID);
		
		// start observer for remove from cart button
		observeRemoveBtn();
		
		// add to invoice
		addToInvoice(newItem);
	}
	
	
	// store in cart table
	function saveCart(command, ASIN)
	{
		if (typeof $.cookie('mycart') === 'undefined')
		{
		 //no cookie
		 // create session id for the shopping cart
		 cartid 	= "cust-" + Date.now();
		 $.cookie('mycart', cartid, { expires: 7 });
		} 
		else
			cartid = $.cookie("mycart");

		// send to server
		$.post( "data/cart.php", { mycart: cartid, asin: ASIN, cmd: command } )
				  .done(function( data ) {
					console.log(data);
				  })
				  .fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					console.log( "failed saving cart: " + err );
				});		
		
	}

	// update UI for cart and invoice
	function addToInvoice( newItem ){
		
		// console.log( "adding newItem: " + newItem.ID);
		// add product to cart
		cart.push(newItem);
		
		// items total cost
		cart.itemsCost 	+= parseFloat(newItem.PriceSAR, 10);
		cart.itemsCost	= parseFloat(cart.itemsCost.toFixed(4)) ;
		
		cart.itemsTax	+= parseFloat(newItem.TaxSAR, 10) ;
		cart.itemsTax	= parseFloat(cart.itemsTax.toFixed(4)) ;
		
		// items total shipping cost
		cart.shippingCost 	+= parseFloat(newItem.ShippingCost, 10);
		cart.shippingCost	= parseFloat(cart.shippingCost.toFixed(4)) ;
		// console.log( cart.totalCost)
		
		// item tax
		cart.invoiceCost	= cart.itemsCost + cart.shippingCost + cart.itemsTax;
		cart.invoiceCost	= parseFloat(cart.invoiceCost.toFixed(4));
		
		// update invoice
		updateInvoice();
		
		mycart = cart;
	}
	
		
	// update UI for cart and invoice
	function removeFromInvoice( id )
	{

			var item = {};
			// console.log("finding :  " + id );
			// find item from array. this may not work in old browsers
			item				=	cart.find(x => x.ID == id);
			
			if( item === undefined) return;
			
			// subtract price from total (decimal float)
			cart.totalCost 		-= parseFloat(item.PriceSAR, 10);
			cart.totalCost		= parseFloat(cart.totalCost.toFixed(4));
			
			// items total cost
			cart.itemsCost 		-= parseFloat(item.PriceSAR, 10);
			cart.itemsCost		= parseFloat(cart.itemsCost.toFixed(4)) ;
			
			// items total shipping cost
			cart.shippingCost 	-= parseFloat(item.ShippingCost, 10);
			cart.shippingCost	= parseFloat(cart.shippingCost.toFixed(4)) ;
			
			// item tax
			cart.itemsTax 		-= parseFloat(item.TaxSAR, 10);
			cart.itemsTax		= parseFloat(cart.itemsTax.toFixed(4)) ;
			
			cart.invoiceCost	= cart.itemsCost + cart.shippingCost;
			cart.invoiceCost	= parseFloat(cart.invoiceCost.toFixed(4));
			
			
			// remove product from cart
			cart.removeItem("ID", id)
			
			// update invoice
			updateInvoice();
		
	}

	// 
	// update invoice
	function updateInvoice()
	{
		$("#itemsCount").html( cart.length);
		$("#itemsCost").html( cart.itemsCost);
		$("#shippingCost").html( cart.shippingCost);
		$("#invoiceTax").html( cart.itemsTax);
		$("#invoiceCost").html( cart.invoiceCost + " ريال");				
	}

	// remove objects from array using search for value:
	Array.prototype.removeItem = function (key, value) {
		if (value == undefined)
			return false;
		
		for (var i in this) {
			if (this[i][key] == value) {
				this.splice(i, 1);
				return true;
			}
		}
		return false;
	};	
});
