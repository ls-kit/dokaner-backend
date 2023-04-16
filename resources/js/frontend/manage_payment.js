import { cart_list_arranging_by_item, customer_address } from "./cartHelpers";

function clearProductCart() {
  let cartList = mybdcart.list();  
  cartList = cartList.filter(filterItem => filterItem.is_check == true);
  cartList.map(cart => {
    mybdcart.remove(cart.id);
  });
  window.localStorage.removeItem("_summary");
}

(function($) {
    let body = $("body");

    const dom = $("body");

    function show_loader() {
        dom.find(".preloader").fadeIn();
    }

    function hide_loader() {
        dom.find(".preloader").fadeOut();
    }

    function sslCommerzPaymentProcess(tran_id) {
        $.ajax({
            url: "/sslcommerz/payment",
            method: "POST",
            data: {
                tran_id: tran_id
            },
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            beforeSend: function() {
                show_loader();
            },
            success: function(response) {
                if (response.status === "success") {
                    clearProductCart();
                    window.location.replace(response.data);
                } else {
                    Swal.fire({
                        icon: "warning",
                        text: "Payment process error, try again"
                    });
                    setTimeout(function() {
                        //  window.location.assign('/failed-order-pay-now/' + tran_id);
                    }, 1000);
                }
            },
            error: function(xhr) {
                console.log(xhr);
            },
            complete: function() {
                hide_loader();
            }
        });
    }

    function load_the_payment_confirm(tran_id) {
        let cartList = mybdcart.list();
        cartList = cartList.filter(filterItem => filterItem.is_check == true);
        cartList = cart_list_arranging_by_item(cartList);
        let address = customer_address();
        let summary = window.localStorage.getItem("_summary");
        summary = JSON.parse(summary);
        $.ajax({
            url: "/ajax/order-confirm",
            method: "POST",
            data: {
                order_id: tran_id,
                OrderItem: JSON.stringify(cartList),
                summary: JSON.stringify(summary),
                address: JSON.stringify(address)
            },
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            beforeSend: function() {
                show_loader();
            },
            success: function(response) {
                if (response.status) {
                    sslCommerzPaymentProcess(response.tran_id);
                }
            },
            error: function(xhr) {
                // if error occurred
                console.log("error", xhr);
            },
            complete: function() {
                hide_loader();
            }
        });
    }

    function payment_summary() {
        let currency = b2b.currency_icon;
        let summary = window.localStorage.getItem("_summary");
        summary = JSON.parse(summary);
        body.find("#productTotalPrice").text(
            `${currency} ` + summary.productTotal
        );
        body.find("#couponDiscount").text(
            `${currency} ` + summary.couponDiscount
        );
        body.find("#needToPay").text(`${currency} ` + summary.needToPay);
        body.find("#dueForProducts").text(
            `${currency} ` + summary.dueForProducts
        );

        let couponRow = body.find("#couponRow");
        if (summary.couponDiscount) {
            couponRow.show();
        } else {
            couponRow.hide();
        }
    }

    function shipping_address() {
        let address = customer_address();
        let name = address.hasOwnProperty("name") ? address.name : "Not set";
        let phone_one = address.hasOwnProperty("phone_one")
            ? address.phone_one
            : "Not set";
        let phone_two = address.hasOwnProperty("phone_two")
            ? address.phone_two
            : null;
        let full_Address = address.hasOwnProperty("address")
            ? address.address
            : "Not set";
        let html_address = `<tr>
                      <td><b>Name:</b> ${name}</td>
                    </tr> <tr>
                      <td><b>Phone:</b> ${phone_one}</td>
                    </tr>
                     ${
                         phone_two
                             ? `<tr>
                      <td><b>Phone Two:</b> ${phone_two}</td>
                    </tr>`
                             : ""
                     }
                     <tr>
                      <td><b>Full Address:</b> ${full_Address}</td>
                    </tr>`;
        body.find("#address_body").html(html_address);
    }

    let dataPage = $(document).find("[data-page=payment]");
    if (dataPage.length) {
        let products = mybdcart.list();
        if (!products.length) {
            window.location.replace("/shopping-cart");
        }
        let address = customer_address();
        if (!address) {
            window.location.replace("/shopping-cart");
        }

        payment_summary();
        shipping_address();
    }

    body.on("click", "#payNowBtn", function() {
        let termsField = $("#termsField");
        let tran_id = $(this).attr("data-order");
        if (termsField.is(":checked")) {
            load_the_payment_confirm(tran_id);
        } else {
            Swal.fire({
                icon: "warning",
                text:
                    "Read and agree to the website terms and conditions and refund policy"
            });
        }
    }).on("click", "#incompletePayNowBtn", function() {
        let termsField = $(document).find("#termsField");
        let tran_id = $(this).attr("data-transaction");
        if (termsField.is(":checked")) {
            sslCommerzPaymentProcess(tran_id);
        } else {
            Swal.fire({
                icon: "warning",
                text:
                    "Please Check, Read and agree to the website Terms and Conditions and Refund Policy"
            });
        }
    });
})(jQuery);
