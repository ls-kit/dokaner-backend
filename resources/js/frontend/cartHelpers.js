let cart_key = b2b.cart_key;
cart_key = cart_key ? cart_key : '_b2b';

/**
 *
 * @param cartList
 * @returns {*}
 */
export const cart_list_arranging_by_item = (cartList = []) => {
   let sortedList = cartList.sort((a, b) => a.id > b.id);

   // let productList = _.groupBy(sortedList, function (item) {
   //    return item.product_id;
   // });

   return sortedList.reduce(function (groups, item) {
      groups[item.product_id] = groups[item.product_id] || [];
      groups[item.product_id].push(item);
      return groups;
   }, {});
}


export const customer_address = () => {
   let address = window.localStorage.getItem("_shipping_address");
   if (address && typeof address !== "undefined" && address) {
      return JSON.parse(address);
   }
   return {};
};

export const update_customer_address = address => {
   window.localStorage.setItem("_shipping_address", JSON.stringify(address));
};


export const remove_space = stringData => {
   return stringData
      .trim() // remove white spaces at the start and end of string
      .toLowerCase() // string will be lowercase
      .replace(/^-+/g, "") // remove one or more dash at the start of the string
      .replace(/[^\w-]+/g, "-") // convert any on-alphanumeric character to a dash
      .replace(/-+/g, "-") // convert consecutive dashes to singular one
      .replace(/-+$/g, "");
};


export const loadingSpinner = () => {
   return `<div class="my-5 w-100 text-center loadingSpinner"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>`;
};

export const loadingIcon = () => {
   return `<div class="text-center w-100"><div class="spinner-border text-muted" style="width: 22px; height: 22px"></div></div>`;
};

export const loadingText = () => {
   return `<div class="text-center w-100"><div class="spinner-border text-muted" style="width: 22px; height: 22px"></div><p style="margin:0;">Loading information, please wait...</p></div>`;
};

export const loadingFail = () => {
   return `<div class="text-center w-100 text-danger"><p style="margin:0;">Loading fail, please try again</p></div>`;
};

export const random = length => {
   var result = "";
   var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
   var charactersLength = characters.length;
   for (var i = 0; i < length; i++) {
      result += characters.charAt(
         Math.floor(Math.random() * charactersLength)
      );
   }
   return result;
};

export const slugify = (text, separator = "-") => {
   return text
      .toString()
      .normalize("NFD") // split an accented letter in the base letter and the acent
      .replace(/[\u0300-\u036f]/g, "") // remove all previously split accents
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9 ]/g, "") // remove all chars not letters, numbers and spaces (to be replaced)
      .replace(/\s+/g, separator);
};

export const loadingWebsite = () => {
   var loaded = $(document).find(".loaded");
   loaded.css("display", "");
   loaded.addClass("preloader");
   loaded.removeClass("loaded");
};

export const loadingOutWebsite = () => {
   $(document).find(".preloader").delay(300).fadeOut(300).addClass("loaded");
};


