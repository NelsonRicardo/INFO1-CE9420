<?php
	setlocale(LC_MONETARY, 'en_US'); // for money_format()

	$fmt = '%#0n';  // for money_format();

	if ($_SERVER['REQUEST_URI'] == '/~ricardon/project/upload.php') 
	{
		$where = " where inv_id = $inv_id";
	}
	else
	{
		$where = " where 1 = 1"; // need to initialize where; all real constraints will start with 'and'

		if ($inv_id)
		{
			$where .= " and inv_id = $inv_id";
		}

		if ($lf_id)
		{
			$where .= " and lf_id like '%$lf_id%'";
		}

		if ($inv_num)
		{
			$where .= " and inv_num like '%$inv_num%'";
		}

		if ($inv_date)
		{
			$where .= " and inv_date = '$inv_date'";
		}

		if ($client_id)
		{
			$where .= " and client_id like '%$client_id%'";
		}

		if ($lf_matt_id)
		{
			$where .= " and lf_matt_id like '%$lf_matt_id%'";
		}

		if ($clnt_matt_id)
		{
			$where .= " and clnt_matt_id like '%$clnt_matt_id%'";
		}

		if ($inv_tot)
		{
			$where .= " and inv_tot = $inv_tot";
		}

		if ($from_date)
		{
			$where .= " and from_date = '$from_date'";
		}

		if ($thru_date)
		{
			$where .= " and thru_date = '$thru_date'";
		}

		if ($inv_desc)
		{
			$where .= " and inv_desc like '%$inv_desc%'";
		}

	}

	if (! $sort) // if no sort specified, use invoice id
	{
		$sort = 'inv_id-asc';
	}

	$invid_seq = ($sort =='inv_id-asc')  ? 'inv_id-desc'   : 'inv_id-asc';
	$lfid_seq = ($sort =='lf_id-asc')  ? 'lf_id-desc'   : 'lf_id-asc';
	$invnum_seq = ($sort =='inv_num-asc')  ? 'inv_num-desc'   : 'inv_num-asc';
	$date_seq = ($sort =='inv_date-asc')  ? 'inv_date-desc'   : 'inv_date-asc';
	$clnt_seq = ($sort =='client_id-asc')  ? 'client_id-desc'   : 'client_id-asc';
	$lfmatid_seq = ($sort =='lf_matt_id-asc')  ? 'lf_matt_id-desc'   : 'lf_matt_id-asc';
	$clntmatid_seq = ($sort =='clnt_matt_id-asc')  ? 'clnt_matt_id-desc'   : 'clnt_matt_id-asc';
	$tot_seq = ($sort =='inv_tot-asc')  ? 'inv_tot-desc'   : 'inv_tot-asc';
	$from_seq = ($sort =='from_date-asc')  ? 'from_date-desc'   : 'from_date-asc';
	$thru_seq = ($sort =='thru_date-asc')  ? 'thru_date-desc'   : 'thru_date-asc';
	$desc_seq = ($sort =='inv_desc-asc')  ? 'inv_desc-desc'   : 'inv_desc-asc';

	$sort  = explode('-', $sort); // split on '-'
	$field = $sort[0]; 
	$seq   = $sort[1];

	require 'db_connect.php'; // connect to databse

	$query = "	select inv_id, lf_id, inv_num, inv_date, client_id, lf_matt_id, clnt_matt_id, inv_tot, from_date, thru_date, inv_desc
				from nr_invoices";

	$query .= $where;

	$query .= " order by $field $seq";

	$cursor = mysqli_query($connect, $query); // execute the query

	if (! $cursor) 
		exit('Could not execute query: ' . mysqli_error($connect) . " \n\n Query: $query");

	mysqli_close($connect); // close mysql connection

	$i = 0;
	$sum = 0;
	while ($row = mysqli_fetch_array($cursor)) // get each row as an array
	{
		$data[$i] = $row; // store row in 2 dim array
		$sum += $row[inv_tot];
		$i++;
	}

	echo 
"		<table>
			<thead>
				<tr>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $invid_seq . "'>System Invoice ID</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $lfid_seq . "'>Law Firm ID</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $invnum_seq . "'>Invoice Number</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $date_seq . "'>Invoice Date</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $clnt_seq . "'>Client ID</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $lfmatid_seq . "'>Law Firm Matter ID</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $clntmatid_seq . "'>Client Matter ID</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $tot_seq . "'>Invoice Total</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $from_seq . "'>From Date</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $thru_seq . "'>Thru Date</a></th>
					<th><a href='$_SERVER[PHP_SELF]?" .  $_SERVER['QUERY_STRING'] . '&amp;sort=' . $desc_seq . "'>Invoice Description</a></th>
					<th>View Details</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan=7>Total: </td>
					<td class='nowrap'>" . money_format($fmt, $sum) . "</td>
					<td colspan=4>&nbsp;</td>
				</tr>
			</tfoot>
			<tbody>\n";

	foreach($data as $row)
	{
		echo 
"				<tr>
					<td>$row[inv_id]</td>
					<td class='nowrap'>$row[lf_id]</td>
					<td class='nowrap'>$row[inv_num]</td>
					<td class='nowrap'>$row[inv_date]</td>
					<td class='nowrap'>$row[client_id]</td>
					<td class='nowrap'>$row[lf_matt_id]</td>
					<td class='nowrap'>$row[clnt_matt_id]</td>
					<td class='nowrap right'>" . money_format($fmt, $row[inv_tot]) . "</td>
					<td class='nowrap'>$row[from_date]</td>
					<td class='nowrap'>$row[thru_date]</td>
					<td>$row[inv_desc]</td>
					<td><a href='view_fees.php?inv_id=$row[inv_id]'>[Fees]</a> <a href='view_expenses.php?inv_id=$row[inv_id]'>[Expenses]</a>
				</tr>\n";
	}

	echo
"			</tbody>
		</table>\n";
?>