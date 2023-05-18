

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<?php echo $this->fetch('content'); ?>

	Your account have been created successfully
<hr />
Click on registry key :
<br />
<table width='100%' bgcolor='#ff0' border='1'>
    <tr>
        <td>
         <?php echo    __SITE_URL.'users/confirmation_email/'.$register_key;  ?>
        </td>
    </tr>
</table>
</body>
</html>