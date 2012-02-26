<?php 
	head(array('title' => 'DigitalNZ', 'bodyclass' => 'primary')); 	
?>

<h1> Digital New Zealand Update </h1>

<div id='primary'>
	<h2> Thank you! </h2>
	
	<p> The items listed below have been updated. Thank you for complying with Digital New Zealand's Terms of Use. </p>
	
	<table class="simple" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Date Added</th>
                <th>DNZ Item</th>
                <th>Item ID</th>
                <th>Collection ID</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
			<?php
				foreach($this->overdue_items as $item)
				{
					echo "<tr>
				 			<td> $item->added </td>
							<td> $item->dnz_id </td>
							<td> $item->item_id </td>
							<td> $item->collection_id </td>
							<td> </td>
					     </tr>";
				}				
			?>
		</tbody>
	</table>
	
	<p> <a href="<?php echo WEB_ROOT.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'items'; ?>"> Return to Items Administration</a></p>
</div>