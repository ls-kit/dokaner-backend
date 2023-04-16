import {
   loadingWebsite,
   loadingOutWebsite
} from "./cartHelpers";

(function ($) {

   function replace_phone_prefix(phone) {
      let ItemOne = phone.replace(/^(?:\+?88|0088)/, "");
      return ItemOne.replace(/\s+/g, '');
   }

   function checkValidPhone(inputPhone = null) {
      const phoneSelect = $('input[name="phone"]');
      const phone = inputPhone ? inputPhone : phoneSelect.val();
      const generatePhone = replace_phone_prefix(phone);
      const method = "^(?:\\+?88|0088)?01[15-9]\\d{8}$";
      const regExpression = new RegExp(method);
      if (regExpression.test(generatePhone)) {
         phoneSelect.removeClass('is-invalid').addClass('is-valid');
         return true;
      } else {
         phoneSelect.removeClass('is-valid').addClass('is-invalid');
      }
      return false;
   }

   function countDownResendOtp() {
      let timeLeft = 30;
      const elem = $('#otpCodeSubmitBtn');
      elem.attr('disabled', 'disabled');
      const timerId = setInterval(countdown, 1000);

      function countdown() {
         if (timeLeft === -1) {
            clearTimeout(timerId);
            doSomething();
         } else {
            elem.text('Resend Code ' + timeLeft);
            timeLeft--;
         }
      }

      function doSomething() {
         elem.removeAttr('disabled');
         elem.text('Resend Code');
      }
   }

   function ajaxSendOtpForVerification(phoneNumber) {
      $.ajax({
         type: 'POST',
         url: '/ajax/login-with-otp',
         data: {
            phone: phoneNumber
         },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function () {
            loadingWebsite();
         },
         success: function (response) {
            if (response.status) {
               $('.loginSubmitCard').addClass('d-none');
               $('.otpSubmitCard').removeClass('d-none');
               let otp_submit_form = $('.otp_submit_form');
               otp_submit_form.find('.userPhone').val(response.phone);
               otp_submit_form.find('.userId').val(response.user_id);
               otp_submit_form.find('.otp_code').val('').trigger('focus');
               countDownResendOtp();
            } else {
               Swal.fire({
                  text: 'OTP Sending Fail. Please try again.'
               });
            }
         },
         error: function (xhr) {
            console.log('otp proceed', xhr)
         },
         complete: function () {
            loadingOutWebsite();
         }
      });
   }

   $("body").on('click', '#withOtpLogin', function () {
      $(this).removeClass('btn-outline-success').addClass('btn-fill-out');
      $('#withEmailLogin').removeClass('btn-fill-out').addClass('btn-outline-success');
      $('.loginWithOtp').removeClass('d-none');
      $('.loginWithEmail').addClass('d-none');

   }).on('click', '#withEmailLogin', function () {
      $(this).removeClass('btn-outline-success').addClass('btn-fill-out');
      $('#withOtpLogin').removeClass('btn-fill-out').addClass('btn-outline-success');

      $('.loginWithOtp').addClass('d-none');
      $('.loginWithEmail').removeClass('d-none');

   }).on('keyup', 'input[name="phone"]', function () {
      let phone = $(this).val();
      checkValidPhone(phone);

   }).on('click', '#otpSubmitBtn', function () {
      let phoneNumber = $('input[name="phone"]').val();
      let checkPhone = checkValidPhone(phoneNumber);
      if (checkPhone) {
         ajaxSendOtpForVerification(phoneNumber);
      } else {
         Swal.fire({
            text: 'Phone Number is Not Valid!'
         });
      }

   }).on('click', '#backToLoginCard', function () {
      $('.loginSubmitCard').removeClass('d-none');
      $('.otpSubmitCard').addClass('d-none');

   }).on('click', '#otpCodeSubmitBtn', function () {
      let phoneNumber = $(document).find('.userPhone').val();
      let checkPhone = checkValidPhone(phoneNumber);
      if (checkPhone) {
         ajaxSendOtpForVerification(phoneNumber);
      } else {
         Swal.fire({
            text: 'Phone Number is Not Valid!'
         });
      }

   }).on('keyup', 'input[name="otp_code"]', function () {
      let product_cart = mybdcart.list();
      let otp_code = $(this).val();
      let userId = $(this).closest('.form-group').find('.userId').val();
      let userPhone = $(this).closest('.form-group').find('.userPhone').val();
      if (otp_code.length === 4) {
         $.ajax({
            type: 'POST',
            url: '/ajax/otp-code-verify',
            data: {
               otp_code: otp_code,
               userPhone: userPhone,
               userId: userId
            },
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
               loadingWebsite();
            },
            success: function (response) {
               if (response.status) {
                  if (product_cart.length > 0) {
                     window.location.assign('/shopping-cart');
                  } else {
                     window.location.assign('/dashboard');
                  }
               } else {
                  loadingOutWebsite();
                  Swal.fire({
                     text: 'Phone Number Verification Fail!'
                  });
               }
            },
            error: function (xhr) {
               console.log(xhr);
            },
            complete: function () {
            }
         });
      }
   });


})(jQuery);
