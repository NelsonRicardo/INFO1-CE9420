<?php 

	$inv_id = $_REQUEST['inv_id'];

	$title_tag = 'Fee Details for Invoice ID ' . $inv_id;

	require 'page_header.php';

	setlocale(LC_MONETARY, 'en_US'); // for money_format()

	$fmt = '%#0n';  // for money_format();

	$inv_id = $_REQUEST['inv_id'];

	$data = array();

	if (!$_REQUEST['sort']) $_REQUEST['sort'] = 'item_num-asc'; // if none provided use item number

	$sort  = explode('-', $_REQUEST['sort']); // split on '-'
	$field = $sort[0]; 
	$seq   = $sort[1];

	require 'db_connect.php'; 

	$query = "select item_num, item_type, item_units, item_rate, item_adj, item_tot, item_date, a.task_code, a.act_code, tkpr_id, tkpr_name, tkpr_class, item_desc, task_desc, activity_desc
			  from nr_fees a 
			  left join nr_task_codes b on a.task_code = b.task_code 
			  left join nr_activity_codes c on a.act_code = c.activity_code
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
	$task_seq = ($sort =='task_code-asc')  ? 'task_code-desc'   : 'task_code-asc';
	$taskdesc_seq = ($sort =='task_desc-asc')  ? 'task_desc-desc'   : 'task_desc-asc';
	$act_seq = ($sort =='act_code-asc')  ? 'act_code-desc'   : 'act_code-asc';
	$actdesc_seq = ($sort =='activity_desc-asc')  ? 'activity_desc-desc'   : 'activity_desc-asc';
	$tkid_seq = ($sort =='tkpr_id-asc')  ? 'tkpr_id-desc'   : 'tkpr_id-asc';
	$tkname_seq = ($sort =='tkpr_name-asc')  ? 'tkpr_name-desc'   : 'tkpr_name-asc';
	$tkclass_seq = ($sort =='tkpr_class-asc')  ? 'tkpr_class-desc'   : 'tkpr_class-asc';
	$desc_seq = ($sort =='item_desc-asc')  ? 'item_desc-desc'   : 'item_desc-asc';

	echo
"		<p>
			<a href='project.php'>[ Main Page ]</a> • 
			<a href='all_invoices.php'>[ Invoice List ]</a> • 
			<a href='all_invoices.php?inv_id=$inv_id'>[ Summary for This Invoice ]</a> • 
			<a href='view_expenses.php?inv_id=$inv_id'>[ Expenses for This Invoice ]</a>
		</p>\n";

	echo
"		<table>
			<thead>
				<tr>
					<th><a href='$_SERVER[PHP_SELF]?sort=$num_seq&amp;inv_id=$inv_id'>Item #</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$type_seq&amp;inv_id=$inv_id'>Type</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$units_seq&amp;inv_id=$inv_id'>Hours</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$rate_seq&amp;inv_id=$inv_id'>Rate</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$adj_seq&amp;inv_id=$inv_id'>Adj.</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$tot_seq&amp;inv_id=$inv_id'>Total</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$date_seq&amp;inv_id=$inv_id'>Date</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$task_seq&amp;inv_id=$inv_id'>Task Code</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$taskdesc_seq&amp;inv_id=$inv_id'>Task Description</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$act_seq&amp;inv_id=$inv_id'>Activity Code</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$actdesc_seq&amp;inv_id=$inv_id'>Activity Description</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$tkid_seq&amp;inv_id=$inv_id'>TK ID</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$tkname_seq&amp;inv_id=$inv_id'>Timekeeper Name</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$tkclass_seq&amp;inv_id=$inv_id'>TK Class</a>
					<th><a href='$_SERVER[PHP_SELF]?sort=$desc_seq&amp;inv_id=$inv_id'>Line Item Description</a>
				</tr>
			</thead> 
			<tfoot>
				<tr>
					<td colspan='5'>Total: </td>
					<td class='nowrap'>" . money_format($fmt, $sum) . "</td>
					<td colspan='9'>&nbsp;</td>
				</tr>
			</tfoot>
			<tbody>\n";

	foreach($data as $row)
	{
		echo
"			<tr>
				<td>$row[item_num]</td>
				<td>$row[item_type]</td>
				<td class='nowrap right'>" . number_format($row[item_units], 2) . "</td>
				<td class='nowrap right'>" . money_format($fmt, $row[item_rate]) . "</td>
				<td class='nowrap right'>" . money_format($fmt, $row[item_adj]) . "</td>
				<td class='nowrap right'>" . money_format($fmt, $row[item_tot]) . "</td>
				<td class='nowrap'>$row[item_date]</td>
				<td>$row[task_code]</td>
				<td>$row[task_desc]</td>
				<td>$row[act_code]</td>
				<td>$row[activity_desc]</td>
				<td>$row[tkpr_id]</td>
				<td>$row[tkpr_name]</td>
				<td>$row[tkpr_class]</td>
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
			<a href='view_expenses.php?inv_id=$inv_id'>[ Expenses for This Invoice ]</a>
		</p>\n";
?>
	</body>
</html>