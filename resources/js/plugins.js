/**
 * Allows you to add data-method="METHOD to links to automatically inject a form
 * with the method on click
 *
 * Example: <a href="{{route('customers.destroy', $customer->id)}}"
 * data-method="delete" name="delete_item">Delete</a>
 *
 * Injects a form with that's fired on click of the link with a DELETE request.
 * Good because you don't have to dirty your HTML with delete forms everywhere.
 */

/**
 * Place any jQuery/helper plugins in here.
 */
(function ($) {

   $("body").on('click', 'a[data-method=delete]', function (event) {
      event.preventDefault();
      const button = $(this);
      const title = button.attr('data-trans-title');
      const confirm = button.attr('data-trans-button-confirm');
      const cancel = button.attr('data-trans-button-cancel');
      const href = button.attr('href');
      const token = $('meta[name=csrf-token]').attr('content');
      Swal.fire({
         icon: 'warning',
         text: title,
         showCancelButton: true,
         confirmButtonText: confirm,
         cancelButtonText: cancel
      }).then((result) => {
         if (result.value) {
            $.ajax({
               type: "POST",
               url: href,
               data: {_method: 'delete', _token: token},
               success: function (res) {
                  if (res.status) {
                     Swal.fire({
                        icon: res.icon,
                        text: res.msg,
                     });
                     button.closest('tr').remove();
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

   }).on('click', 'a[data-method=show]', function (event) {
      event.preventDefault();
      const action = $(this).attr('href');
      const token = $('meta[name=csrf-token]').attr('content');
      const details = $('#details_loading');
      $.ajax({
         url: $(this).attr('href'),
         method: 'POST',
         data: {_token: token},
         success: function (res) {
            if (res.status) {
               details.find('.modal-title').text(res.title);
               details.find('.modal-body').html(res.render);
               details.modal('show');
            } else {
               Swal.fire({
                  icon: 'error',
                  text: 'loading fails',
               });
            }
         },
         error: function (xhr) {
            console.error('Error:', xhr);
         }


      });
   }).on('click', '.noticeButton', function () {
      const notice = $(this).attr('data-notice');
      const token = $('meta[name=csrf-token]').attr('content');
      $.ajax({
         url: '/ajax/DK4iSC8EJDqU6xtKgZRvilrraTQjlxwS9sN',
         method: 'POST',
         data: {notice: notice, _token: token}
      }).done(function (store_response) {
         console.log(store_response);
      }).fail(function (response) {
         console.log(response);
      });
   });


   $('[data-toggle="tooltip"]').tooltip();

   $('.custom-carousel .carousel-item').css('min-height', ($(window).height() * 0.80));
   $('.custom-carousel .carousel-caption').css('top', ($(window).height() * 0.80 * 0.25));

   $('.plan-features p').prepend('<span class="mr-2"><i class="fas fa-check"></i></span>');

})(jQuery);
