<?php
	$title_tag = 'Upload Results';

	require 'page_header.php';
?>
		<p><a href="project.php">[ Main Page ]</a> • <a href="all_invoices.php">[ Invoice List ]</a></p>
<?php
	require 'process_upload.php';
	process_upload(); // custom function
?>
		<p><a href="project.php">[ Main Page ]</a> • <a href="all_invoices.php">[ Invoice List ]</a></p>
	</body>
</html>