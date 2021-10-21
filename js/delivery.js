var $ = jQuery;

        getCartCount(); //aggiunge il numero di elementi nel carrello al menù "regala un'esperienza"
        // login
        $(document).on("click", ".delivery-login", function (event) {
            if (getFormValidity(this)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                $.post(delivery.ajax, {
                    email: $("#email-login").val(),
                    password: $("#password-login").val(),
                    deliverySession: Cookies.get("delivery-session"),
                    action: "login",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                    }
                }, function (res) {
                    if ($("#voucher-api-token").length === 1) {
                        var obj = JSON.parse(res);
                        if (obj.error) {
                      //    console.log($(this).val()+' '+ $(this).attr("delivery-qty")+' '+qty);
    $("#delivery-login-error").html(obj.error);
                        } else {
                            //      console.dir(obj);
                            $("#voucher-first_name").val(obj.first_name);
                            $("#voucher-last_name").val(obj.last_name);
                            $("#voucher-email").val(obj.email);
                            $("#voucher-address").val(obj.address);
                            $("#voucher-city").val(obj.city);
                            $("#voucher-country").val(obj.country);
                            $("#voucher-post_code").val(obj.post_code);
                            $("#voucher-phone_number").val(obj.phone_number);
                            $("#voucher-api-token").val(obj.api_token);
                            Cookies.set("delivery-token", obj.api_token, {
                                expires: 36000 // il numero di giorni in cui il cookie sarà efficace
                            });
                            $("#delivery-login-error").html("");
                            $("#delivery-user-ok").html(delivery.login_ok_status);
                            $("#delivery-login-forms").slideUp();
                            $("#delivery-voucher-form").slideDown();
                            $("#delivery-logout").show();
                        }
                    }
                    $("#loader").addClass("hidden");
                });
            }

        });
        // recupera password
        $(document).on("click", ".delivery-recover-password", function (event) {
            if (getFormValidity(this)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                $.post(delivery.ajax, {
                    email: $("#email-login").val(),
                    deliverySession: Cookies.get("delivery-session"),
                    action: "recover_password",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                    }
                }, function (res) {
                        var obj = JSON.parse(res);
                        if (obj.message === null) {
                            $("#delivery-recover-ok").html(delivery.recover_ok_status);
                            $("#delivery-recover-error").html("");
                        } else {
                            $("#delivery-recover-ok").html("");
                            $("#delivery-recover-error").html(obj.message);
                        }
                    $("#loader").addClass("hidden");
                });
            }

        });
        // recupera password
        $(document).on("click", ".delivery-change-password", function (event) {
            if (getFormValidity(this)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                $.post(delivery.ajax, {
                    email: $("#email").val(),
                    password: $("#password").val(),
                    password_confirmation: $("#password_confirmation").val(),
                    token: $("#token").val(),
                    action: "change_password",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                    }
                }, function (res) {
                        var obj = JSON.parse(res);
                        var error;
                        console.dir(obj);
                        if (obj.errors) {
                            error = obj.message + "<br/>";
                            $.each(obj.errors, function (i, val) {
                                $.each(val, function (i2, val2) {
                                    error += val2 + "<br/>";
                                });
                            });
                            $("#delivery-change-ok").html("");
                            $("#delivery-change-error").html(error);
                        } else {
                            $("#delivery-change-error").html("");
                            $("#delivery-change-ok").html(obj.message);
                        }
                    $("#loader").addClass("hidden");
                });
            }

        });
        // logout
        $(document).on("click", "#delivery-logout", function(event){

            event.preventDefault();
            event.stopImmediatePropagation();
            Cookies.set("delivery-token", "", {
                expires: 1 // il numero di giorni in cui il cookie sarà efficace
            });
            getCartCount();
            $("#delivery-cart").html("");
            getCart();
            $("#delivery-login-forms").slideDown();
            $("#delivery-voucher-form").slideUp();
            $("#delivery-logout").hide();
        });
        // register
        $(document).on("click", ".delivery-register", function (event) {

            if (getFormValidity(this)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                $.post(delivery.ajax, {
                    first_name: $("#first_name").val(),
                    last_name: $("#last_name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    address: $("#address").val(),
                    city: $("#city").val(),
                    country: $("#country").val(),
                    post_code: $("#post_code").val(),
                    phone_number: $("#phone_number").val(),
                    deliverySession: Cookies.get("delivery-session"),
                    action: "register",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                        // console.log("removeHidden delivery-register");
                    }
                }, function (res) {
                    if (res === "0") {
                        $("#delivery-register-error").html(delivery.error_status);
                    } else {
                        if ($("#voucher-api-token").length === 1) {
                            $("#voucher-first_name").val($("#first_name").val());
                            $("#voucher-last_name").val($("#last_name").val());
                            $("#voucher-email").val($("#email").val());
                            $("#voucher-address").val($("#address").val());
                            $("#voucher-city").val($("#city").val());
                            $("#voucher-country").val($("#country").val());
                            $("#voucher-post_code").val($("#post_code").val());
                            $("#voucher-phone_number").val($("#phone_number").val());
                            $("#voucher-api-token").val(res);

                            Cookies.set("delivery-token", res, {
                                expires: 36000 // il numero di giorni in cui il cookie sarà efficace
                            });
                            $("#delivery-register-error").html("");
                            $("#delivery-login-forms").hide();
                            $("#delivery-voucher-form").show();
                            $("#delivery-logout").show();
                            $("#delivery-user-ok").html(delivery.register_ok_status);
                        }
                    }
                    $("#loader").addClass("hidden");
                    // console.log("addHidden delivery-register");
                });
            }
        });
        //checkout
        $(document).on("click", ".delivery-checkout", function (event) {
            if (getFormValidity(this)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                $.post(delivery.ajax, {
                    first_name: $("#voucher-first_name").val(),
                    last_name: $("#voucher-last_name").val(),
                    email: $("#voucher-email").val(),
                    address: $("#voucher-address").val(),
                    city: $("#voucher-city").val(),
                    country: $("#voucher-country").val(),
                    post_code: $("#voucher-post_code").val(),
                    phone_number: $("#voucher-phone_number").val(),
                    notes: $("#voucher-notes").val(),
                    api_token: $("#voucher-api-token").val(),
                    voucher_name: $("#voucher-dest-name").val(),
                    voucher_email: $("#voucher-dest-email").val(),
                    deliverySession: Cookies.get("delivery-session"),
                    action: "checkout",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                        // console.log("removeClass delivery-checkout");
                    }
                }, function (res) {
                    $("#loader").addClass("hidden");
                    // console.log("addClass delivery-checkout");
                    var obj = JSON.parse(res);
                    if (obj.redirect){
                        Cookies.set("delivery-session", '', {
                            expires: 1 // il numero di giorni in cui il cookie sarà efficace
                        });
                        $("#delivery-checkout-error").html("");
                        $("#delivery-checkout-ok").html(delivery.paypal_status);
                        window.location.href = obj.redirect;
                    }else{
                   //     console.dir(obj);
                        $("#delivery-checkout-error").html(delivery.error_status);
                        $("#delivery-checkout-ok").html("");
                    }
                });
            }
        });

        // add/cart
        $(document).on("click change paste", ".delivery-add-to-cart", function (event) {
            event.stopImmediatePropagation();

            var qty;
            if ($(this).is("input")) {
                qty = parseInt($(this).val()) - parseInt($(this).attr("delivery-qty"));
                  console.log($(this).val()+' '+ $(this).attr("delivery-qty")+' '+qty);
            } else {
                qty = parseInt($(this).attr("delivery-qty"));
            }
            var attributePrice = 0;
            var attributeValue;
            var attributeId;
            var attributeTheId;
            if ($(this).attr("delivery-attributeValue")!=='') {
                attributeValue = $(this).attr("delivery-attributeValue");
            }
            if ($(this).attr("delivery-attributePrice")){
                attributePrice = $(this).attr("delivery-attributePrice");
            }
            if ($(this).attr("delivery-attributeId")){
                attributeId = $(this).attr("delivery-attributeId");
            }
            if ($(this).attr("delivery-attributeTheId")){
                attributeTheId = $(this).attr("delivery-attributeTheId");
            }
            if (qty !== 0) {
                //aggiungi al carrello
                $.post(delivery.ajax, {
                    price: $(this).attr("delivery-price"),
                    attributePrice: attributePrice,
                    productId: $(this).attr("delivery-productId"),
                    qty: qty,
                    calice: attributeValue,
                    attributeId: attributeId,
                    attributeTheId: attributeTheId,
                    deliverySession: Cookies.get("delivery-session"),
                    action: "add_to_cart",
                    beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $("#loader").removeClass("hidden");
                         //console.log("removeClass delivery-add-to-cart");
                    }
                }, function (res) {
                    
                    Cookies.set("delivery-session", res, {expires: 36000 });
                    getCartCount();
                    
                    if ($("#delivery-cart").length === 1) {
                        $("#delivery-cart").html("");
                        getCart();
                    }

                    
                    $("#loader").addClass("hidden");
                    $("html, body").animate({ scrollTop: "0" });

                });
            }
        });

        //elimina dal carrello
        $(document).on("click", ".delivery-remove-from-cart", function (event) {
            var rowId;
            if($(this).attr('delivery-attributeId') !== ''){
                rowId = $(this).attr("delivery-attributeId")
            } else {
                rowId = $(this).attr("delivery-productId")
            }
            event.stopImmediatePropagation();
            $.post(delivery.ajax, {
                beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $("#loader").removeClass("hidden");
                    //console.log("removeClass delivery-remove-to-cart");
                },
                productId: rowId,
                deliverySession: Cookies.get("delivery-session"),
                action: "remove_from_cart"
            }, function (res) {
                console.log(Cookies.get("delivery-session"));
                console.dir(JSON.parse(res));
              /*  Cookies.set("delivery-session", res, {
                    expires: 36000 // il numero di giorni in cui il cookie sarà efficace
                });*/
                getCartCount();
                if ($("#delivery-cart").length === 1) {
                    $("#delivery-cart").html("");
                    getCart();
                }
                $("#loader").addClass("hidden");
                // console.log("addClass delivery-remove-to-cart");
            }).fail(function (response){
                console.log("errore get carrt"+response);
            });
        });
        if ($("#delivery-cart").length === 1) {
            getCart();
        }
        if ($("#delivery-order-completed").length === 1) {
            getOrderById();
        }

        function getOrderById() {

            $.post(delivery.ajax, {
                orderId: $("#delivery-order-completed").attr("delivery-order-completed"),
                deliverySession: Cookies.get("delivery-session"),
                beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $("#loader").removeClass("hidden");
                    // console.log("removeClass delivery-order-completed");
                },
                action: "get_order"
            }, function (res) {
                var obj = JSON.parse(res);

                $("#delivery-order-" + obj.status).show();
                $.each(obj, function (i, val) {
                    if ($("#delivery-order-" + i + "-value").length === 1) {
                        $("#delivery-order-" + i + "-value").html(val);
                    }
                });

                // console.log("addClass delivery-order-completed");
                $("#loader").addClass("hidden");
            });
        }


