import {cart_list_arranging_by_item, check_cart, customer_address, productId} from "./cartHelpers";
import Swal from "sweetalert2";

const loadingSpinner = `<div class="my-5 w-100 text-center loadingSpinner"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>`;
const loadingIcon = `<div class="text-center w-100"><div class="spinner-border text-muted" style="width: 22px; height: 22px"></div></div>`;
const loadingText = `<div class="text-center w-100"><div class="spinner-border text-muted" style="width: 22px; height: 22px"></div><p style="margin:0;">Loading information, please wait...</p></div>`;
const loadingFail = `<div class="text-center w-100 text-danger"><p style="margin:0;">Loading fail, please try again</p></div>`;

// start pure function

/**
 * @description converted price with global settings
 * @param price
 * @returns {number}
 */
function convertedPrice(price) {
   let rate = Number(b2b.increase_rate);
   let totalPrice = Number(price) * rate;
   return Math.ceil(totalPrice);
}

/**
 * @description calculate local delivery for product
 * @returns {number}
 * @param DeliveryCosts
 */
function calculate_localDelivery(DeliveryCosts = []) {
   let return_cost = 0;
   if (DeliveryCosts.length) {
      for (let i = 0; i < DeliveryCosts.length; i++) {
         const element = DeliveryCosts[i];
         let Price = element.hasOwnProperty("Price") ? element.Price : {};
         if (Object.keys(Price).length) {
            return_cost = Price.hasOwnProperty("OriginalPrice")
               ? Price.OriginalPrice
               : 0;
            break;
         }
      }
   }
   return convertedPrice(return_cost);
}

/**
 * @description calculate air shipping charge base on product subtotal
 * @param subTotal
 * @returns {number}
 */
function calculateAirShippingCharge(subTotal) {
   let charges = b2b.hasOwnProperty("air_shipping_charges") ? JSON.parse(b2b.air_shipping_charges) : [];
   let airLength = charges.length;
   let chargeAmount = 880;
   let dCharge = null;
   if (airLength) {
      for (let i = 0; i < airLength; i++) {
         dCharge = charges[i];
         if (dCharge.minimum < subTotal && dCharge.maximum > subTotal) {
            chargeAmount = dCharge.rate;
            break;
         }
      }
   }
   return parseInt(chargeAmount);
}

/**
 * @description configurators Item attributes calculate
 * @param Configurators
 * @param attributes
 * @returns {[]}
 * @constructor
 */
function ConfiguratorsAttributes(Configurators, attributes) {
   let attr = [];
   if (Configurators.length) {
      Configurators.map((config, key) => {
         let findAttr = attributes.find(
            (attribute, key) =>
               (attribute.IsConfigurator = attribute.Vid === config.Vid)
         );
         if (findAttr) {
            attr.push(findAttr);
         }
      });
   }
   return attr;
}

/**
 * @description finding promotional price
 * @param itemCode
 * @param Promotions
 * @returns {[]}
 */
function findingPromotionalPrice(itemCode, Promotions) {
   let promoPrice = [];
   for (let n = 0; n < Promotions.length; n++) {
      let Promotion = Promotions[n];
      if (Object.keys(Promotion).length) {
         let ConfiguredItems = Promotion.hasOwnProperty("ConfiguredItems")
            ? Promotion.ConfiguredItems
            : [];
         let ConfiguredItem = ConfiguredItems.find(
            configItem => configItem.Id === itemCode
         );
         if (ConfiguredItem) {
            let configPrice = ConfiguredItem.hasOwnProperty("Price")
               ? ConfiguredItem.Price
               : {};
            if (Object.keys(configPrice).length) {
               promoPrice = configPrice;
               break;
            }
         } else {
            let Price = Promotion.hasOwnProperty("Price")
               ? Promotion.Price
               : {};
            if (Object.keys(Price).length) {
               promoPrice = Price;
               break;
            }
         }
      }
   }
   return promoPrice;
}

/**
 * @description load_attribute image if exits
 * @param attributes
 * @param mainImage
 * @returns {string}
 */
function load_attribute_image(attributes, mainImage = null) {
   let attr_data = "";
   if (attributes.length) {
      let attribute = attributes.find(attr =>
         attr.hasOwnProperty("MiniImageUrl")
      );
      if (attribute) {
         attr_data += `<img src="${attribute.MiniImageUrl}" class="img-sm" alt="">`;
      } else {
         attribute = attributes.find(attr =>
            attr.hasOwnProperty("ImageUrl")
         );
         if (attribute) {
            attr_data += `<img src="${attribute.ImageUrl}" class="img-sm" alt="">`;
         } else {
            attr_data += `<img src="${mainImage}" class="img-sm" alt="">`;
         }
      }
   } else {
      attr_data += `<img src="${mainImage}" class="img-sm" alt="">`;
   }
   return attr_data;
}

/**
 * @description load attributes
 * @param attributes
 * @returns {string}
 */
function load_attributes(attributes) {
   let attr_data = `<p class="cartSmallText text-capitalize ">`;
   if (attributes.length) {
      attributes.map(attr => {
         attr_data += `${attr.PropertyName}: ${attr.Value}, <br>`;
      });
   }
   attr_data += `</p>`;
   return attr_data;
}

/**
 * @description check key exits in object
 * @param key
 * @param obj
 * @returns {boolean}
 */
function obj_key_exists(key, obj) {
   return obj.hasOwnProperty(key);
}

/**
 * @description calculate current item quantity
 * @returns {number}
 */
function details_page_total_qty(item_id) {
   let list = mybdcart.list().filter(item => item.product_id === item_id);
   let quantity = 0;
   if (list.length) {
      list.map(listItem => {
         quantity += listItem.quantity;
      });
   }
   return quantity;
}

/**
 * @return {number}
 */
