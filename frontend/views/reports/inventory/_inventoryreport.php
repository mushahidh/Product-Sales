<?php

?>
<div class="row main-container report_div">    
  <div class="row report-view">
      <h2>Inventory Report</h2>
      <div class="col-md-12" style="margin-bottom:10px;">
        <button id="back" class="btn btn-success">Back</button>
          <div style="float:right;margin-right:20px;">
            <?php
            $stock_total = 0;
                if(isset($stock_in_hand))
                {
              $stock_total = $stock_in_hand;
              ?>
              <span>Stock In Hand: </span>
              <?php
              echo $stock_in_hand ;
            }
            ?>
          </div>
    </div>
    <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Product</th>
          </tr>
        </thead>
        <tbody>
        <?php 
          foreach ($inventoryArr as $value) {
            if($value->type == 'Stock In'){
              $stock_total =   $stock_total + $value->quantity;
            }else{
              $stock_total =    $stock_total - $value->quantity;  
            }
              ?>
                <tr>
                  <td><?php echo $value->user ?></td>
                  <td><?php echo $value->date ?></td>
                  <td><?php echo $value->quantity  ?></td>
                  <td><?php echo $value->type ?></td>
                  <td><?php echo $value->product ?></td>             
                </tr>
                <?php }?>
        </tbody>
      </table>
      <div style="float:right;margin-right:20px;">
        <span>Remaining Stock: </span>
        <?=  $remaining_stock ;?>
      </div>
  </div>
</div>
    
    <script>
    $( "#back" ).click(function() {
  
    $('.filter').show(1000);
 
    $('.view-report').hide(1000);
});
</script>