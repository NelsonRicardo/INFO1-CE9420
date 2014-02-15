<?php
function process_upload()
{
	setlocale(LC_MONETARY, 'en_US'); // for money_format()

	$fmt = '%#0n'; // for money_format();

	$upfile = $_FILES['userfile']['tmp_name']; // uploaded file

	$line_nums = array(); // will be used to poulate row numbers to check if line number already used

	// Check to see if an error code was generated on the upload attempt
	if ($_FILES['userfile']['error'] > 0)
	{
	echo "\t\t<p><b>Problem:</b> ";
	switch ($_FILES['userfile']['error'])
		{
		case 1:	echo 'File exceeded upload_max_filesize.';
				break;
		case 2:	echo 'File exceeded max_file_size.';
				break;
		case 3:	echo 'File only partially uploaded.';
				break;
		case 4:	echo 'No file uploaded.';
				break;
		case 6:	echo 'Cannot upload file: No temp directory specified.';
				break;
		case 7:	echo 'Upload failed: Cannot write to disk.';
				break;
		}
	exit("</p>\n\t</body>\n</html>");
	}

	// Does the file have the right MIME type?
	if ($_FILES['userfile']['type'] != 'text/plain')
	{
		exit("\t\t<p><b>Problem:</b> file is not plain text.</p>\n\t</body>\n</html>");
	}

	// Check for file attck
	if (!is_uploaded_file($upfile))
	{
	echo "\t\t<p><b>Problem:</b> Possible file upload attack. Filename: ";
	echo $_FILES['userfile']['name'];
	exit("</p>\n\t</body>\n</html>");
	}

	$fp = fopen($upfile, 'r');

	if (! $fp)
	{
		exit("\t\t<p>Cannot open file for reading.</p>\n\t</body>\n</html>");
	}

	$i = 0; // count loop iterations to determine when on first row
	$errors = ''; // accumulate error text to outpt to html; if blank after looping thru data, data is valid
	$running_total = 0.00; // add up line items while iterating; check against total reported in file

	require 'db_connect.php';

	$result = mysqli_query($connect, 'start transaction;'); // wrap all inserts in a transaction

	if (! $result) // db error
	{
		exit("\t\t<p>Cannot start MySQL transaction: " . mysqli_error($connect) . "</p>\n\t</body>\n</html>");
	}

	while(! feof($fp)) // loop through lines of uploaded file
	{
		$row = fgetcsv($fp, 0, '|'); // vertical bar is field delimiter (per LEDES 1998B spec)

		if ($i == 0 && $row[0] != 'LEDES1998B[]') // check that first line of file indicates LEDES 1998B file.
		{
			exit("\t\t<p>File Error — This is not a LEDES 1998B file.</p>\n\t</body>\n</html>");
		}

		if ($i == 1 && 
			(
			$row[0] != 'INVOICE_DATE' || 
			$row[1] != 'INVOICE_NUMBER'|| 
			$row[2] != 'CLIENT_ID' || 
			$row[3] != 'LAW_FIRM_MATTER_ID' || 
			$row[4] != 'INVOICE_TOTAL' || 
			$row[5] != 'BILLING_START_DATE' || 
			$row[6] != 'BILLING_END_DATE' || 
			$row[7] != 'INVOICE_DESCRIPTION' || 
			$row[8] != 'LINE_ITEM_NUMBER' || 
			$row[9] != 'EXP/FEE/INV_ADJ_TYPE' || 
			$row[10] != 'LINE_ITEM_NUMBER_OF_UNITS' || 
			$row[11] != 'LINE_ITEM_ADJUSTMENT_AMOUNT' || 
			$row[12] != 'LINE_ITEM_TOTAL' || 
			$row[13] != 'LINE_ITEM_DATE' || 
			$row[14] != 'LINE_ITEM_TASK_CODE' || 
			$row[15] != 'LINE_ITEM_EXPENSE_CODE' || 
			$row[16] != 'LINE_ITEM_ACTIVITY_CODE' || 
			$row[17] != 'TIMEKEEPER_ID' || 
			$row[18] != 'LINE_ITEM_DESCRIPTION' || 
			$row[19] != 'LAW_FIRM_ID' || 
			$row[20] != 'LINE_ITEM_UNIT_COST' || 
			$row[21] != 'TIMEKEEPER_NAME' || 
			$row[22] != 'TIMEKEEPER_CLASSIFICATION' || 
			$row[23] != 'CLIENT_MATTER_ID[]'
			)
		) // check that second line of file contains valid LEDES 1998B field names
		{
			exit("\t\t<p>File Error — Invalid LEDES 1998B field names in line 2 of file.</p>\n\t</body>\n</html>");
		}

		if ($i >= 2) // do not insert header lines into database
		{
			$inv_date = 	mysqli_real_escape_string($connect, htmlentities($row[0])); // replace html entities and escape sql delimiters
			$inv_num = 		mysqli_real_escape_string($connect, htmlentities($row[1]));
			$clnt_id = 		mysqli_real_escape_string($connect, htmlentities($row[2]));
			$lf_matt_id = 	mysqli_real_escape_string($connect, htmlentities($row[3]));
			$inv_tot = 		mysqli_real_escape_string($connect, htmlentities($row[4]));
			$start_date = 	mysqli_real_escape_string($connect, htmlentities($row[5]));
			$end_date = 	mysqli_real_escape_string($connect, htmlentities($row[6]));
			$inv_desc = 	mysqli_real_escape_string($connect, htmlentities($row[7]));
			$line_num = 	mysqli_real_escape_string($connect, htmlentities($row[8]));
			$line_type = 	mysqli_real_escape_string($connect, htmlentities($row[9]));
			$line_qty = 	mysqli_real_escape_string($connect, htmlentities($row[10]));
			$line_adj = 	mysqli_real_escape_string($connect, htmlentities($row[11]));
			$line_tot = 	mysqli_real_escape_string($connect, htmlentities($row[12]));
			$line_date = 	mysqli_real_escape_string($connect, htmlentities($row[13]));
			$task_code = 	mysqli_real_escape_string($connect, htmlentities($row[14]));
			$exp_code = 	mysqli_real_escape_string($connect, htmlentities($row[15]));
			$act_code = 	mysqli_real_escape_string($connect, htmlentities($row[16]));
			$tkpr_id = 		mysqli_real_escape_string($connect, htmlentities($row[17]));
			$line_desc = 	mysqli_real_escape_string($connect, htmlentities($row[18]));
			$lf_id = 		mysqli_real_escape_string($connect, htmlentities($row[19]));
			$line_rate = 	mysqli_real_escape_string($connect, htmlentities($row[20]));
			$tkpr_name = 	mysqli_real_escape_string($connect, htmlentities($row[21]));
			$tkpr_class = 	mysqli_real_escape_string($connect, htmlentities($row[22]));
			$clnt_matt_id = mysqli_real_escape_string($connect, htmlentities(substr($row[23], 0, strlen($row[23]) - 2))); // strip out LEDES 1998B line delimiter, []

			if (count($row) != 24)
			{
				exit("\t\t<p>File line number $i data error — Missing fields. Each line must contain 24 fields, separated by a vertcal bar, |. No further error checking can be done until this is corrected. (The line number given here is the physical line in your file, not the LINE_ITEM_NUMBER.)</p>\n\t</body>\n</html>");
			}

			if ($i == 2) // extract header data from first non-header line only (LEDES 1998B spec says take header values from first record & ignore others)
			{	
				$query = "select * from nr_invoices where inv_num = '$inv_num' and lf_id = '$lf_id' and client_id = '$clnt_id'";

				$result = mysqli_query($connect, $query);

				if (mysqli_num_rows($result) != 0) // invoice already in database
				{
					exit("\t\t<p><b>Error: </b>Cannot upload duplicate invoice. This invoice is already in the system. Invoice Number $inv_num, Law Firm ID: $lf_id, Client ID: $clnt_id.</p>\n\t</body>\n</html>");
				}

				// ***** begin invoice-level validation *****

				if (! $inv_date) // check if invoice date provided
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num invoice date error — the INVOICE_DATE field cannot be blank.</li>\n";
				}
				else 
					if (strlen($inv_date) != 8 || ! is_numeric($inv_date) || ! checkdate(substr($inv_date, 4, 2), substr($inv_date, 6, 2), substr($inv_date, 0, 4))) // confirm date is valid
					{
						$errors .= "\t\t\t<li>Invoice number $inv_num date error — $inv_date is not a valid INVOICE_DATE. Specify a valid date in the YYYYMMDD format (4-digit year, 2-digit month, and 2-digit day, with no spaces or punctuation.</li>\n";
					}

				if (! $clnt_id) // check if client id provided
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num client ID error — the CLIENT_ID field cannot be blank.</li>\n";
				}

				if (! $inv_tot) // check if invoice total provided
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num invoice total error — the INVOICE_TOTAL field cannot be blank.</li>\n";
				}
				else
					if (! is_numeric($inv_tot))
					{
						$errors .= "\t\t\t<li>Invoice number $inv_num invoice total error — the INVOICE_TOTAL field contains $inv_tot, which is not a valid number.</li>\n";
					}

				if (! $start_date) // check if billing start date provided
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num billing start date error — the BILLING_START_DATE field cannot be blank.</li>\n";
				}
				else 
					if (strlen($start_date) != 8 || ! is_numeric($start_date) || ! checkdate(substr($start_date, 4, 2), substr($start_date, 6, 2), substr($start_date, 0, 4))) // confirm date is valid
					{
						$errors .= "\t\t\t<li>Invoice number $inv_num billing start date error — $start_date is not a valid BILLING_START_DATE. Specify a valid date in the YYYYMMDD format (4-digit year, 2-digit month, and 2-digit day, with no spaces or punctuation.</li>\n";
					}

				if (! $end_date) // check if billing end date provided
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num billing end date error — the BILLING_END_DATE field cannot be blank.</li>\n";
				}
				else 
					if (strlen($end_date) != 8 || ! is_numeric($end_date) || ! checkdate(substr($end_date, 4, 2), substr($end_date, 6, 2), substr($end_date, 0, 4))) // confirm date is valid
					{
						$errors .= "\t\t\t<li>Invoice number $inv_num billing end date error — $end_date is not a valid BILLING_END_DATE. Specify a valid date in the YYYYMMDD format (4-digit year, 2-digit month, and 2-digit day, with no spaces or punctuation.</li>\n";
					}

				if (! $inv_desc) // check if invoice description provided. although optional per LEDES 1998B specs, most real-world systems and/or client require it.
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num invoice description error — the INVOICE_DESCRIPTION field cannot be blank.</li>\n";
				}

				if ($inv_date < $end_date) // invoice date preceeds billing end date 
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num date error — INVOICE_DATE must be on or after BILLING_END_DATE.</li>\n";
				}

				if ($start_date > $end_date) // end date before start date
				{
					$errors .= "\t\t\t<li>Invoice number $inv_num date error — BILLING_END_DATE must be on or after BILLING_START_DATE.</li>\n";
				}

				// ***** end invoice-level validation. check of sum of line items vs reported total happens at the end after line items have been looped through *****

				if ($errors == '') // no validation errors so far, so insert row
				{
					$insert = "	insert into nr_invoices
								(inv_id, lf_id, inv_num, inv_date, client_id, lf_matt_id, clnt_matt_id, inv_tot, from_date, thru_date, inv_desc)
								values
								(0, '$lf_id', '$inv_num', '$inv_date', '$clnt_id', '$lf_matt_id', '$clnt_matt_id', '$inv_tot', '$start_date', '$end_date', '$inv_desc');";

					$result = mysqli_query($connect, $insert); //issue the insert

					$inv_id = mysqli_insert_id($connect); // need primary key from invoices table to populate foreign key column in fees and expenses tables

					if (! $result) // db cannot insert row
					{
						exit("\t\t<p>Cannot execute update: " . mysqli_error($connect) . "</p>\n\t</body>\n</html>");
					}
				}
	
				$invoice_total = $inv_tot; // to prevent overwriting on subsequent loop iterations
			}

			if (in_array($line_num, $line_nums)) // line item number repeated
			{
				$errors .= "\t\t\t<li>Line item number $line_num line item number error — LINE_ITEM_NUMBER $line_num has been used more than once in this invoice. LINE_ITEM_NUMBER must be unique per invoice.</li>\n";
			}

			$line_nums[] = $line_num; // add current line item number to array

			// ***** begin line-item validation *****

			if (! $line_num)
			{
				$errors .= "\t\t\t<li>File line number $i line item number error — the LINE_ITEM_NUMBER field cannot be blank. (The line number given here is the physical line in your file, not the LINE_ITEM_NUMBER.)</li>\n";
			}

			if (! $inv_num) // check if invoice number provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num invoice number error — the INVOICE_NUMBER field cannot be blank.</li>\n";
			}

			if (! $lf_matt_id) // check if law firm matter id provided
			{
				$errors .= "\t\t\t<li>Invoice number $inv_num law firm matter ID error — the LAW_FIRM_MATTER_ID field cannot be blank.</li>\n";
			}

			if (! $line_type) // check if line type provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num type error — the EXP/FEE/INV_ADJ_TYPE field cannot be blank.</li>\n";
			}
			else
				if ($line_type != 'F' && $line_type != 'E' && $line_type != 'IF' && $line_type != 'IE') // check if line type is valid
				{
					$errors .= "\t\t\t<li>Line item number $line_num type error — $line_type is not a valid EXP/FEE/INV_ADJ_TYPE. Specify F, E, IF, or IE.</li>\n";
				}

			if (($line_type == 'F' || $line_type == 'E') && ! $line_qty) // check if line item number of units provided. not required for IE or IF lines
			{
				$errors .= "\t\t\t<li>Line item number $line_num number of units error — the LINE_ITEM_NUMBER_OF_UNITS field cannot be blank on fee (F) and expense (E) line items.</li>\n";
			}

			if ($line_qty != '' && ! is_numeric($line_qty)) // check for valid line item number of units, if provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num number of units error — the LINE_ITEM_NUMBER_OF_UNITS field contains $line_qty, which is not a valid number.</li>\n";
			}

			if ($line_qty != '' && ! is_numeric($line_adj)) // check for valid line item adjustment amt. field is optional, so check only if not blank
			{
				$errors .= "\t\t\t<li>Line item number $line_num adjustment amount error — the LINE_ITEM_ADJUSTMENT_AMOUNT field contains $line_adj, which is not a valid number.</li>\n";
			}

			if (! $line_tot) // check if line item total provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num total error — the LINE_ITEM_TOTAL field cannot be blank.</li>\n";
			}
			else
				if (! is_numeric($line_tot)) // check if line item total valid
				{
					$errors .= "\t\t\t<li>Line item number $line_num total error — the LINE_ITEM_TOTAL field contains $line_tot, which is not a valid number.</li>\n";
				}

			if (! $line_date) // check if line item date provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num line item date error — the LINE_ITEM_DATE field cannot be blank.</li>\n";
			}
			else 
				if (strlen($line_date) != 8 || ! is_numeric($line_date) || ! checkdate(substr($line_date, 4, 2), substr($line_date, 6, 2), substr($line_date, 0, 4))) // confirm date is valid
				{
					$errors .= "\t\t\t<li>Line item number $line_num line item date error — $line_date is not a valid LINE_ITEM_DATE. Specify a valid date in the YYYYMMDD format (4-digit year, 2-digit month, and 2-digit day, with no spaces or punctuation.</li>\n";
				}

			if ($line_type == 'F' && ! $task_code)  // fees -- check if task code provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num task code error — the LINE_ITEM_TASK_CODE field cannot be blank on fee (F) line items.</li>\n";
			}

			if (($line_type == 'F' || $line_type == 'IF') && $task_code != '') // fees and invoice-level fee adjustments -- validate task code
			{
				$query = "select * from nr_task_codes where task_code = '$task_code';"; // confirm that task code is valid

				$result = mysqli_query($connect, $query);

				if (mysqli_num_rows($result) == 0) // task code not found
				{
					$errors .= "\t\t\t<li>Line item number $line_num task code error — $task_code is not a valid LINE_ITEM_TASK_CODE.</li>\n";
				}
			}

			if ($line_type == 'F' && ! $act_code)  // fees -- check if activity code provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num activity code error — the LINE_ITEM_ACTIVITY_CODE field cannot be blank on fee (F) line items.</li>\n";
			}

			if (($line_type == 'F' || $line_type == 'IF') && $act_code != '') // fees and invoice-level fee adjustments -- validate activity code
			{
				$query = "select * from nr_activity_codes where activity_code = '$act_code';"; // confirm that expense code is valid

				$result = mysqli_query($connect, $query);

				if (mysqli_num_rows($result) == 0) // activity code not found
				{
					$errors .= "\t\t\t<li>Line item number $line_num activity code error — $act_code is not a valid LINE_ITEM_ACTIVITY_CODE.</li>\n";
				}
			}

			if ($line_type == 'E' && ! $exp_code) // expenses -- check if expense code provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num expense code error — the LINE_ITEM_EXPENSE_CODE field cannot be blank on expense (E) line items.</li>\n";
			}

			if (($line_type == 'E' || $line_type == 'IE') && $exp_code != '') // expenses and invoice-level expense adjustments -- validate expense code
			{
				$query = "select * from nr_expense_codes where exp_code = '$exp_code';"; // confirm that expense code is valid

				$result = mysqli_query($connect, $query);

				if (mysqli_num_rows($result) == 0) // expense code not found
				{
					$errors .= "\t\t\t<li>Line item number $line_num expense code error — $exp_code is not a valid LINE_ITEM_EXPENSE_CODE.</li>\n";
				}
			}

			if ($line_type == 'F' && ! $tkpr_id) // fees -- check if timekeeper id provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num timekeeper id error — the TIMEKEEPER_ID field cannot be blank on fee (F) line items.</li>\n";
			}

			if ($line_type == 'F' && ! $line_desc) // fees -- check if line item description provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num line item description error — the LINE_ITEM_DESCRIPTION field cannot be blank on fee (F) line items.</li>\n";
			}

			if ($line_qty * $line_rate + $line_adj != $line_tot) // line-item math error
			{
				$errors .= "\t\t\t<li>Line item number $line_num math error — LINE_ITEM_NUMBER_OF_UNITS x LINE_ITEM_UNIT_COST + LINE_ITEM_ADJUSTMENT_AMOUNT does not equal LINE_ITEM_TOTAL: " . number_format($line_qty, 2) . ' x ' . money_format($fmt, $line_rate) . ' + ' . money_format($fmt, $line_adj) . ' =/= ' . money_format($fmt, $line_tot) . "</li>\n";
			}

			if (! $lf_id) // check if law firm id provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num law firm id error — the LAW_FIRM_ID field cannot be blank.</li>\n";
			}

			if (($line_type == 'F' || $line_type == 'E') && ! $line_rate) // fees and expenses -- check if line item unit cost provided. optional for invoice-level adjustments
			{
				$errors .= "\t\t\t<li>Line item number $line_num total error — the LINE_ITEM_UNIT_COST field cannot be blank.</li>\n";
			}

			if ($line_rate && ! is_numeric($line_rate)) // check if line item unit cost valid
			{
				$errors .= "\t\t\t<li>Line item number $line_num total error — the LINE_ITEM_UNIT_COST field contains $line_rate, which is not a valid number.</li>\n";
			}

			if ($line_type == 'F' && ! $tkpr_name) // fees -- check if timekeeper name provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num timekeeper name error — the TIMEKEEPER_NAME field cannot be blank on fee (F) line items.</li>\n";
			}

			if ($line_type == 'F' && ! $tkpr_class) // fees -- check if timekeeper classification provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num timekeeper classification error — the TIMEKEEPER_CLASSIFICATION field cannot be blank on fee (F) line items.</li>\n";
			}

			if ($tkpr_class && $tkpr_class != 'PT' && $tkpr_class != 'AS' && $tkpr_class != 'OC' && $tkpr_class != 'LA' && $tkpr_class != 'OT') // fees -- check if timekeeper classification provided
			{
				$errors .= "\t\t\t<li>Line item number $line_num timekeeper classification error — $tkpr_class is not a valid TIMEKEEPER_CLASSIFICATION. Specify PT, AS, OC, LA, or OT.</li>\n";
			}

			if (! $clnt_matt_id) // check if client matter id provided. although seemingly optional per the specs, most real-world systems require this.
			{
				$errors .= "\t\t\t<li>Line item number $line_num client matter id error — the CLIENT_MATTER_ID field cannot be blank.</li>\n";
			}

			if ($line_date < $start_date || $line_date > $end_date) // line date not between billing start and end dates
			{
				$errors .= "\t\t\t<li>Line item number $line_num date error — LINE_ITEM_DATE must fall between BILLING_START_DATE and BILLING_END_DATE (inclusive).</li>\n";
			}

			// ***** end line-item validation *****

			if ($errors == '') // no validation errors so far, so insert row
			{
				if ($line_type == 'F' || $line_type == 'IF') // fees and invoice-level fee adjustments -- prepare insert statement
				{
					$insert = " insert into nr_fees
								(fee_id, inv_id, item_num, item_type, item_units, item_rate, item_adj, item_tot, item_date, task_code, act_code, tkpr_id, tkpr_name, tkpr_class, item_desc)
								values
								(0, $inv_id, '$line_num', '$line_type', '$line_qty', '$line_rate', '$line_adj', '$line_tot', '$line_date', '$task_code', '$act_code', '$tkpr_id', '$tkpr_name', '$tkpr_class', '$line_desc');";
				}

				if ($line_type == 'E' || $line_type == 'IE') // expenses and invoice-level expense adjustments -- prepare insert statement
				{
					$insert = " insert into nr_expenses
								(exp_id, inv_id, item_num, item_type, item_units, item_rate, item_adj, item_tot, item_date, exp_code, item_desc)
								values
								(0, $inv_id, '$line_num', '$line_type', '$line_qty', '$line_rate', '$line_adj', '$line_tot', '$line_date', '$exp_code', '$line_desc');";
				}
				
				$result = mysqli_query($connect, $insert); //issue the insert					

				if (! $result) // db cannot insert row
				{
					exit("\t\t<p>Cannot insert data: " . mysqli_error($connect) . "</p>\n\t</body>\n</html>");
				}
			}

			$running_total += $line_tot; // add current line total to running total
		}

	$i++; // increment iteration counter

	}

	if ($invoice_total != $running_total) // invoice lines do not add up total claimed in file
	{
		$errors .= "\t\t\t<li>Invoice number $inv_num math error — The INVOICE_TOTAL does not equal the sum of the LINE_ITEM_TOTALs: " . money_format($fmt, $invoice_total) . ' =/= ' . money_format($fmt, $running_total) . "</li>\n";
	}

	fclose($upfile); //close file

	if ($errors == '') // no LEDES 1998B validation errors found
	{
		$result = mysqli_query($connect, 'commit work;'); // commit to db

		echo "\t\t<h4>You have successfully uploaded the following invoice:</h4>\n"; // whew! it worked!

		include "invoice_list.php";
	}
	else // LEDES 1998B validation errors found
	{
		$result = mysqli_query($connect, 'rollback work;'); // rollback from db

		echo "\t\t<h4 class='errors'>File "  . $_FILES['userfile']['name'] . " cannot be uploaded due to errors. Please correct the following errors before attempting resubmission:</h4>";
		echo "\n\t\t<ul>\n" . $errors . "\t\t</ul>\n";
	}

	if (! $result) // db cannot commit OR rollback
	{
		exit("\t\t<p>Transaction processing error: " . mysqli_error($connect) . "</p>\n\t</body>\n</html>");
	}

	mysqli_close($connect); // close mysql connection
}
?>