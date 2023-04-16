import {
    loadingSpinner
} from './cartHelpers';

function loadItems(offset, limit, reload = 0) {
    let params = JSON.parse($('.searchParameters').text());
    var productContainer = $("#productContainer");
    var spinner = loadingSpinner();

    var data = {
        search: params.search,
        type: params.type,
        pathTime: params.pathTime,
        image: params.image,
        offset: offset,
        limit: limit
    }

    setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: params.route,
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                if (!offset) {
                    productContainer.html(spinner);
                } else {
                    productContainer.append(spinner);
                }
            },
            success: function (response) {
                if (response.status === false) {
                    if (reload < 3) {
                        loadItems(offset, limit, reload + 1);
                    } else {
                        productContainer.html('Ops! 404 page not found');
                    }
                } else {
                    productContainer.find('.loadingSpinner').remove();
                    if (response.redirect) {
                        window.location.assign(response.redirect);
                    } else {
                        if (!offset) {
                            productContainer.html(response.data);
                        } else {
                            productContainer.append(response.data);
                        }
                    }
                }
            },
            error: function (xhr) { // if error occured
                if (reload < 4) {
                    loadItems(offset, limit, reload + 1);
                } else {
                    productContainer.html('Ops! 404 page not found');
                }
            },
            complete: function () {
                $("img.b2bLoading").Lazy();
                if (productContainer.find('.item').length > 1) {
                    $('.loadMoreBlock').html(`<button type="button" class="btn btn-light loadMoreBtn">Load More</button>`);
                } else {
                    $(document).find('.item a').trigger('click');
                }
            }
        });
    }, 100);
}



function loadCategoryItems(offset, limit, reload = 0) {
    let params = JSON.parse($('.searchParameters').text());
    var productContainer = $("#productContainer");
    var spinner = loadingSpinner();
    var data = {
        cat_id: params.cat_id,
        subcat_id: params.subcat_id,
        offset: offset,
        limit: limit
    };
    $.ajax({
        type: 'POST',
        url: params.route,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            if (!offset) {
                productContainer.html(spinner);
            } else {
                productContainer.append(spinner);
            }
        },
        success: function (res) {
            if (res.status === false) {
                if (reload < 3) {
                    loadItems(offset, limit, reload + 1);
                } else {
                    productContainer.html('Ops! 404 page not found');
                }
            } else {
                productContainer.find('.loadingSpinner').remove();
                if (!offset) {
                    productContainer.html(res.data);
                } else {
                    productContainer.append(res.data);
                }
                $("img.b2bLoading").Lazy();
            }
        },
        error: function (xhr) { // if error occured
            if (reload < 4) {
                loadItems(offset, limit, reload + 1);
            } else {
                productContainer.html('Ops! 404 page not found');
            }
        },
        complete: function () {
            if (productContainer.find('.item').length > 1) {
                $('.loadMoreBlock').html(
                    `<button type="button" class="btn btn-light loadMoreBtn">Load More</button>`
                );
            }
        }
    });

}


$(function () {
    $("html, body").animate({
        scrollTop: 0
    }, 600);

    let $searchPage = $('[data-page="search"]').length;
    let $categoryPage = $('[data-page="category"]').length;

    if ($searchPage) {
        loadItems(0, 36);
    }

    if ($categoryPage) {
        loadCategoryItems(0, 36);
    }


    $(document).on('click', '.loadMoreBtn', function () {
        var offset = $("#productContainer").find('.item').length;
        if ($categoryPage) {
            loadCategoryItems(offset, 36);
        }
        if ($searchPage) {
            loadItems(offset, 36);
        }
        $(this).remove();
    });



})