$(function() {

        getCartCount(); //aggiunge il numero di elementi nel carrello al menù "regala un'esperienza"
        isLogged(); //controlla se il cliente è loggato

});

function currencyFormat(num) {
    return num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,").replace(".", ",") + " €";
}

function getFormValidity(submit_button) {
    var form_elements = $(submit_button).parents("form")[0];
    var ret = true;
    $.each(form_elements, function (i, form_element) {

        if (!form_element.checkValidity() && $(form_element).is("input")) {
            ret = false;
            return false;
        }
    });
    return ret;

}

function isLogged() {
    if (Cookies.get("delivery-token")) {
        $.post(delivery.ajax, {
            beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $("#loader").removeClass("hidden");
                // console.log("removeClass delivery-isLogged");
            },
            apiToken: Cookies.get("delivery-token"),
            action: "is_logged"
        }, function (res) {
            if ($("#delivery-login-forms").length === 1) {
                $("#delivery-login-forms").hide();
                $("#delivery-voucher-form").show();
            }
            if ($("#voucher-api-token").length === 1) {
                var obj = JSON.parse(res);
                //      console.dir(obj);
                $("#voucher-first_name").val(obj.first_name);
                $("#voucher-last_name").val(obj.last_name);
                $("#voucher-email").val(obj.email);
                $("#voucher-address").val(obj.address);
                $("#voucher-city").val(obj.city);
                $("#voucher-country").val(obj.country);
                $("#voucher-post_code").val(obj.post_code);
                $("#voucher-phone_number").val(obj.phone_number);
                $("#voucher-api-token").val(obj.api_token);
            }


             $("#loader").addClass("hidden");
            // console.log("addClass delivery-isLogged");
        });
    } else {
        $("#delivery-logout").hide();
        if ($("#delivery-login-forms").length === 1) {
            $("#delivery-login-forms").show();
            $("#delivery-voucher-form").hide();
        }
    }
}

