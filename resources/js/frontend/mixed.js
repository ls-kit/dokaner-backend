const {
    default: Swal
} = require("sweetalert2");


$(document).on('submit', '#SubscribeEmailForm', function (event) {
    event.preventDefault();
    var email = $(this).find('input[name="email"]');
    $.ajax({
        url: '/ajax/LZPhsFSUice8hB5rYzNHuLZmmG5pJYcdNeH',
        method: 'POST',
        data: {
            email: email.val()
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (store_response) {
        if (store_response.status) {
            Swal.fire({
                text: "Thanks for your email subscription",
                icon: 'success',
            });
        }
        email.val('');
    }).fail(function (response) {
        console.log(response);
    });
});


$(document).on('click', '.noticeButton', function () {
    var notice = $(this).attr('data-notice');
    $.ajax({
        url: '/ajax/DK4iSC8EJDqU6xtKgZRvilrraTQjlxwS9sN',
        method: 'POST',
        data: {
          notice: notice
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (store_response) {
      console.log(store_response);
    }).fail(function (response) {
        console.log(response);
    });
});



$(document).on('keyup', '.searchField', function(e){
  var search = $(this).val();
  var textBox = $(document).find('.searchSuggestText');
  if(search.length > 3){
    textBox.show();
  }else{
    textBox.hide();
  }   
});




