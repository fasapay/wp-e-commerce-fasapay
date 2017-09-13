<hr />

<table>
<tbody>
<tr>
<td>Nomor Order</td>
<td>: <?php echo filter_input(INPUT_POST, 'order_id'); ?></td>
</tr>
<tr>
<td>Pembayaran Dari</td>
<td>: <?php echo filter_input(INPUT_POST, 'fp_paidby'); ?></td>
</tr>
<tr>
<td>Harga</td>
<td>: <?php echo filter_input(INPUT_POST, 'fp_amnt');?></td>
</tr>
<tr>
<td>Status Transaksi</td>
<td>: SUCCESS !</td>
</tr>
</tbody>
</table>
<hr />
<?php 

	unset($_SESSION['cart']);
	unset($_SESSION['max_cart']); 
?>