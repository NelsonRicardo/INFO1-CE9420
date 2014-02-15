<?php
	$inv_id = $_REQUEST['inv_id'];
	$lf_id = $_REQUEST['lf_id'];
	$inv_num = $_REQUEST['inv_num'];
	$inv_date = $_REQUEST['inv_date'];
	$client_id = $_REQUEST['client_id'];
	$lf_matt_id = $_REQUEST['lf_matt_id'];
	$clnt_matt_id = $_REQUEST['clnt_matt_id'];
	$inv_tot = $_REQUEST['inv_tot'];
	$from_date = $_REQUEST['from_date'];
	$thru_date = $_REQUEST['thru_date'];
	$inv_desc = $_REQUEST['inv_desc'];
	$sort = $_REQUEST['sort'];

	if ($inv_id || $_POST || $_GET) // filters fed in from somewhere
	{
		$title_tag = 'Filtered and/or Sorted Invoice List';
	}
	else // no filters sent
	{
		$title_tag = 'All Invoices';
	}

	require 'page_header.php';
?>
		<div class='search'>
			<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Enter filters:</legend>
					<small>Except for system invoice id, invoice total, and dates, searches will be made within the field for any matching portion.</small>
					<hr>					
					<table>
						<tr>
							<td><label>System Invoice ID: </label></td>
							<td><input type="text" name="inv_id" value="<?php echo $inv_id; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Law Firm ID: </label></td>
							<td><input type="text" name="lf_id" value="<?php echo $lf_id; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Invoice Number: </label></td>
							<td><input type="text" name="inv_num" value="<?php echo $inv_num; ?>"></td>
						</tr>
						<tr>
							<td><label>Invoice Date: </label></td>
							<td><input type="text" name="inv_date" value="<?php echo $inv_date; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Client ID: </label></td>
							<td><input type="text" name="client_id" value="<?php echo $client_id; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Law Firm Matter ID: </label></td>
							<td><input type="text" name="lf_matt_id" value="<?php echo $lf_matt_id; ?>"></td>
						</tr>
						<tr>
							<td><label>Client Matter ID: </label></td>
							<td><input type="text" name="clnt_matt_id" value="<?php echo $clnt_matt_id; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Invoice Total: </label></td>
							<td><input type="text" name="inv_tot" value="<?php echo $inv_tot; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Invoice From Date: </label></td>
							<td><input type="text" name="from_date" value="<?php echo $from_date; ?>"></td>
						</tr>
						<tr>
							<td><label>Invoice Thru Date: </label></td>
							<td><input type="text" name="thru_date" value="<?php echo $thru_date; ?>"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><label>Invoice Description: </label></td>
							<td><input type="text" name="inv_desc" value="<?php echo $inv_desc; ?>"></td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td><input type="submit" value="Search Invoices"></td>
							<td colspan="7">&nbsp;</td>
						</tr>
					</table>
				</fieldset>
			</form>
		</div>
		<p><a href="project.php">[ Main Page ]</a> • <a href="all_invoices.php">[ List All Invoices ]</a></p>
<?php require 'invoice_list.php'; ?>
		<p><a href="project.php">[ Main Page ]</a> • <a href="all_invoices.php">[ List All Invoices ]</a></p>
	</body>
</html>