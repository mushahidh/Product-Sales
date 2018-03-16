jQuery(document).ready(function() {
    //hide payment method

    if ($('input[name="Order[payment_method]"][value="3"]').is(':checked')) {
        $('.payment_slip').show();
    } else {
        $('.payment_slip').hide();
    }

    $('#order-payment_method').click(function() {
        if ($('input[name="Order[payment_method]"][value="3"]').is(':checked')) {
            $('.payment_slip').show();
        } else {
            $('.payment_slip').hide();
        }
    });
    $('#order-request_agent_name').on('change', function() {
        $.post("../stock-in/getunits?id=" + $('#order-product_id').val() + "&user_id=" + $(this).val(), function(data) {
            $('#order-orde').val(data);
        });
    });

    //this jis product code

    $("#items_all").jsGrid({
        //height: "70%",
        width: "100%",
        filtering: true,
        editing: true,
        inserting: true,
        sorting: true,
        //paging: true,
        autoload: true,
        //pageSize: 15,
        //pageButtonCount: 5,
        controller: db_items,
        fields: [
            // {name: "item_number", title: "Item Number", id: "item_number", width: "auto", type: "hidden"},
            { name: "unit", title: "Units", type: "text", width: "auto" },
            { name: "price", title: "Price", type: "text", width: "auto", type: "disabled" },
            { name: "total_price", title: "Total Price", type: "text", width: "auto", type: "disabled" },
            { name: "product", title: "Product", type: "text", width: "auto", type: "disabled" },
            { name: "product_id", title: "Product ID", css: "hide", width: 0, type: "disabled" },
            { type: "control" }
        ]
    });
    $('.jsgrid-insert-mode-button').click();

    $('.save-button').click(function(e) {
        if (db_items.clients == '') {
            $('.vehcle_not_found').html('Add Product Order Please');
            e.preventDefault();
            return;
        } else {
            $('#order-hidden').val(JSON.stringify({ order_info: db_items.clients }));

        }
    });
});