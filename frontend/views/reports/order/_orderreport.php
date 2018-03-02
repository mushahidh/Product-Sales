<?php

?>
<div class="row main-container report_div">    
       <div class="row report-view">
       <h2>Order Report</h2>
      <div class="col-md-12" style="margin-bottom:10px;">
	  <button id="back" class="btn btn-success">Back</button>
	  <div style="float:right;margin-right:20px;">
	
	  </div>
	  </div>
	  <div class="col-md-12">
       <table class="table table-bordered">
         <thead>
           <tr>
             <th>Order By</th>
             <th>Order To</th>
             <th>Product</th>
             <th>Quantity</th>
             <th>Status</th>
             <th>Requested By</th>
             <th>Requested Date</th>
           </tr>
         </thead>
         <tbody>
         <?php 
    foreach ($order as $orders) {

        ?>
    
           <tr>
             <td><?php echo $model->username($orders['user_id']); ?></td>
             <td><?php echo $model->username($orders['order_request_id']); ?></td>
             <td><?php echo $orders['name']; ?></td>        
             <td><?php echo $orders['quantity']; ?></td>        
             <td><?php echo \common\models\Lookup::$status[$orders['status']]; ?></td>
             <td><?php echo $model->username($orders['created_by']) ?></td>             
             <td><?php echo $orders['created_at']; ?></td>             
             
           </tr>
           <?php }?>
         </tbody>

       </table>
	   </div>
     </div>
    
    <script>
    $( "#back" ).click(function() {
  
    $('.filter').show(1000);
 
    $('.view-report').hide(1000);
});
    </script>