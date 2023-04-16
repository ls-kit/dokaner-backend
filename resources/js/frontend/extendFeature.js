import Swal from "sweetalert2";
import {
   loadingWebsite,
   loadingOutWebsite
} from "./cartHelpers";

$(function () {


   $(document).on('click', '.pictureSearchBtn', function () {
      $(document).find('#pictureSearch').trigger('click');
   });

   $(document).on('click', '.categorySidebarClose', function () {
      $(this).closest('.navbar').removeClass('show');
   });

   $('#pictureSearch').on('change', function () {
      $(this).closest('form').submit();
   });


   $('#pictureSearchForm').on('submit', function (event) {
      var pictureForm = $(this);

      $.ajax({
         url: pictureForm.attr("action"),
         type: pictureForm.attr("method"),
         dataType: "JSON",
         data: new FormData(pictureForm[0]),
         processData: false,
         contentType: false,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function () {
            loadingWebsite();
         },
         success: function (resData) {
            if (resData.status) {
               window.location.href = resData.href;
            } else {
               Swal.fire({
                  icon: 'error',
                  text: resData.message
               });
            }
         },
         error: function (xhr) {
            console.log(xhr);
         },
         complete: function () {
            loadingOutWebsite()
         }
      });
      event.preventDefault();
   }); // pictureSearch event

});