function getCart() {
    $.post(delivery.ajax, {
        beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
            $("#loader").removeClass("hidden");
            // console.log("removeClass delivery-getCart");
        },
        deliverySession: Cookies.get("delivery-session"),
        action: "getCart"
    }, function (res) {
        
        obj = JSON.parse(res);
        var subtotal = 0;
        var rowTotal = 0;
        var rowProduct = '';
        var html;

        if ($("#row-head").length === 0) {
            html =
                "    <div class='grid'>" +
                "        <div class='row head' id='row-head'>" +
                "            <div class='col-6'>" + delivery.gift_label + "</div>" +
                "            <div class='col-3'>" + delivery.persons_label + "</div>" +
                "            <div class='col-3 alignRight'>" + delivery.total_label + "</div>" +
                "        </div>" +
                "    </div>";
            $("#delivery-cart").append(html);
        }

        $.each(obj, function (i, val) {
            console.dir(val);
            var attributeValue = '';
            var attributeId = '';
            var attributeTheId ='';
            if ($("#row-" + val.id).length === 0) {
                rowTotal = val.quantity * val.price;
                rowProduct = val.name;
                if (val.attributes.attributeId !== undefined) {
                    attributeValue = val.attributes.label;
                    attributeId = val.attributes.attributeId;
                    attributeTheId = val.attributes.attributeTheId;
                    rowProduct += " + " + attributeValue;
                }
                html =
                    "<div class='row' id='row-" + val.id + "'>" +
                    "<div class='col-6'>" +
                    "<a href=''>" + rowProduct + "</a>" +
                    "<a href='#' class='no-barba delivery-cart-button delivery-remove-from-cart' delivery-productId='" + val.attributes.productId + "' delivery-attributeId='"+attributeId+"' ><span class='icon-qpc-delivery-06'></span></a>" +
                    "</div>" +
                    "<div class='col-3'>" +
                //    val.quantity +

                    "<a href='#' class='no-barba delivery-cart-button delivery-add-to-cart' delivery-attributeValue='"+attributeValue+"' delivery-attributeId='"+attributeId+"' delivery-attributeTheId='"+attributeTheId+"' delivery-price='" + val.price + "' delivery-productId='" + val.attributes.productId + "' delivery-qty='-1' >-</a>" +
                    "<input type='text' class='delivery-add-to-cart' delivery-price='" + val.price + "' delivery-productId='" + val.attributes.productId + "'  delivery-qty='" + val.quantity + "' value='" + val.quantity + "' delivery-attributeValue='"+attributeValue+"' delivery-attributeId='"+attributeId+"' delivery-attributeTheId='"+attributeTheId+"' />" +
                    "<a href='#' class='no-barba delivery-cart-button delivery-add-to-cart' delivery-attributeValue='"+attributeValue+"' delivery-attributeId='"+attributeId+"' delivery-attributeTheId='"+attributeTheId+"' delivery-price='" + val.price + "' delivery-productId='" + val.attributes.productId + "' delivery-qty='1' >+</a>" +
                    "</div>" +

                    "<div class='col-3 alignRight boldFont'>" + currencyFormat(rowTotal) + "</div>" +
                    "</div>";
                $("#delivery-cart").append(html);

                subtotal += parseInt(rowTotal);
            }
        });

        if ($("#row-total").length === 0) {
            html = "<div class='row' id='row-total'>" +
                "<div class='col-3'></div>" +
                "<div class='col-3'> </div>" +
                "<div class='col-6 alignRight'>" + delivery.subtotal_label + " " + currencyFormat(subtotal) + "</div>" +
                "</div>";
            $("#delivery-cart").append(html);
        }
        $("#loader").addClass("hidden");
        // console.log("removeaddClassClass delivery-getCart");
    });
}
function getCartCount() {

  //  console.log("entro nella chiamata");
    $.post(delivery.ajax, {
        deliverySession: Cookies.get("delivery-session"),
        action: "getCart"
    }, function (res) {
        $("#delivery-cart-count").html(countCartItems(res));
    }).fail(function (response){
        console.log("errore get cart");
    });
}


function countCartItems(res) {

    obj = JSON.parse(res);
    total = 0;
    $.each(obj, function (i, val) {
        total += parseInt(val.quantity);
    });

    return total;
}
