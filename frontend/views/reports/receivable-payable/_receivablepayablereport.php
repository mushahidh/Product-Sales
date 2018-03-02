<?php

?>
<div class="row main-container report_div">    
       <div class="row report-view">
       <h2><?=$AccountType?> Report</h2>
      <div class="col-md-12" style="margin-bottom:10px;">
	  <button id="back" class="btn btn-success">Back</button>
	  <div style="float:right;margin-right:20px;">
		 <?php
     $receivable_total = 0;
     $payable_total = 0;
      if(isset($receivable_in_hand) && $AccountType=="Receivable")
      {
			      $receivable_total = $receivable_in_hand;
			?>
			  <span>Receivable In Hand: </span>
			<?php
			echo $receivable_in_hand ;
		}
     if(isset($payable_in_hand) && $AccountType=="Payable")
      {
			      $payable_total = $payable_in_hand;
			?>
			  <span>Payable In Hand: </span>
			<?php
			echo $payable_in_hand;
		}
		?>
	  </div>
	  </div>
	  <div class="col-md-12">
       <table class="table table-bordered">
         <thead>
           <tr>
             <th>Name</th>
             <th>Amount</th>
             <th>Type</th>
             <th>Date</th>
            
           </tr>
         </thead>
         <tbody>
         <?php 
    foreach ($ReceivablePayableArr as $value) {
      if($value->type == 'Receivable'){
        $receivable_total =   $receivable_total + $value->amount;
      }else{
        $payable_total =   $payable_total + $value->amount;
      }
        ?>
    
           <tr>
             <td><?php echo $value->user ?></td>
             <td><?php echo $value->amount ?></td>
             <td><?php echo $value->type  ?></td>
             <td><?php echo $value->date ?></td>
           </tr>
           <?php }?>
         </tbody>

       </table>
       <div style="float:right;margin-right:20px;">
       <?php if($AccountType=="Receivable")
       {
       ?>
        	<span>Total Receivable: </span>
          <?= $receivable_total;?>
       <?php 
       } 
       ?>
      <?php if($AccountType=="Payable")
       {
      ?>
         <span>Total Payable: </span>
		    	<?= $payable_total;?>
    <?php
      }
    ?>
	  </div>
	   </div>
     </div>
    
    <script>
    $( "#back" ).click(function() {
  
    $('.filter').show(1000);
 
    $('.view-report').hide(1000);
});
    </script>