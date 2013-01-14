$(document).ready(function(){
    /*
    $(document).on("click", ".addto-prints", function()
    { 
        var count = parseFloat($("#in-cart-number").html()) + 1;
        $("#in-cart-number").html(count);
        $(this).parent().parent().parent().addClass("photoInCart");
        $(this).removeClass("addto-prints").addClass("removefrom-prints").html("Remove from Prints");
        // store reference of photos id somewhere
        var photoCart = readCookie('phCart');
        var photoID = $(this).attr("href").substring(1);
        
        if ( photoCart != null )
        {
            var addID = photoCart + "," + photoID;
            createCookie('phCart', addID,'90');
        } else {
            createCookie('phCart', photoID,'90');   
        }
        //
        sendNotification("positive", "The photo was added to your cart.");
    });
    $(document).on("click", ".removefrom-prints", function()
    { 
        var count = parseFloat($("#in-cart-number").html()) - 1;
        if ( count < 0 )
        {
            count = 0;
        }
        $("#in-cart-number").html(count);
        $(this).parent().parent().parent().removeClass("photoInCart");
        $(this).removeClass("removefrom-prints").addClass("addto-prints").html("Add to Prints");
        // remove reference from cart
        var photoCart = readCookie('phCart');
        var photoArr = photoCart.split(",");
        var photoID = $(this).attr("href").substring(1);
        var inPhotoArr = photoArr.indexOf(photoID);
        if ( inPhotoArr >= 0 )
        {
            // remove from array
            photoArr.splice(inPhotoArr,1);
            var newIDstring = photoArr.toString();
            createCookie('phCart', newIDstring,'90');
        }
        //
        sendNotification("caution", "The photo was removed from your cart.");
    });
*/
    
    // UPGRADES MENU
    $("#upgradeChoices").click(function(e) 
    {          
        e.preventDefault();
        $("#checkoutMenu").hide();
        
        $("#upgradeChoicesMenu").toggle();
        //$("#event-nav-privacy").toggleClass("menu-open");
    });
    
    $(document).on("click", ".addUpgrade", function()
    {
        var upgrade_id = $(this).attr("rel");
        
        var upgradeCookie = readCookie('upgrades');
        
        if ( upgradeCookie != null )
        {
            newString = upgradeCookie + "," + upgrade_id;
            createCookie('upgrades', newString,'90');
        } else {
            createCookie('upgrades', upgrade_id,'90');
        }
        $(this).closest(".addUpgradeWrap").html("Added.");
        
        setTimeout( function() { bringBackAddonButton(upgrade_id) }, 1500)
    });

    /**** SHOW CHECKOUT BUTTON ****/
    $('#checkout').click( function(e)
    {
        e.preventDefault();
        $("#upgradeChoicesMenu").hide();
        
        var photos_in_cart = parseFloat($("#in-cart-number").html());
        if ( photos_in_cart == 0 )
        {
            alert("You haven't added any photos yet.");
        } else {
            $("#checkoutMenu .menuContents ul").html(" ");
            
            var upgradeCookie = readCookie('upgrades');
            
            var subtotal = 0;
            var shipping = 3;
            var total = 3;
            
            var instructions = "";
            
            // if there's upgrades add them
            if ( upgradeCookie != null )
            {
                // check if there's more than one upgrade in teh cookie
                if (upgradeCookie.indexOf(",") >= 0)
                {
                    // more than one upgrade exists
                    var upgrades = upgradeCookie.split(",");
                    
                    var remains = 0;
                    var extras = 0;
                    var print_count = 0;
                    var total_prints = 0;
                    var shipping = "FREE";
                    var instructions = "";
                    var price = 0;
                    var thisID = 0;
                    
                    // add upgrades
                    $.Mustache.load('/assets/js/templates.html').done(function () 
                    {
                        $.each(upgrades, function(key, value) 
                        {
                            if ( value == 2 )
                            {
                                price = 11;
                                print_count = 12;
                            }
                            else if ( value == 3 )
                            {
                                price = 19;
                                print_count = 24;
                            }
                            else if ( value == 4 )
                            {
                                price = 27;
                                print_count = 36;
                            }
                        
                            var viewData = {
                                id: thisID,
                                num: 1, 
                                print_count: print_count,
                                instructions: "",
                                price: price,
                                type: 'upgrade'
                            };
                            $("#checkoutMenu .menuContents ul").mustache('checkout-review-upgrade', viewData);
                            
                            total_prints = total_prints + print_count;
                            remains = total_prints - photos_in_cart;
                            subtotal = subtotal + price;
                            thisID++;
                            
                            $("#checkoutReviewSubTotalNum").html("$" + subtotal);
                            // add shipping
                            if ( shipping != "FREE" )
                            {
                                shipping = "$" + shipping;
                            }
                            $("#checkoutReviewShippingNum").html(shipping);
                            // add total
                            $("#checkoutReviewTotalNum").html("$" + subtotal);
                        });
                    // if there's less prints than the upgrades allow display message with # of prints left
                    if  (remains > 0)
                    {
                        $("#checkoutReviewInstructions").html("You're selected upgrades allow for " + total_prints + " photos, you've  only chosen " + photos_in_cart + ".").show();
                    } else {
                        $("#checkoutReviewInstructions").hide();
                    }
                        
                    });
                    // if there's more prints than the upgrades add them as another line item
                } else {
                
                    // just one upgrade here
                    
                    var remains = 0;
                    var extras = 0;
                    var print_count = 0;
                    
                    // get subtotal
                    if ( upgradeCookie == 2 )
                    {
                        subtotal = 11;
                        print_count = 12;
                    }
                    else if ( upgradeCookie == 3 )
                    {
                        subtotal = 19;
                        print_count = 24;
                    }
                    else if ( upgradeCookie == 4 )
                    {
                        subtotal = 27;
                        print_count = 36;
                    }
                    
                    // if there's less prints than the upgrades allow display message with # of prints left
                    if ( upgradeCookie == 2 && photos_in_cart < 12 )
                    {
                        remains = 12 - photos_in_cart;
                        print_count = 12;
                    }
                    else if ( upgradeCookie == 3 && photos_in_cart < 24 )
                    {
                        remains = 24 - photos_in_cart;
                        print_count = 24;
                    }
                    else if ( upgradeCookie == 4 && photos_in_cart < 36 )
                    {
                        remains = 36 - photos_in_cart;
                        print_count = 36;
                    }
                    else if ( upgradeCookie == 2 && photos_in_cart > 12 )
                    {
                        extras = photos_in_cart - 12;
                    }
                    else if ( upgradeCookie == 3 && photos_in_cart > 24 )
                    {
                        extras = photos_in_cart - 24;
                    }
                    else if ( upgradeCookie == 4 && photos_in_cart > 36 )
                    {
                        extras = photos_in_cart - 36;
                    }
                    
                    if  (remains > 0)
                    {
                        $("#checkoutReviewInstructions").html("You're selected upgrade allow for " + print_count + " photos, you've  only chosen " + photos_in_cart + ".");
                    }
                    
                    // add upgrade
                    $.Mustache.load('/assets/js/templates.html').done(function () 
                    {
                        var viewData = {
                            id: 0,
                            num: 1, 
                            print_count: print_count,
                            instructions: "",
                            price: subtotal,
                            type: 'upgrade'
                        };
                        $("#checkoutMenu .menuContents ul").mustache('checkout-review-upgrade', viewData);
                        
                        if (extras > 0)
                        {
                            var viewData = {
                                num: extras, 
                                type: 'singles'
                            };
                            $("#checkoutMenu .menuContents ul").mustache('checkout-review-singles', viewData);
                        }
                    });
                    
                    // add subtotal
                    $("#checkoutReviewSubTotalNum").html("$" + subtotal);
                    // add shipping
                    if ( shipping != "FREE" )
                    {
                        shipping = "$" + shipping;
                    }
                    $("#checkoutReviewShippingNum").html(shipping);
                    // add total
                    $("#checkoutReviewTotalNum").html("$" + total);
                }
                
                shipping = "FREE";
                total = subtotal;
                $("#checkoutReviewShippingNum").addClass("freeShipping");
                
            } else { // if there's no upgrades display cost for individual prints
                //
                $.Mustache.load('/assets/js/templates.html').done(function () 
                {
                    var viewData = {
                        num: photos_in_cart, 
                        type: 'singles'
                    };
                    $("#checkoutMenu .menuContents ul").mustache('checkout-review-singles', viewData);
                });
                subtotal = photos_in_cart;
                total = photos_in_cart + shipping;
                
                // add subtotal
                $("#checkoutReviewSubTotalNum").html("$" + subtotal);
                // add shipping
                if ( shipping != "FREE" )
                {
                    shipping = "$" + shipping;
                }
                $("#checkoutReviewShippingNum").html(shipping);
                // add total
                $("#checkoutReviewTotalNum").html("$" + total);
            }
            $("#checkoutMenu").toggle();
        }
    });
    
    $("#checkoutMenu").on("click", ".checkoutRemove", function(e) 
    {
        var deets = $(this).attr("rel").split("|");
        var thisID = deets[0];
        var thisPrice = deets[1];
        
        // HIDE & REMOVE FROM CHECKOUT MENU
        
        $("#addon-" + thisID).fadeOut("fast");
        
        // update subtotal and total
        
        var subtotal = parseFloat($("#checkoutReviewSubTotalNum").html().replace("$", ""));
        var new_subtotal = subtotal - thisPrice;
        
        $("#checkoutReviewSubTotalNum").html("$" + new_subtotal);
        $("#checkoutReviewTotalNum").html("$" + new_subtotal);
        
        // REMOVE FROM UPGRADES COOKIE
        
        var upgradeCookie = readCookie('upgrades');
        var upgradeArr = upgradeCookie.split(",");
        upgradeArr.splice(thisID,1);
        createCookie('upgrades', upgradeArr,'90');
        
        if ( readCookie('upgrades') == "" )
        {
            createCookie('upgrades', "",'-90');
            $("#checkoutReviewSubTotalNum").html("$0");
            $("#checkoutReviewTotalNum").html("$0");
        }
    });
    
    $("#checkoutMenu").on("click", "#checkoutReviewContinue", function(e) 
    {
        var photosInCart = readCookie('phCart');
        var upgrades = readCookie('upgrades');
        
        if ( photosInCart.length > 0 && upgrades.length > 0 )
        {
            window.location = "/checkout/shipping";
        } else {
            alert("You don't seem to have added anything to your order.");
        }
    });
});