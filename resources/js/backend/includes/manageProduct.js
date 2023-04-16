function remove_space(stringData) {
  return stringData
     .trim() // remove white spaces at the start and end of string
     // .toLowerCase() // string will be lowercase
     .replace(/^-+/g, "") // remove one or more dash at the start of the string
     .replace(/[^\w-]+/g, "-") // convert any on-alphanumeric character to a dash
     .replace(/-+/g, "-") // convert consecutive dashes to singular one
     .replace(/-+$/g, "");
};


(function ($) {


  let body = $("body");


  body.on('change', '#allSelectCheckbox', function () {
     var tbodyCheckbox = $('tbody').find('input.checkboxItem');
     if ($(this).is(':checked')) {
        tbodyCheckbox.prop("checked", true);
     } else {
        tbodyCheckbox.prop("checked", false);
     }

  }).on('change', 'input.checkboxItem', function () {
     var checked_item = $('input.checkboxItem:checked').length;
     var uncheck_item = $('input.checkboxItem:not(":checked")').length;

     if (uncheck_item == 0) {
        $('#allSelectCheckbox').prop("checked", true);
     } else {
        $('#allSelectCheckbox').prop("checked", false);
     }
  }).on('click', '.process_multiple_delete', function (e) {
     e.preventDefault();
     var checkboxItem = $('input.checkboxItem:checked');
     var data_table = $(this).attr('data-table');
     var permanent = $(this).attr('data-permanent');
     if (checkboxItem.length) {
        Swal.fire({
           icon: 'warning',
           text: 'Are you sure to delete selected items ?',
           showCancelButton: true,
           confirmButtonText: 'confirm',
           cancelButtonText: 'cancel'
        }).then((result) => {
           if (result.value) {
              var token = $('meta[name=csrf-token]').attr('content');
              var product_ids = [];
              checkboxItem.each(function (index) {
                 var item_id = $(this).val();
                 product_ids.push(item_id);
              });

              $.ajax({
                 type: "POST",
                 url: '/admin/product/multi-delete',
                 data: {
                    product_ids: product_ids,
                    data_table: data_table,
                    permanent: permanent,
                    _token: token
                 },
                 success: function (res) {
                    if (res.status) {
                       Swal.fire({
                          icon: res.icon,
                          text: res.msg,
                       });
                       checkboxItem.each(function (index) {
                          var del_id = $(this).val();
                          $('tr#' + del_id).remove();
                       });

                    } else {
                       Swal.fire({
                          icon: res.icon,
                          text: res.msg,
                       });
                    }
                 },
                 error: function (xhr) {
                    console.error('Error:', xhr);
                 }
              });
           }
        });


     } else {

        Swal.fire({
           icon: 'warning',
           text: 'First select items.',
        });

     }


  });


})(jQuery);
