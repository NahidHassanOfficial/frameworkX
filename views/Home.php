<p style="color: crimson; font-size: 20px;">This is the Home page</p>
<?php global $DB; $dbx = $DB->result_all('SELECT * FROM user'); echo json_encode($dbx); ?>
<?php View::render('components/Child'); ?>