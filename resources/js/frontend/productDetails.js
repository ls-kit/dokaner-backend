import Swal from "sweetalert2";
import {add, exists, get, quantity, total, update} from "cart-localstorage";
import {
   calculate_cart_item,
   productCart,
   updateCart,
   check_cart,
   loadingIcon,
   calculate_localDelivery,
   calculateAirShippingCharge,
   loadingText,
   loadingFail,
   QuantityRangesPrice,
   ConfiguratorsAttributes,
   details_page_total_qty,
   load_cart_by_ajax,
   obj_key_exists,
   update_customer_checkout,
   productId,
   findingPromotionalPrice
} from "./cartHelpers";

let dom = $(document);

function loadPrices(reload = 0) {
   var priceUrl = $(document)
      .find(".main_content")
      .attr("data-getAdditionalInformation");
   var item_id = $(document)
      .find("#itemFullInfo")
      .attr("data-id");
   var priceBox = $(".loadAdditionalInformation");
   var AdditionalInfo = $("#Additional-info");
   $.ajax({
      url: priceUrl,
      method: "POST",
      data: {
         item_id: item_id
      },
      dataType: "json",
      headers: {
         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      beforeSend: function () {
         priceBox.html(loadingText);
         AdditionalInfo.html(loadingText);
      },
      success: function (response) {
         if (response.status === false) {
            if (reload < 3) {
               loadPrices(reload + 1);
            }
         } else {
            let htmlData = response.data;
            let additional = response.additional;
            priceBox.html(htmlData);
            AdditionalInfo.html(additional);
         }
      },
      error: function (xhr) {
         console.log(xhr);
      },
      complete: function () {
         find_product_PhysicalParameters();
         filter_attribute();
         calculate_cart_item();
         // update_customer_checkout(); // off for new testing
         //loadSellerInformationAjax(); // auto load seller info load by ajax
         setTimeout(function () {
            reload_product_delivery_cost_information();
         }, 300);
      }
   });
}

$(document).on("click", ".loadSellerInformation", function () {
   loadSellerInformationAjax(); // auto load seller info load by ajax
});

function loadDescriptionAjax(route, reload = 0) {
   var item_id = productId();
   var Description = $("#Description");
   var data = {
      item_id: item_id
   };
   $.ajax({
      type: "POST",
      url: route,
      data: data,
      headers: {
         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      beforeSend: function () {
         Description.html(loadingText);
      },
      success: function (res) {
         if (res.status === false) {
            if (reload < 3) {
               loadDescriptionAjax(route, reload + 1);
            }
         } else {
            Description.html(res.data);
         }
      },
      error: function (xhr) {
         if (reload < 3) {
            loadDescriptionAjax(route, reload + 1);
         } else {
            Description.html(loadingFail);
         }
      },
      complete: function () {
         Description.find("img").removeAttr("width");
         Description.find("img").removeAttr("height");
         Description.find("img").addClass("img-fluid");
      }
   });
}

$(document).on("click", "#loadDescription", function (event) {
   event.preventDefault();
   let route = $(document)
      .find(".main_content")
      .attr("data-getItemDescription");
   loadDescriptionAjax(route);
});

function loadSellerInformationAjax(reload = 0) {
   var sellerUrl = $(document)
      .find(".main_content")
      .attr("data-getItemSellerInformation");
   const item = productId(true);
   var Seller = $(".loadVender_information_data");
   Seller.html(loadingText);
   $.ajax({
      url: sellerUrl,
      method: "POST",
      data: {
         vendor_id: item.VendorId
      },
      headers: {
         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      success: function (res) {
         if (res.status === false) {
            if (reload < 3) {
               loadSellerInformationAjax(reload + 1);
            }
         } else {
            Seller.html(res.data);
         }
      },
      error: function (xhr) {
         console.log(xhr);
         if (reload < 3) {
            loadSellerInformationAjax(reload + 1);
         } else {
            Seller.html(loadingFail);
         }
      },
      complete: function () {
      }
   });
}

$(document).on("click", ".product_size_switch span", function () {
   const this_btn = $(this);
   let fullImageUrl = this_btn.attr("data-fullimageurl");
   if (fullImageUrl) {
      $(document)
         .find(".zoomWindow")
         .css("background-image", 'url("' + fullImageUrl + '")');
      $(document)
         .find("#product_img")
         .attr("src", fullImageUrl);
   }
   $(this)
      .closest("p")
      .find("span")
      .removeClass("active");
   $(this).addClass("active");
   setTimeout(function () {
      filter_attribute();
   }, 200);
});

function product_change_effect(configItem, qty, image) {
   configItem = configItem ? configItem : {};
   let product = productId(true);
   let product_id = product.Id;
   let product_attr = product.Attributes;
   let Promotions = product.Promotions;
   let DeliveryCosts = product.DeliveryCosts;
   let approxWeight = $(document)
      .find(".hiddenPerUnitApproxWeight")
      .text();
   let shipped_by = "by_air";
   let shippingRate = 0;
   let FirstLotQuantity = Number(product.FirstLotQuantity);
   let NextLotQuantity = Number(product.NextLotQuantity);
   let BatchLotQuantity = Number(product.BatchLotQuantity);

   let QuantityRanges = obj_key_exists("QuantityRanges", configItem)
      ? configItem.QuantityRanges
      : product.QuantityRanges;
   let Configurators = obj_key_exists("Configurators", configItem)
      ? configItem.Configurators
      : [];
   let newQty = Number(qty) * NextLotQuantity;
   let itemCode = configItem.Id;
   let max = configItem.Quantity;

   let Price = obj_key_exists("Price", configItem) ? configItem.Price : product.Price;

   if (Promotions.length) {
      let promoPrice = findingPromotionalPrice(itemCode, Promotions);
      Price = Object.keys(promoPrice).length ? promoPrice : Price;
   }

   let rate = Number(QuantityRangesPrice(QuantityRanges, Price));
   let attributes = ConfiguratorsAttributes(Configurators, product_attr);
   let localDelivery = calculate_localDelivery(DeliveryCosts);
   // console.log(localDelivery)

   let mainImage = image ? image : product.MainPictureUrl;

   let item_id = product_id + "_" + itemCode;

   if (exists(item_id)) {
      update(item_id, "price", rate);
      update(item_id, "shippingRate", shippingRate);
      let effectQty = get(item_id).quantity;
      effectQty = Number(newQty) - Number(effectQty);
      quantity(item_id, effectQty);
   } else {
      const myProduct = {
         id: item_id,
         name: product.Title,
         price: rate,
         product_id: product_id,
         shipped_by: shipped_by,
         shippingRate: shippingRate,
         FirstLotQuantity: FirstLotQuantity,
         BatchLotQuantity: BatchLotQuantity,
         mainImage: mainImage,
         QuantityRanges: QuantityRanges,
         Promotions: Promotions,
         localDelivery: localDelivery,
         approxWeight: approxWeight,
         itemCode: itemCode,
         max: max,
         attributes: attributes
      };
      add(myProduct, qty);
   }
}

function add_to_cart(product_id) {
   let cart = productCart();
   if (cart.length) {
      let productItem = cart.find(
         existsItem => existsItem.product_id === product_id
      );
      if (productItem) {
         productItem.isCart = true;
      }
      //updateCart(cart);
      load_cart_by_ajax(cart);
   } else {
      alert("Please select quantity of any variation");
   }
}

$(document).on("change paste keyup", 'input[name="quantity"]', function () {
   let product = productId(true);
   let configId = $(this).attr("id");
   let qty = Number($(this).val());
   let max = Number($(this).attr("max"));
   let stepData = Number($(this).attr("step"));

   if (qty > max) {
      Swal.fire({
         icon: "error",
         text: "Maximum stock already select"
      });
      $(this).val(max);
   } else {
      let ConfiguredItems = product.ConfiguredItems;
      let configItem = ConfiguredItems.find(item => item.Id === configId);
      let image = $(".product_size_switch")
         .find("span.active")
         .find("img")
         .attr("src");
      if (qty % stepData !== 0) {
         let stepQty = Math.ceil(qty / stepData);
         qty = stepQty * stepData;
      }
      $(this).val(qty);
      product_change_effect(configItem, qty, image);
   }
});


$(document).on("click", ".plus", function () {
   let input_field = $(this)
      .closest(".input-group")
      .find("input");
   let stepData = Number(input_field.attr("step"));
   let inputValue = Number(input_field.val());
   let max = Number(input_field.attr("max"));

   if (inputValue < max) {
      stepData = stepData > 1 ? stepData : 1;
      input_field.val(+inputValue + stepData).trigger("change");
   } else {
      Swal.fire({
         icon: "error",
         text: "Maximum stock already select"
      });
   }
});

$(document).on("click", ".minus", function () {
   let input_field = $(this)
      .closest(".input-group")
      .find("input");
   let stepData = Number(input_field.attr("step"));
   let inputValue = Number(input_field.val());
   stepData = stepData > 1 ? stepData : 1;
   if (input_field.val() >= 1) {
      if (input_field.val() >= 1)
         input_field.val(+inputValue - stepData).change();
   }
});

$(function () {
   const dataPage = $(document)
      .find(".main_content")
      .attr("data-page");
   if (dataPage === "productDetails") {
      loadPrices(); //load additional information
   }
   let product_cart = productCart();
   let cartProducts = [];
   if (product_cart.length) {
      cartProducts = productCart().filter(
         findProduct => findProduct.isCart === true
      );
      updateCart(cartProducts);
   }
   // load_cart_by_ajax(cartProducts);
});


