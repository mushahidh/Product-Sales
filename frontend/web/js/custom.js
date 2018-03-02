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
    $('#order-rquest_customer,#order-child_user').on('change', function() {
        $.post("../user/getuseraddress?id=" + $(this).val(), function(data) {

            var json = $.parseJSON(data);

            $('#order-email').val(json.email);
            $('#order-mobile_no').val(json.mobile_no);
            $('#order-phone_no').val(json.phone_no);
            $('#order-district').val(json.district);
            $('#order-province').val(json.province);
            $('#order-postal_code').val(json.postal_code);
            $('#order-address').val(json.address);
            $('#order-country').val(json.country);


        });
    });
    //this code is to hidden the grid and show for order and request if user login

});