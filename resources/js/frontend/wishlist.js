const {
    default: Swal
} = require("sweetalert2");

$(document).on('click', '.add_wishlist', function (event) {
    event.preventDefault();
    var storeUrl = $(this).attr('href');
    var item_id = $(this).attr('data-id');

    var exists_wishlist = $(this).attr('data-status');
    var isAuth = $(this).attr('data-auth');
    var addWishList = true;

    if (!isAuth) {
        addWishList = false;
        Swal.fire({
            icon: 'info',
            text: 'Please Login First!'
        });
    }

    if (exists_wishlist) {
        addWishList = false;
        Swal.fire({
            icon: 'info',
            text: 'Already Added to Wishlist'
        });
    }

    if (addWishList) {
        $.ajax({
            url: storeUrl,
            method: 'POST',
            data: {
                item_id: item_id
            },
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (resData) {

            check_wishlist(resData.total_w_list);
            Swal.fire({
              icon: 'success',
              text: 'Successfully added to Wishlist'
          });

        }).fail(function (response) {
            console.log('error', response);
        });
    }
    // $(this).addClass('text-danger')
});



function check_wishlist(resData = 0) {
    if (resData) {
        $(document).find('.wishlistCount').text(resData);
    } else {
        $.ajax({
            url: '/wishlist/count-wishlist',
            method: 'POST',
            data: {},
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (resData) {
            $(document).find('.wishlistCount').text(resData.total_w_list);

        }).fail(function (response) {
            console.log('error', response);
        });
    }
}




$(function () {
    if (isAuth) {
        check_wishlist();
    }
});
