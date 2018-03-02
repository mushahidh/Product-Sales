(function() {

    var db_export_vehicle = {
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
                insertingClient.ProductProject = $('.dynamic-field-final-4').val();
            }
            if ($('.dynamic-field-final-5').val()) {
                insertingClient.ProductProject = $('.dynamic-field-final-5').val();
            }
            if ($('.dynamic-field-final-6').val()) {
                insertingClient.ProductProject = $('.dynamic-field-final-6').val();
            }
            if ($('.dynamic-field-final-7').val()) {
                insertingClient.ProductProject = $('.dynamic-field-final-7').val();
            }
            if ($('.dynamic-field-final-8').val()) {
                insertingClient.ProductProject = $('.dynamic-field-final-8').val();
            }
            if ($('.dynamic-field-final-9').val()) {
                insertingClient.ProductProject = $('.dynamic-field-final-9').val();
            }


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
            if ($('.dynamic-field-final-4').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-4').val();
            }
            if ($('.dynamic-field-final-5').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-5').val();
            }
            if ($('.dynamic-field-final-6').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-6').val();
            }
            if ($('.dynamic-field-final-7').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-7').val();
            }
            if ($('.dynamic-field-final-8').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-8').val();
            }
            if ($('.dynamic-field-final-9').val()) {
                updatingClient.ProductProject = $('.dynamic-field-final-9').val();
            }
            console.log(updatingClient);

        },
        deleteItem: function(deletingClient) {
            var clientIndex = $.inArray(deletingClient, this.clients);
            this.clients.splice(clientIndex, 1);
        },


    };

    function checkDuplicate(vin) {
        debugger;
        var already_in_table = false;
        for (var i = 0; i < window.db_export_vehicle.clients.length; i++) {
            if (db_export_vehicle.clients[i].vin == vin) {
                already_in_table = true;
            }
        }
        return already_in_table;
    }
    db_export_vehicle.accounts;

    window.db_export_vehicle = db_export_vehicle;

    db_export_vehicle.clients = [];

}());