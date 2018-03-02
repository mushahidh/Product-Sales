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



            console.log(insertingClient);

            this.clients.push(insertingClient);
        },
        updateItem: function(updatingClient) {
            if ($('.dynamic-field-final-1').val()) {
                updatingClient.DebitAccount = $('.dynamic-field-final-1').val();
            }
            if ($('.dynamic-field-final-2').val()) {
                updatingClient.Department = $('.dynamic-field-final-2').val();
                console.log(updatingClient.Department);
            }
            if ($('.dynamic-field-final-3').val()) {
                updatingClient.FundBranch = $('.dynamic-field-final-3').val();
            }




        },
        deleteItem: function(deletingClient) {
            var clientIndex = $.inArray(deletingClient, this.clients);
            this.clients.splice(clientIndex, 1);
        }

    };
    window.db_items = db_items;

    db_items.clients = [];

}());