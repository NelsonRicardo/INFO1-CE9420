<?php 
$title_tag = 'Main Page';

require 'page_header.php'; ?>
		<form method="post" action="upload.php" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
			<fieldset>
				<legend>Upload a LEDES 1998B file:</legend><br>
				<input type="file" name="userfile"><br>
				<input type="submit" value="Upload File">
			</fieldset>
		</form>
		<p><a href="all_invoices.php">[ Invoice List ]</a> • <a href="search_tasks.php">[ Search Task Codes ]</a></p>
		<br>
		<br>
		<br>
		<hr>
		<p>Please visit <a href="http://www.ledes.org/" target="_blank">www.ledes.org</a> for information about the <a href="http://www.ledes.org/ledes1998b.aspx" target="_blank">LEDES 1998B</a> format.</p>
		<p>Some simplifications for this project:</p>
		<ul>
			<li>Each file can contain only one invoice. (Firms creating LEDES 1998B bills using DocuDraft in Aderant Expert already do it this way. Firms using other systems or Report Writer in Aderant Expert may have LEDES 1998B files with multiple bills.)</li>
			<li>Each invoice can be for only one matter. (Most real-world systems enforce this anyway.)</li>
			<li>A new line, rather than an empty bracket pair (i.e., []), will be treated as the record delimiter. (Most real-world LEDES 1998B files likely have a new line after the record delimiter.)</li> 
		</ul>
	</body>
</html>