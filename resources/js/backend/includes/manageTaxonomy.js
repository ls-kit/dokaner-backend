import Axios from "axios";
import _ from "lodash";
import Swal from "sweetalert2";

function select_item_warning() {
    Swal.fire({
        icon: "warning",
        text: "Please select the item first"
    });
}

(function($) {
    function parse_categories() {
        let categories = $("#mainCategories").text();
        if (categories) {
            categories = JSON.parse(categories);
        }
        return _.isArray(categories) && !_.isEmpty(categories)
            ? categories
            : [];
    }

    function load_main_categories() {
        const categories = parse_categories();
        const category = $("#category");
        const active = category.attr("data-active");
        let option = `<option value="">- Select --</option>`;
        option += categories.map(item => {
            return `<option value="${item.otc_id}" ${
                active === item.otc_id ? 'selected="selected"' : ""
            } >${item.name}</option>`;
        });
        $("#category").html(option);
        if (active) {
            load_sub_categories(active);
        }
    }

    function load_sub_categories(item_id) {
        const categories = parse_categories();
        const category = categories.find(f => f.otc_id === item_id);
        const children =
            !_.isEmpty(category) && _.isObject(category)
                ? category.children
                : [];
        if (!_.isEmpty(children) && _.isArray(children)) {
            const subCategory = $("#subCategory");
            const active = subCategory.attr("data-active");
            let option = `<option value="">- Select --</option>`;
            option += children.map(item => {
              return `<option value="${item.otc_id}" ${
                  active === item.otc_id ? 'selected="selected"' : ""
              } >${item.name}</option>`;
            });
            subCategory.html(option);
        }
    }

    $("#category").on("change", function() {
        const item_id = $(this).val();
        load_sub_categories(item_id);
    });

    load_main_categories();

    // action operations

    function clear_selected_ids() {
        $("input[type=checkbox]").prop("checked", false);
    }

    function get_selected_ids() {
        const checkboxItem = $("input.checkboxItem:checked");
        const product_ids = [];
        checkboxItem.each(function(index) {
            var item_id = $(this).val();
            product_ids.push(item_id);
        });
        return product_ids;
    }

    function toggle_as_top(make = true) {
        var selected = get_selected_ids();
        let isTop = '<span class="badge badge-danger">No</span>';
        if (make) {
            isTop = '<span class="badge badge-success">Yes</span>';
        }
        if (_.isArray(selected)) {
            selected.map(item => {
                $(`#${item}`)
                    .find(".is_top")
                    .html(isTop);
            });
            clear_selected_ids();
        }
    }

    function toggle_as_active(active = true) {
        var selected = get_selected_ids();
        let isActive =
            '<span class="badge badge-danger" title="Inactive">No</span>';
        if (active) {
            isActive =
                '<span class="badge badge-success" title="Active">Yes</span>';
        }
        if (_.isArray(selected)) {
            selected.map(item => {
                $(`#${item}`)
                    .find(".active")
                    .html(isActive);
            });
            clear_selected_ids();
        }
    }

    function remove_deleted_item() {
        var selected = get_selected_ids();
        if (_.isArray(selected)) {
            selected.map(item => {
                $(`#${item}`).remove();
            });
            clear_selected_ids();
        }
    }

    $("body")
        .on("click", ".markAsTop", function(e) {
            e.preventDefault();
            var selected = get_selected_ids();
            if (selected.length > 0) {
                Swal.fire({
                    icon: "warning",
                    text: "Are you sure to proceed ?",
                    showCancelButton: true,
                    confirmButtonText: "confirm",
                    cancelButtonText: "cancel"
                }).then(result => {
                    if (result.value) {
                        let dataTop = $(this).attr("data-top");
                        dataTop = parseInt(dataTop);
                        Axios.post("/admin/taxonomy/make-top", {
                            ids: JSON.stringify(selected),
                            top: dataTop
                        })
                            .then(response => {
                                const resData = response.data;
                                if (!_.isEmpty(resData)) {
                                    if (resData.status) {
                                        Swal.fire({
                                            icon: "success",
                                            text: resData.msg
                                        });
                                        toggle_as_top(dataTop);
                                    }
                                }
                            })
                            .then(error => {
                                console.log(error.error);
                            })
                            .then(() => {
                                console.log("remove loader");
                            });
                    }
                });
            } else {
                select_item_warning();
            }
        })
        .on("click", ".markAsActive", function(e) {
            e.preventDefault();
            var selected = get_selected_ids();
            if (selected.length > 0) {
                Swal.fire({
                    icon: "question",
                    text: "Are you sure to proceed ?",
                    showCancelButton: true,
                    confirmButtonText: "confirm",
                    cancelButtonText: "cancel"
                }).then(result => {
                    if (result.value) {
                        let dataActive = $(this).attr("data-active");
                        dataActive = parseInt(dataActive);
                        Axios.post("/admin/taxonomy/make-active", {
                            ids: JSON.stringify(selected),
                            active: dataActive
                        })
                            .then(response => {
                                const resData = response.data;
                                if (!_.isEmpty(resData)) {
                                    if (resData.status) {
                                        Swal.fire({
                                            icon: "success",
                                            text: resData.msg
                                        });
                                        toggle_as_active(dataActive);
                                    }
                                }
                            })
                            .then(error => {
                                console.log(error.error);
                            })
                            .then(() => {
                                console.log("remove loader");
                            });
                    }
                });
            } else {
                select_item_warning();
            }
        })
        .on("click", ".makeDelete", function(e) {
            e.preventDefault();
            var selected = get_selected_ids();

            if (selected.length > 0) {
                Swal.fire({
                    icon: "question",
                    title: "Are you sure ?",
                    text: "This will proceed to delete with all children",
                    showCancelButton: true,
                    confirmButtonText: "confirm",
                    cancelButtonText: "cancel"
                }).then(result => {
                    if (result.value) {
                        Axios.post("/admin/taxonomy/make-delete", {
                            ids: JSON.stringify(selected)
                        })
                            .then(response => {
                                const resData = response.data;
                                if (!_.isEmpty(resData)) {
                                    if (resData.status) {
                                        Swal.fire({
                                            icon: "success",
                                            text: resData.msg
                                        });
                                        remove_deleted_item();
                                    }
                                }
                            })
                            .then(error => {
                                console.log(error.error);
                            })
                            .then(() => {
                                console.log("remove loader");
                            });
                    }
                });
            } else {
                select_item_warning();
            }
        });
})(jQuery);