function QuantityRangesPrice(ranges, Price = {}, total_qty = 0, item_id) {
   let quantity = total_qty ? total_qty : details_page_total_qty(item_id);
   let unitPrice = 0;
   if (ranges.length) {
      for (let i = 0; i < ranges.length; i++) {
         let QtyRange = ranges[i];
         let nextQtyRange = ranges[i + 1];
         let MinQuantity = QtyRange.hasOwnProperty("MinQuantity")
            ? QtyRange.MinQuantity
            : 0;
         let Price = QtyRange.hasOwnProperty("Price") ? QtyRange.Price : 0;
         if (quantity < MinQuantity) {
            unitPrice = Price.hasOwnProperty("OriginalPrice")
               ? Price.OriginalPrice
               : 0;
            break;
         } else {
            if (nextQtyRange) {
               nextQtyRange = nextQtyRange.MinQuantity;
               if (MinQuantity <= quantity && quantity < nextQtyRange) {
                  unitPrice = Price.hasOwnProperty("OriginalPrice")
                     ? Price.OriginalPrice
                     : 0;
                  break;
               }
            } else {
               unitPrice = Price.hasOwnProperty("OriginalPrice")
                  ? Price.OriginalPrice
                  : 0;
            }
         }
      }
   }
   if (!unitPrice && Object.keys(Price).length) {
      unitPrice = Price.hasOwnProperty("OriginalPrice")
         ? Price.OriginalPrice
         : 0;
   }
   unitPrice = convertedPrice(unitPrice);
   if (Number(unitPrice) && ranges.length) {
      let currency = b2b.currency_icon;
      $(document)
         .find("#itemCalculationTable")
         .find(".priceRate")
         .text(currency + " " + Math.ceil(unitPrice));
   }

   return Number(unitPrice);
}

/**
 * @description update customer cart
 * @param reload
 * @param finalCart
 */
function update_customer_checkout(reload = 0, finalCart = false) {
   let cartList = mybdcart.list();
   cartList = cart_list_arranging_by_item(cartList);

   axios.post("/ajax/update-customer-checkout", {cart: JSON.stringify(cartList)})
      .then(response => {
         // console.log('response', response);
      })
      .catch(response => {
         console.log('response', response.error)
      })
      .then(() => {
         console.log('complete loading_cart');
      });
}

/**
 *
 * @param cartProducts
 */
function load_cart_by_ajax(cartProducts) {
   setTimeout(() => {
      let product_id = 0;
      let customerCart = mybdcart.list();
      let loadCart = false;
      if (product_id) {
         let loadProduct = cartProducts.find(
            filterItem =>
               filterItem.product_id === product_id &&
               filterItem.isCart === true
         );
         if (loadProduct) {
            loadCart = true;
         }
      } else {
         if (customerCart.length) {
            loadCart = true;
         }
      }
      if (loadCart || customerCart.length === 0) {
         $.ajax({
            type: "POST",
            url: "/ajax/load-customer-cart",
            data: {
               cartProducts: JSON.stringify(customerCart)
            },
            dataType: "json",
            headers: {
               "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            beforeSend: function () {
               // before loading...
            },
            success: function (response) {
               if (response.status) {
                  updateCart(response.cart);
                  $(document)
                     .find("#cart_count")
                     .text(response.cart.length);
               }
            },
            error: function (xhr) {
               // if error occurred
               // console.log('error', xhr);
            },
            complete: function () {
               check_cart(product_id);
            }
         });
      }
   }, 500);
}

function validateCartProducts(proceedLink = null) {
   let cartList = mybdcart.list();
   let address = customer_address();
   cartList = cartList.filter(filterItem => filterItem.is_check === true && filterItem.is_cart === true);

   if (!cartList.length) {
      Swal.fire({
         icon: "warning",
         text: "Please select your product first!"
      });
      return false;
   }

   if (!address.hasOwnProperty("name")) {
      Swal.fire({
         icon: "warning",
         text: "Please add your shipping address first!"
      });
      return false;
   }

   cartList = cart_list_arranging_by_item(cartList);
   let proceed = true;

   if (Object.keys(cartList).length && proceed) {
      let minQtyOnSetting = Number(b2b.min_order_quantity);
      let minAmount = Number(b2b.min_order_amount);
      let currency_icon = b2b.currency_icon;
      let totalItem = 0;
      for (const item_id in cartList) {
         let items = cartList.hasOwnProperty(item_id) ? cartList[item_id] : [];
         let title = "";
         let mainImage = "";
         let $table = "";
         let minQuantity = 1;
         let localDelivery = 0;
         let itemQuantity = 0;
         let itemSubTotal = 0;
         totalItem += 1;

         items.map(product => {
            let product_id = product.hasOwnProperty("product_id")
               ? product.product_id
               : 0;
            mainImage = product.hasOwnProperty("mainImage")
               ? product.mainImage
               : "";
            minQuantity = product.hasOwnProperty("FirstLotQuantity")
               ? product.FirstLotQuantity
               : 0;
            title = product.hasOwnProperty("name") ? product.name : 0;

            let quantity = product.hasOwnProperty("quantity")
               ? product.quantity
               : 0;
            let price = product.hasOwnProperty("price") ? product.price : 0;
            localDelivery = product.hasOwnProperty("localDelivery")
               ? product.localDelivery
               : 0;
            let rowTotal = quantity * price;
            itemSubTotal += rowTotal;
            itemQuantity += quantity;
         });

         $table = `<table class="table table-bordered table-sm">
                          <tr>
                            <td class="align-middle">
                              <img src="${mainImage}" style="width:90px">
                            </td>
                            <td class="align-middle">
                              <span class="text-danger">${title}</span>
                            </td>
                          </tr>
                        </table>`;

         if (minQuantity > itemQuantity || minQtyOnSetting > itemQuantity) {
            let takenQty =
               minQtyOnSetting > minQuantity
                  ? minQtyOnSetting
                  : minQuantity;
            Swal.fire({
               // icon: 'warning',
               text: "You can't Down the Minimum Quantity of " + takenQty,
               html: $table
            });
            proceed = false;
            let docProduct = $(document)
               .find("[data-product=" + item_id + "]")
               .find(".title");
            docProduct.removeClass("text-dark");
            docProduct.addClass("text-danger");
            break;
         }

         if (itemSubTotal < minAmount) {
            Swal.fire({
               // icon: 'warning',
               title: `You can't down the minimum price of ${currency_icon} ${minAmount.toFixed(
                  2
               )}`,
               html: $table
            });
            proceed = false;
            let docProduct = $(document)
               .find("[data-product=" + item_id + "]")
               .find(".title");
            docProduct.removeClass("text-dark");
            docProduct.addClass("text-danger");
            break;
         }
      }

      if (proceed && proceedLink) {
         window.location.href = proceedLink;
      }
      return proceed;
   }
}

