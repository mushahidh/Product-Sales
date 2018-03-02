
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    <h3> <?=Yii::t('app', 'Order Summary')?></h3><br>
        <div class="table-responsive">
        <table class="table table-hover invoice-table">
            <thead>
                <tr>
                    <td class="text-center">Date/Time</td>
                    <td class="text-center">Location</td>
                    <td class="text-center">Status</td>
                    <td class="text-center">Remark</td>
                </tr>
            </thead>
                <tbody>
                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                <?php
                if(sizeof($order_history)>0)
                {
                    foreach ($order_history as $history) {
                    ?>
                        <tr>
                            <td  class="text-center"><?=$history->date_time;?></td>
                            <td class="text-center"><?=$history->location?></td>
                            <td class="text-center"><?=$history->status?></td>
                            <td class="text-center"><?=$history->remark?> </td>
                        </tr>
                <?php
                    }
                 }
                 else
                 {?>
                    <tr>
                        <td class="text-center" colspan="4">No order history found.</td>
                    </tr>
                <?php }
                 ?>
                </tbody>
            </table>
        </div>
    </div>
</div>