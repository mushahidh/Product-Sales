(function() {
    var db_items = {
        loadData: function(filter) {
            return $.grep(this.clients, function(client) {
                return (!filter.DebitAccount || client.DebitAccount.indexOf(filter.DebitAccount) > -1) &&
                    (!filter.Department || client.Department === filter.Department) &&
                    (!filter.FundBranch || client.FundBranch.indexOf(filter.FundBranch) > -1) &&
                    (!filter.ProductProject || client.ProductProject.indexOf(filter.ProductProject) > -1) &&
                    (!filter.Amount || client.Amount === filter.Amount);
            });
        },
        insertItem: function(insertingClient) {
            if ($('.dynamic-field-final-1').val()) {
                insertingClient.DebitAccount = $('.dynamic-field-final-1').val();

            }
            if ($('.dynamic-field-final-2').val()) {
                insertingClient.Department = $('.dynamic-field-final-2').val();
            }
            if ($('.dynamic-field-final-3').val()) {
                insertingClient.FundBranch = $('.dynamic-field-final-3').val();
            }
            if ($('.dynamic-field-final-4').val()) {
                insertingClient.FundBranch = $('.dynamic-field-final-4').val();
            }


            console.log(insertingClient);

            this.clients.push(insertingClient);
        },
        updateItem: function(updatingClient) {
            if ($('#user-user_level_id').val() == null && $('#order-request_agent_name').val() == null) {
                var url = "../user-product-level/getunitsprice?id=" + updatingClient.unit + "&user_level=" + (typeof($('#order-child_level').val()) === "undefined" ? $('#order-all_level').val() : $('#order-child_level').val()) + "&product_id=" + updatingClient.product_id;
            } else if ($('#order-request_agent_name').val() == null) {
                var url = "../user-product-level/getunitsprice?id=" + updatingClient.unit + "&user_level=" + $('#user-user_level_id').val() + "&product_id=" + updatingClient.product_id;
            } else {
                url = "../product/get-product?id=" + $('#order-product_id').val();
            }
            $.post(url, function(data) {
                if ($('#order-request_agent_name').val()) {
                    var json = data;
                } else {
                    var json = $.parseJSON(data);
                }
                if (json.price) {
                    updatingClient.price = json.price;
                    updatingClient.total_price = "" + parseFloat(updatingClient.unit) * parseFloat(json.price);
                    $("#items_all").jsGrid("loadData");
                }
            });
        },
        deleteItem: function(deletingClient) {
            var clientIndex = $.inArray(deletingClient, this.clients);
            this.clients.splice(clientIndex, 1);
        }

    };
    window.db_items = db_items;

    db_items.clients = [];

}());