// end pure function

(function ($) {
   const dom = $("body");

   function show_loader() {
      dom.find(".preloader").fadeIn();
   }

   function hide_loader() {
      dom.find(".preloader").fadeOut();
   }

   /**
    * @description get details page product params and if id only return id
    * @param id
    * @returns {null}
    */
   function productItem(id = false) {
      let product = dom.find("#itemDetails").text();
      product = product ? JSON.parse(product) : {};
      let return_data = {};
      if (Object.keys(product).length) {
         if (id) {
            return_data = product.hasOwnProperty("Id") ? product.Id : 0;
         } else {
            return_data = product;
         }
      }
      return return_data;
   }

   /**
    * @description calculate card item variation attributes
    */
   function calculate_cart_item() {
      let cartList = mybdcart.list();
      cartList = cart_list_arranging_by_item(cartList);
      let new_id = 0;
      let totalItem = 0;
      let currentQty = 0;
      let currentPrice = 0;
      dom.find(".qty").val(0);

      for (const item_id in cartList) {
         let items = cartList.hasOwnProperty(item_id) ? cartList[item_id] : [];
         let totalItemPrice = 0;
         let localDelivery = 0;
         totalItem += items[0].is_cart ? 1 : 0;
         // console.log("items", items[0].is_cart)
         // console.log("totalItem", totalItem)
         items.map(product => {
            let product_id = product.hasOwnProperty("product_id") ? product.product_id : 0;
            let itemCode = product.hasOwnProperty("itemCode") ? product.itemCode : 0;
            let approxWeight = product.hasOwnProperty("approxWeight") ? Number(product.approxWeight) : 0;
            let quantity = product.hasOwnProperty("quantity") ? product.quantity : 0;
            let price = product.hasOwnProperty("price") ? product.price : 0;
            localDelivery = product.hasOwnProperty("localDelivery") ? product.localDelivery : 0;
            let rowTotal = quantity * price;
            let rowApproxWeight = quantity * approxWeight;
            totalItemPrice += rowTotal;

            let itemField = itemCode ? dom.find("#" + itemCode) : dom.find("#" + product_id);
            if (itemField.length) {
               itemField.val(quantity); // also efect checkout cart
               itemField
                  .closest("tr")
                  .find(".priceRate")
                  .text(price);

               // update checkout cart
               itemField
                  .closest("tr")
                  .find(".subTotal")
                  .text(Math.ceil(rowTotal));
               itemField
                  .closest("tr")
                  .find(".item_approx_weight")
                  .text(Number(rowApproxWeight).toFixed(3));
            }
            totalItemPrice = localDelivery ? totalItemPrice + localDelivery : totalItemPrice;
            $("tr." + item_id)
               .find(".totalItemPrice")
               .text(Math.ceil(totalItemPrice));
         });
      }

      dom.find(".cart_count").text(totalItem);

      // reset coupon can re calculate the cart summary
      let dataPage = $(document).find("[data-page=payment]");
      if (!dataPage.length) {
         resetCoupon();
      }
   }

   /**
    * @description reload physical parameters as like weight
    */
   function find_product_PhysicalParameters(item_id) {
      let weightDom = $(document).find("#approxWeight");
      let hasWeight = $(document)
         .find("#hiddenPerUnitApproxWeight")
         .attr("data-hasWeight");
      // console.log('hasWeight', typeof parseInt(hasWeight), hasWeight);
      if (parseInt(hasWeight) === 0) {
         axios
            .post("/ajax/load-physical-parameters", {
               item_id: item_id
            })
            .then(
               response => {
                  if (response.data.status) {
                     let params = response.data.PhysicalParameters;
                     let ApproxWeight = params.hasOwnProperty("ApproxWeight") ? params.ApproxWeight : 0;
                     let Weight = params.hasOwnProperty("Weight") ? params.Weight : 0;
                     let loadWeight = ApproxWeight ? ApproxWeight : Weight;
                     if (loadWeight) {
                        weightDom.text(loadWeight);
                        $("#hiddenPerUnitApproxWeight").text(
                           loadWeight
                        );
                     }
                  }
               },
               error => {
                  console.log(error);
               }
            );
      }
   }

   /**
    * @description filter attributes
    * @param reload
    */
   function filter_attribute(reload = 0) {
      let filter = $(document)
         .find(".product_size_switch")
         .find("span.active")
         .attr("data-filter");
      let colorName = $(document)
         .find(".product_size_switch")
         .find("span.active")
         .attr("data-color-name");
      if (filter) {
         let itemCalculationTable = $(document).find(
            "#itemCalculationTable"
         );
         let isHasFilter = itemCalculationTable.find("tbody tr." + filter)
            .length;
         if (isHasFilter) {
            itemCalculationTable.find("tbody tr").hide();
            itemCalculationTable.find("tbody tr." + filter).show();
         }
         if (colorName) {
            $(document)
               .find(".pr_switch_wrap")
               .find(".ColorName")
               .text(colorName);
         }
      } else {
         if (reload < 3) {
            filter_attribute(reload + 1);
         }
      }
   }

   /**
    * @description calculation for china to bd
    */
   function append_from_china_to_bd() {
      const product = productItem();
      const product_id = product.Id;
      let cart = mybdcart.list();
      // cart = cart.filter(filCart => filCart.is_cart === true);
      let currency = b2b.currency_icon;
      let reset = false;
      if (cart.length) {
         let cart_product = cart.filter(cartItem => cartItem.product_id === product_id);
         if (cart_product.length) {
            let localDelivery = 0;
            let approxWeight = 0;
            let shippingRate = 0;
            let totalQuantity = 0;
            let is_cart = false;
            let subTotal = 0;
            cart_product.map((item, keys) => {
               let quantity = item.hasOwnProperty("quantity") ? Number(item.quantity) : 0;
               let price = item.hasOwnProperty("price") ? Number(item.price) : 0;
               totalQuantity += quantity;
               subTotal += price * quantity;
               localDelivery = item.hasOwnProperty("localDelivery") ? Number(item.localDelivery) : 0;
               shippingRate = item.hasOwnProperty("shippingRate") ? Number(item.shippingRate) : 0;
               approxWeight = item.hasOwnProperty("approxWeight") ? Number(item.approxWeight) : 0;
               is_cart = item.is_cart;
            });

            dom.find("#totalQuantity").text(totalQuantity);
            dom.find("#productPrice")
               .closest("td")
               .html(`${currency} <span id="productPrice">${Math.ceil(subTotal)}</span>`);

            let total_price = subTotal + localDelivery;
            dom.find("#totalPrice")
               .closest("td")
               .html(`${currency} <span id="totalPrice">${Math.ceil(total_price)}</span>`);

            let shipping = calculateAirShippingCharge(subTotal); // need update single airShipping on cart
            if (shipping !== shippingRate) {
               dom.find("#airShippingCharge")
                  .html(`${currency} <span>${Math.ceil(shipping)}</span>`)
                  .attr("data-shippingcharge", shipping);
               let shipItems = cart_product.filter(
                  filterShipped => filterShipped.shippingRate !== shipping
               );
               shipItems.map((shipItem, keys) => {
                  mybdcart.update(shipItem.id, "shippingRate", shipping);
               });
            } else {
               if (localDelivery) {
                  dom.find("#chinaExpressFeeRow").show();
                  dom.find("#chinaLocalDelivery").text(localDelivery);
               }

               let totalWeight = totalQuantity * Number(approxWeight);
               // console.log('approxWeight', typeof approxWeight, approxWeight);
               if (Number(approxWeight)) {
                  dom.find("#approxWeight").text(
                     Number(totalWeight).toFixed(3)
                  );
               }
            }
            if (is_cart) {
               dom.find(".btn-addToCart").hide();
               dom.find("#buyNow").show();
            } else {
               dom.find(".btn-addToCart").show();
               dom.find("#buyNow").hide();
            }
         } else {
            reset = true;
         }
      } else {
         reset = true;
      }

      if (reset) {
         dom.find(".btn-addToCart").show();
         dom.find("#buyNow").hide();
         dom.find("#totalQuantity").text("0.00");
         dom.find("#approxWeight").text("0.00");
         dom.find("#chinaLocalDelivery").text("0.00");
         dom.find("#productPrice")
            .closest("td")
            .html(`${currency + " "} <span id="productPrice">0.00</span>`);
         dom.find("#totalPrice")
            .closest("td")
            .html(`${currency + " "} <span id="totalPrice">0.00</span>`);
      }
   }

   /**
    * @description load single product page details information
    */
   function load_additional_info() {
      const item_id = $("#product_id").text();
      const priceBox = $(".loadAdditionalInformation");
      const AdditionalInfo = $("#Additional-info");
      axios
         .post("/ajax/load-additional-info", {
            item_id: item_id
         })
         .then(
            response => {
               if (response.data.status) {
                  priceBox.html(response.data.main);
                  AdditionalInfo.html(response.data.additional);

                  filter_attribute();
                  find_product_PhysicalParameters(item_id);

                  // render product cart
                  renderCart();
               }
            },
            error => {
               console.log(error);
            }
         );
   }

   /**
    * @description help to find hidden loaded approx weight
    * @returns {any}
    */
   function hidden_approx_weight() {
      let weight = dom.find("#hiddenPerUnitApproxWeight").text();
      return weight ? Number(weight).toFixed(3) : 0;
   }

   /**
    * @ return air shipping charges
    * @returns {number}
    */
   function air_shipping_charge() {
      let charge = dom.find("#airShippingCharge").attr("data-shippingcharge");
      return charge ? Number(charge) : 0;
   }

   function add_new_product(productData, newQty) {
      mybdcart.add(productData, newQty);
   }

   /**
    * @description single page product effect change calculate
    * @param itemConfig
    * @param newQty
    * @param image
    */
   function product_change_effect(itemConfig, newQty, image) {
      const configItem = itemConfig ? itemConfig : {};
      let product = productItem();
      let product_id = product.hasOwnProperty("Id") ? product.Id : 0;
      let product_attributes = product.hasOwnProperty("Attributes")
         ? product.Attributes
         : [];
      let Promotions = product.hasOwnProperty("Promotions")
         ? product.Promotions
         : [];
      let MasterQuantity = product.hasOwnProperty("MasterQuantity")
         ? product.MasterQuantity
         : 0;
      let QuantityRanges = product.hasOwnProperty("QuantityRanges")
         ? product.QuantityRanges
         : [];
      let Price = product.hasOwnProperty("Price") ? product.Price : {};
      let MainPictureUrl = product.hasOwnProperty("MainPictureUrl")
         ? product.MainPictureUrl
         : "";
      let DeliveryCosts = product.hasOwnProperty("DeliveryCosts")
         ? product.DeliveryCosts
         : [];

      // config items
      QuantityRanges = configItem.hasOwnProperty("QuantityRanges")
         ? configItem.QuantityRanges
         : QuantityRanges;
      Price = configItem.hasOwnProperty("Price") ? configItem.Price : Price;
      let Configurators = configItem.hasOwnProperty("Configurators")
         ? configItem.Configurators
         : [];
      let itemCode = configItem.hasOwnProperty("Id") ? configItem.Id : 0;
      let max = configItem.hasOwnProperty("Quantity")
         ? configItem.Quantity
         : MasterQuantity;

      let approxWeight = hidden_approx_weight();
      let shipped_by = "by_air";
      let shippingRate = air_shipping_charge();

      let express = $("#chinaLocalDelivery").attr("data-express");
      let localDelivery = 0;
      if (express) {
         localDelivery = parseInt(express);
      } else {
         localDelivery = calculate_localDelivery(DeliveryCosts);
      }
      // let newQty = inputQty * NextLotQuantity;

      if (Promotions.length) {
         let promoPrice = findingPromotionalPrice(itemCode, Promotions);
         Price = Object.keys(promoPrice).length ? promoPrice : Price;
      }

      let rate = QuantityRangesPrice(QuantityRanges, Price, product_id);
      let attributes = ConfiguratorsAttributes(
         Configurators,
         product_attributes
      );

      let item_id = itemCode ? product_id + "_" + itemCode : product_id;

      if (mybdcart.exists(item_id)) {
         mybdcart.list().map((listItem) => {
            mybdcart.update(listItem.id, "is_cart", false);
         });
         mybdcart.update(item_id, "price", rate);
         mybdcart.update(item_id, "shippingRate", shippingRate);
         mybdcart.update(item_id, "approxWeight", approxWeight);
         mybdcart.update(item_id, "localDelivery", localDelivery);
         let effectQty = mybdcart.get(item_id).quantity;
         effectQty = parseInt(newQty) - parseInt(effectQty);
         mybdcart.quantity(item_id, effectQty);
      } else {
         let newProductData = {
            id: item_id,
            name: product.hasOwnProperty("Title") ? product.Title : "",
            price: rate,
            is_cart: false,
            product_id: product_id,
            shipped_by: shipped_by,
            shippingRate: shippingRate,
            FirstLotQuantity: product.hasOwnProperty("FirstLotQuantity")
               ? product.FirstLotQuantity
               : 1,
            BatchLotQuantity: product.hasOwnProperty("BatchLotQuantity")
               ? product.BatchLotQuantity
               : 1,
            mainImage: image ? image : MainPictureUrl,
            QuantityRanges: QuantityRanges,
            Promotions: Promotions,
            localDelivery: localDelivery,
            approxWeight: approxWeight,
            itemCode: itemCode,
            max: max,
            attributes: attributes
         };
         if (!localDelivery) {
            show_loader();
            axios
               .post("/ajax/reload-product-delivery-cost", {
                  product_id: product_id
               })
               .then(
                  response => {
                     if (response.data.status) {
                        localDelivery = calculate_localDelivery(
                           response.data.DeliveryCosts
                        );
                        if (localDelivery) {
                           $("#chinaLocalDelivery").attr(
                              "data-express",
                              localDelivery
                           );
                        }
                        newProductData.localDelivery = localDelivery;
                        add_new_product(newProductData, newQty);
                     }
                     hide_loader();
                  },
                  error => {
                     console.log(error);
                  }
               );
         } else {
            add_new_product(newProductData, newQty);
         }
      }

      append_from_china_to_bd();
   }

   function single_item_update(product, newQty) {
      let item_id = product.hasOwnProperty("id") ? product.id : 0;
      let product_id = product.hasOwnProperty("product_id")
         ? product.product_id
         : 0;
      let itemCode = product.hasOwnProperty("itemCode")
         ? product.itemCode
         : 0;
      let Promotions = product.hasOwnProperty("Promotions")
         ? product.Promotions
         : [];
      let QuantityRanges = product.hasOwnProperty("QuantityRanges")
         ? product.QuantityRanges
         : [];
      let product_attributes = product.hasOwnProperty("attributes")
         ? product.attributes
         : [];
      let quantity = product.hasOwnProperty("quantity")
         ? product.quantity
         : 0;
      let rate = product.hasOwnProperty("price") ? product.price : 0;
      let newPrice = {};
      if (Promotions.length) {
         let promoPrice = findingPromotionalPrice(itemCode, Promotions);
         newPrice = Object.keys(promoPrice).length ? promoPrice : {};
      }

      if (Object.keys(newPrice).length) {
         rate = QuantityRangesPrice(QuantityRanges, newPrice, product_id);
      }

      if (mybdcart.exists(item_id)) {
         mybdcart.update(item_id, "price", rate);
         let effectQty = parseInt(newQty) - parseInt(quantity);
         mybdcart.quantity(item_id, effectQty);
      }
   }

   $("body")
      .on("change paste keyup", "input[name=quantity]", function () {
         let product = productItem();
         let config_id = $(this).attr("id");
         let inputQty = parseInt($(this).val());
         let max = parseInt($(this).attr("max"));
         let stepData = parseInt($(this).attr("step"));

         if (inputQty > max) {
            Swal.fire({
               icon: "error",
               text: "Maximum stock already select"
            });
            $(this).val(max);
         } else {
            let ConfiguredItems = product.ConfiguredItems;
            let configItem = ConfiguredItems.find(
               item => item.Id === config_id
            );
            let image = $(".product_size_switch")
               .find("span.active")
               .find("img")
               .attr("src");
            if (inputQty % stepData !== 0) {
               let stepQty = Math.ceil(inputQty / stepData);
               inputQty = stepQty * stepData;
            }
            $(this).val(inputQty);
            product_change_effect(configItem, inputQty, image);
         }
      })
      .on("change paste keyup", "input[name=inputQty]", function () {
         let unique_id = $(this).attr("data-unique");
         let inputQty = parseInt($(this).val());
         let max = parseInt($(this).attr("max"));
         let stepData = parseInt($(this).attr("step"));

         if (inputQty > max) {
            Swal.fire({
               icon: "error",
               text: "Maximum stock already select"
            });
            $(this).val(max);
         } else {
            if (inputQty % stepData !== 0) {
               let stepQty = Math.ceil(inputQty / stepData);
               inputQty = stepQty * stepData;
            }
            $(this).val(inputQty);
            if (mybdcart.exists(unique_id)) {
               let product = mybdcart.get(unique_id);
               single_item_update(product, inputQty);
            }
         }
      })
      .on("click", ".plus", function () {
         let input = $(this)
            .closest(".input-group")
            .find("input");
         let stepData = Number(input.attr("step"));
         let inputValue = Number(input.val());
         let max = Number(input.attr("max"));

         if (inputValue < max) {
            stepData = stepData > 1 ? stepData : 1;
            input.val(+inputValue + stepData).trigger("change");
         } else {
            Swal.fire({
               icon: "error",
               text: "Maximum stock already select"
            });
         }
      })
      .on("click", ".minus", function () {
         let input = $(this)
            .closest(".input-group")
            .find("input");
         let stepData = Number(input.attr("step"));
         let inputValue = Number(input.val());
         stepData = stepData > 1 ? stepData : 1;
         if (input.val() >= 1) {
            if (input.val() >= 1)
               input.val(+inputValue - stepData).change();
         }
      })
      .on("click", ".product_size_switch span", function () {
         const this_btn = $(this);
         let fullImageUrl = this_btn.attr("data-fullimageurl");
         if (fullImageUrl) {
            dom.find(".zoomWindow").css(
               "background-image",
               'url("' + fullImageUrl + '")'
            );
            dom.find("#product_img").attr("src", fullImageUrl);
         }
         $(this)
            .closest("p")
            .find("span")
            .removeClass("active");
         $(this).addClass("active");
         setTimeout(function () {
            filter_attribute();
         }, 200);
      })
      .on("click", ".loadSellerInformation", function () {
         loadSellerInformationAjax(); // auto load seller info load by ajax
      })
      .on("click", "#loadDescription", function (event) {
         event.preventDefault();
         let route = $(document)
            .find(".main_content")
            .attr("data-getItemDescription");
         loadDescriptionAjax(route);
      })
      .on("click", ".btn-addToCart", function (event) {
         event.preventDefault();
         let product_id = productItem('id');
         let cart = mybdcart.list();
         let filterCart = cart.filter(filterCart => filterCart.product_id === product_id);
         if (filterCart.length) {
            let minQuantityOnSetting = Number(b2b.min_order_quantity);
            let isAddToCart = true;
            let totalQuantity = 0;
            let subTotal = 0;
            let minQuantity = 0;
            filterCart.map((cart) => {
               totalQuantity += cart.quantity;
               subTotal += cart.price * cart.quantity;
               minQuantity = cart.FirstLotQuantity;
            });

            if (minQuantity || minQuantityOnSetting) {
               if (minQuantity > totalQuantity || minQuantityOnSetting > totalQuantity) {
                  let takenQty = minQuantityOnSetting > minQuantity ? minQuantityOnSetting : minQuantity;
                  Swal.fire({
                     icon: "info",
                     text: "Minimum Order Quantity " + takenQty
                  });
                  isAddToCart = false;
               }
            }

            if (isAddToCart) {
               let currency_icon = b2b.currency_icon;
               let minAmount = Number(b2b.min_order_amount);
               subTotal = Number(subTotal);
               if (subTotal < minAmount) {
                  Swal.fire({
                     icon: "info",
                     text: `Minimum Order Value ${currency_icon} ${minAmount.toFixed(2)}`
                  });
                  isAddToCart = false;
               }
            }
            if (isAddToCart) {
               filterCart.map((cartItem) => {
                  mybdcart.update(cartItem.id, "is_cart", true);
               });
            }
         } else {
            Swal.fire({
               icon: "warning",
               text: "Please increase your Quantity first"
            });
         }
      })
      .on("click", ".removeCart", function (event) {
         event.preventDefault();
         let item_code = $(this).attr("href");
         Swal.fire({
            icon: "warning",
            text: "Are you sure to remove this?",
            showCancelButton: true,
            confirmButtonText: "Yes, Remove!"
         }).then(result => {
            mybdcart.remove(item_code);
            loaded_checkout_cart();
         });
      });

   function loadSellerInformationAjax(reload = 0) {
      var sellerUrl = $(document)
         .find(".main_content")
         .attr("data-getItemSellerInformation");
      const item = productItem();
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

   function loadDescriptionAjax(route, reload = 0) {
      var item_id = productItem(true);
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

   function configure_qty_input(
      config_id,
      BatchLotQuantity,
      quantity,
      max,
      item_unique_id
   ) {
      let qty_box = `<div class="input-group input-group-sm">`;
      qty_box += `<div class="input-group-prepend">`;
      qty_box += `<button type="button" class="btn btn-primary minus"><i class="fas fa-minus"></i></button>`;
      qty_box += `</div>`;
      qty_box += `<input type="text" name="inputQty" class="qty form-control text-center" step="${BatchLotQuantity}"  title="Qty" id="${config_id}" data-unique="${item_unique_id}" max="${max}" value="${quantity}" size="5">`;
      qty_box += `<div class="input-group-append">`;
      qty_box += `<button type="button" class="btn btn-primary plus"><i class="fas fa-plus"></i></button>`;
      qty_box += `</div>`;
      qty_box += `</div>`;
      return qty_box;
   }

   /**
    * @description load the checkout cart
    */
   function loaded_checkout_cart() {
      const currency = b2b.currency_icon;
      let cartList = mybdcart.list();
      cartList = cartList.filter(filterCartlist => filterCartlist.is_cart === true);

      if (cartList.length) {
         cartList = cart_list_arranging_by_item(cartList);
         let htmlData = "";

         for (const item_id in cartList) {
            let items = cartList.hasOwnProperty(item_id) ? cartList[item_id] : [];
            let subTotal = 0;
            let localDelivery = 0;
            let shippingRate = 0;
            let totalItemPrice = 0;

            items.map(product => {
               let item_unique_id = product.hasOwnProperty("id")
                  ? product.id
                  : 0;
               let product_id = product.hasOwnProperty("product_id")
                  ? product.product_id
                  : 0;
               localDelivery = product.hasOwnProperty("localDelivery")
                  ? product.localDelivery
                  : 0;
               shippingRate = product.hasOwnProperty("shippingRate")
                  ? product.shippingRate
                  : 0;
               let shipped_by = product.hasOwnProperty("shipped_by")
                  ? product.shipped_by
                  : 0;
               let approxWeight = product.hasOwnProperty("approxWeight")
                  ? Number(product.approxWeight)
                  : 0;
               let FirstLotQuantity = product.hasOwnProperty(
                  "FirstLotQuantity"
               )
                  ? product.FirstLotQuantity
                  : 0;
               let BatchLotQuantity = product.hasOwnProperty(
                  "BatchLotQuantity"
               )
                  ? product.BatchLotQuantity
                  : 0;
               let title = product.hasOwnProperty("name")
                  ? product.name
                  : "";
               let mainImage = product.hasOwnProperty("mainImage")
                  ? product.mainImage
                  : "";
               let QuantityRanges = product.hasOwnProperty(
                  "QuantityRanges"
               )
                  ? product.QuantityRanges
                  : [];
               let attributes = product.hasOwnProperty("attributes")
                  ? product.attributes
                  : [];
               let itemCode = product.hasOwnProperty("itemCode")
                  ? product.itemCode
                  : 0;
               let quantity = product.hasOwnProperty("quantity")
                  ? product.quantity
                  : 0;
               let max = product.hasOwnProperty("max") ? product.max : 0;
               let rate = product.hasOwnProperty("price")
                  ? product.price
                  : 0;
               let is_check = product.hasOwnProperty("is_check")
                  ? product.is_check
                  : 0;

               subTotal = rate * quantity; // single item subtotal
               totalItemPrice += subTotal; // cart subtotal

               let itemGroup = itemCode
                  ? product_id + "_" + itemCode
                  : product_id;
               itemCode = itemCode ? itemCode : product_id;

               htmlData += `<tr class="border-bottom" data-itemGroup="${item_unique_id}" data-product="${product_id}">
               <td class="align-middle text-center"><input type="checkbox" class="checkbox_item" value="${item_unique_id}" ${
                  is_check ? 'checked="checked"' : ""
               } /></td><td class="align-middle text-center"  style="min-width: 90px;">
                    <figure class="itemside">
                      <div class="aside">
                      ${load_attribute_image(attributes, mainImage)}                    
                      </div>
                    </figure>
                  </td>
                  <td class="align-middle" style="min-width: 160px;">
                    <figure class="itemside m-0">
                      <figcaption class="info">
                        <a href="/product/${product_id}" class="title text-dark" target="_blank">${title}</a>
                        ${load_attributes(attributes)}
                        <p class="cartSmallText text-danger">Approx Weight: <span class="item_approx_weight">${Number(
                  approxWeight * quantity
               ).toFixed(3)}</span> Kg</p>
                        <p class="cartSmallText text-danger">Shipping rate: ${currency +
               " " +
               Math.ceil(shippingRate)}</p>
                      </figcaption>
                    </figure>
                  </td>
                  <td class="align-middle" style="min-width: 115px;">
                    ${configure_qty_input(
                  itemCode,
                  BatchLotQuantity,
                  quantity,
                  max,
                  item_unique_id
               )}
                  </td>
                  <td class="align-middle text-center"  style="min-width: 90px;">
                    <div class="price-wrap">
                      <var class="price">${currency} <span class="rate subTotal">${subTotal}</span></var>
                      <p class="text-muted small m-0">${currency} <span class="rate eachRate">${rate}</span> each </p>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <a href="${item_unique_id}" class="removeCart btn btn-light"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>`;
            });

            totalItemPrice = localDelivery + totalItemPrice;
            if (localDelivery) {
               htmlData += `<tr class="${item_id}"><td></td>
                <td class="text-right" colspan="3">China Express Fees:</td>
                <td class="text-center"><var class="price">${currency} <span class="localDelivery">${localDelivery}</span></var></td>
                <td></td>
              </tr>`;
            }
            htmlData += `<tr class="${item_id}"><td></td>
                          <td class="text-right" colspan="3">Sub total</td>
                          <td class="text-center"><var class="price">${currency} <span class="totalItemPrice">${Math.ceil(
               totalItemPrice
            )}</span></var></td>
                          <td></td>
                        </tr>`;
         }

         $(document)
            .find("#shoppingCartTable")
            .find("tbody")
            .html(htmlData);

         calculate_cart_summary(cartList);
      } else {
         Swal.fire({
            text:
               "Your cart is empty. Browse the product and add to cart first!",
            icon: "warning",
            showCancelButton: false,
            confirmButtonText: "Ok, Understood!"
         }).then(result => {
            // window.location.replace("/");
         });
      }
   }

   function calculate_cart_summary(
      cartList = {},
      coupon = 0,
      couponCode = null
   ) {
      const currency = b2b.currency_icon;
      let cartTotal = 0;
      for (const item_id in cartList) {
         let items = cartList.hasOwnProperty(item_id)
            ? cartList[item_id]
            : [];
         items = items.filter(filterItems => filterItems.is_check === true);
         let subTotal = 0;
         let localDelivery = 0;
         let totalItemPrice = 0;

         items.map(product => {
            let quantity = product.hasOwnProperty("quantity")
               ? Number(product.quantity)
               : 0;
            let price = product.hasOwnProperty("price")
               ? Number(product.price)
               : 0;
            localDelivery = product.hasOwnProperty("localDelivery")
               ? Number(product.localDelivery)
               : 0;
            subTotal = price * quantity; // single item subtotal
            totalItemPrice += subTotal; // cart subtotal
         });
         cartTotal += localDelivery + totalItemPrice;
      }

      let couponMergeTotal = coupon ? cartTotal - coupon : cartTotal;

      let needToPay = couponMergeTotal / 2;
      needToPay = Math.ceil(needToPay);
      let dueForProducts = couponMergeTotal / 2;
      dueForProducts = Math.ceil(dueForProducts);

      let dom = $(document);
      let couponRow = dom.find("#couponRow");

      dom.find("#productTotalPrice").text(
         `${currency} ` + Math.ceil(cartTotal)
      );
      dom.find("#coupon_cartTotal").val(Math.ceil(cartTotal)); // coupon form cart total
      dom.find("#needToPay").text(`${currency} ` + needToPay);
      dom.find("#dueForProducts").text(`${currency} ` + dueForProducts);

      let summary = {
         productTotal: Math.ceil(cartTotal),
         couponDiscount: null,
         couponCode: null,
         needToPay: needToPay,
         dueForProducts: dueForProducts
      };

      if (coupon) {
         summary.couponDiscount = Math.ceil(coupon);
         summary.couponCode = couponCode;
         couponRow.show();
      } else {
         couponRow.hide();
      }

      dom.find("#couponVictory").text(`${currency} ` + Math.ceil(coupon));

      window.localStorage.setItem("_summary", JSON.stringify(summary));
   }

   // ================= start manage coupon ========================= //

   function reload_cart_summary(amount, couponCode) {
      let button = $("#couponApplyForm").find("button");
      button
         .attr("type", "button")
         .text("Remove")
         .addClass("resetCoupon")
         .addClass("btn-danger")
         .removeClass("applyCoupon")
         .removeClass("btn-primary");
      let cartList = mybdcart.list();
      cartList = cart_list_arranging_by_item(cartList);
      calculate_cart_summary(cartList, amount, couponCode);
   }

   function resetCoupon() {
      let button = $("#couponApplyForm").find("button");
      button
         .attr("type", "submit")
         .text("Apply")
         .addClass("applyCoupon")
         .addClass("btn-primary")
         .removeClass("resetCoupon")
         .removeClass("btn-danger");
      $("#coupon_code")
         .removeAttr("disabled")
         .val("");
      let cartList = mybdcart.list();
      cartList = cart_list_arranging_by_item(cartList);
      calculate_cart_summary(cartList);
      // console.log('resetCoupon', cartList);
   }

   $("body")
      .on("click", "#proceedButton", function (event) {
         event.preventDefault();
         let proceedLink = $(this).attr("href");
         validateCartProducts(proceedLink);
      })
      .on("submit", "#couponApplyForm", function (event) {
         event.preventDefault();
         let checkBtn = $(this).find(".btn-fill-out");
         checkBtn.removeAttr("disabled");
         let coupon = $(this).find("#coupon_code");
         $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            beforeSend: function () {
               show_loader();
            },
            success: function (htmlData) {
               if (htmlData.status) {
                  coupon.attr("disabled", "disabled");
                  reload_cart_summary(htmlData.amount, coupon.val());
               } else {
                  Swal.fire({
                     icon: "warning",
                     text: "Coupon is not valid"
                  });
               }
            },
            error: function (xhr) {
               console.log("error", xhr);
            },
            complete: function () {
               hide_loader();
            }
         });
      })
      .on("click", ".resetCoupon", function (e) {
         e.preventDefault();
         resetCoupon();
      });

   // ================= end manage coupon ========================= //

   function calculate_check_uncheck_item() {
      let tbody = $("#product_list_item");
      let check_all = $("#checkbox_all");
      let total_length = tbody.find(".checkbox_item").length;
      let checked_length = tbody.find(".checkbox_item:checked").length;
      if (total_length == checked_length && checked_length > 0) {
         check_all.prop("checked", true);
      } else {
         check_all.prop("checked", false);
      }
   }

   /**
    *
    * @param {true/false} is_checked
    */
   function checked_unchecked_items(is_checked) {
      let cartList = mybdcart.list();
      cartList = cartList.filter(filCart => filCart.is_cart === true);
      cartList.map(product => {
         mybdcart.update(product.id, "is_check", is_checked);
      });
   }

   $("body")
      .on("change", "#checkbox_all", function (e) {
         e.preventDefault();
         let check_item = $(this)
            .closest("table")
            .find(".checkbox_item");
         let is_checked = $(this).is(":checked");
         if (is_checked) {
            check_item.prop("checked", true);
         } else {
            check_item.prop("checked", false);
         }
         checked_unchecked_items(is_checked);
      })
      .on("change", ".checkbox_item", function (e) {
         e.preventDefault();
         const checkbox_item = $(this).val();
         const is_checked = $(this).is(":checked");
         mybdcart.update(checkbox_item, "is_check", is_checked);
         calculate_check_uncheck_item();
      });

   let page = dom.find(".main_content").attr("data-page");
   if (page === "product") {
      load_additional_info();
   } else if (page === "shopCart") {
      loaded_checkout_cart(); // load the default checkout cart
   }

   function renderCart() {
      let itemList = mybdcart.list();
      if (page === "product") {
         // // update_customer_checkout(); // off for new testing
         // // loadSellerInformationAjax(); // auto load seller info load by ajax
         append_from_china_to_bd();
      } else if (page === "shopCart") {
         calculate_check_uncheck_item();
         update_customer_checkout();
      }

      calculate_cart_item();
   }

   renderCart();
   mybdcart.onChange(renderCart);
})(jQuery);
