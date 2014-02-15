<?php 

	$inv_id = $_REQUEST['inv_id'];

	$title_tag = 'Expense Details for Invoice ID ' . $inv_id;

	require 'page_header.php';

	setlocale(LC_MONETARY, 'en_US'); // for money_format()

	$fmt = '%#0n';  // for money_format();

	$data = array();

	if (!$_REQUEST['sort']) $_REQUEST['sort'] = 'item_num-asc'; // if none provided use item number

	$sort  = explode('-', $_REQUEST['sort']); // split on '-'
	$field = $sort[0]; 
	$seq   = $sort[1];

	require 'db_connect.php'; 

	$query = "select item_num, item_type, item_units, item_rate, item_adj, item_tot, item_date, a.exp_code, item_desc, exp_desc
			  from nr_expenses a left join nr_expense_codes b on a.exp_code = b.exp_code
			  where inv_id = $inv_id
			  order by $field $seq;";

	$cursor = mysqli_query($connect, $query); // execute the query

	if (! $cursor) 
	exit('Could not execute query: ' . mysqli_error($connect) . "<br><br> Query: $query");

	$i = 0;
	$sum = 0;
	while ($row = mysqli_fetch_array($cursor)) // get each row as an array
	{
		$data[$i] = $row; // store row in 2 dim array
		$sum += $row['item_tot'];
		$i++;
	}

	$sort = $_REQUEST['sort'];

	$num_seq = ($sort =='item_num-asc')  ? 'item_num-desc'   : 'item_num-asc';
	$type_seq = ($sort =='item_type-asc')  ? 'item_type-desc'   : 'item_type-asc';
	$units_seq = ($sort =='item_units-asc')  ? 'item_units-desc'   : 'item_units-asc';
	$rate_seq = ($sort =='item_rate-asc')  ? 'item_rate-desc'   : 'item_rate-asc';
	$adj_seq = ($sort =='item_adj-asc')  ? 'item_adj-desc'   : 'item_adj-asc';
	$tot_seq = ($sort =='item_tot-asc')  ? 'item_tot-desc'   : 'item_tot-asc';
	$date_seq = ($sort =='item_date-asc')  ? 'item_date-desc'   : 'item_date-asc';
	$code_seq = ($sort =='a.exp_code-asc')  ? 'a.exp_code-desc'   : 'a.exp_code-asc';
	$codedesc_seq = ($sort =='exp_desc-asc')  ? 'exp_desc-desc'   : 'exp_desc-asc';
	$desc_seq = ($sort =='item_desc-asc')  ? 'item_desc-desc'   : 'item_desc-asc';

	echo "<p>
			<a href='project.php'>[ Main Page ]</a> • 
			<a href='all_invoices.php'>[ Invoice List ]</a> • 
			<a href='all_invoices.php?inv_id=$inv_id'>[ Summary for This Invoice ]</a> • 
			<a href='view_fees.php?inv_id=$inv_id'>[ Fees for This Invoice ]</a>
		</p>";

	echo
"		<table>
			<thead>
			   <tr>
					<th><a href='$_SERVER[PHP_SELF]?sort=$num_seq&amp;inv_id=$inv_id'>Item #</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$type_seq&amp;inv_id=$inv_id'>Type</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$units_seq&amp;inv_id=$inv_id'>Units</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$rate_seq&amp;inv_id=$inv_id'>Rate</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$adj_seq&amp;inv_id=$inv_id'>Adj.</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$tot_seq&amp;inv_id=$inv_id'>Total</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$date_seq&amp;inv_id=$inv_id'>Date</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$code_seq&amp;inv_id=$inv_id'>Expense Code</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$codedesc_seq&amp;inv_id=$inv_id'>Code Description</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$desc_seq&amp;inv_id=$inv_id'>Line Item Description</a>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan=5>Total: </td>
					<td>" . money_format($fmt, $sum) . "</td>
					<td colspan=4>&nbsp;</td>
				</tr>
			</tfoot>
			<tbody>\n";

	foreach($data as $row)
	{
		echo
"				<tr>
					<td>$row[item_num]</td>
					<td>$row[item_type]</td>
					<td class='nowrap right'>" . number_format($row[item_units], 2) . "</td>
					<td class='nowrap right'>" . money_format($fmt, $row[item_rate]) . "</td>
					<td class='nowrap right'>" . money_format($fmt, $row[item_adj]) . "</td>
					<td class='nowrap right'>" . money_format($fmt, $row[item_tot]) . "</td>
					<td class='nowrap'>$row[item_date]</td>
					<td>$row[exp_code]</td>
					<td>$row[exp_desc]</td>
					<td>$row[item_desc]</td>
				</tr>\n";
	}

	echo
"			</tbody>
		</table>\n";

	echo
"		<p>
			<a href='project.php'>[ Main Page ]</a> • 
			<a href='all_invoices.php'>[ Invoice List ]</a> • 
			<a href='all_invoices.php?inv_id=$inv_id'>[ Summary for This Invoice ]</a> • 
			<a href='view_fees.php?inv_id=$inv_id'>[ Fees for This Invoice ]</a>
		</p>\n";
?>
	</body>
</html>