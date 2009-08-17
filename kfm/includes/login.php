<html>
	<head>
		<style type="text/css"><?php
			$cssfile='themes/'.$kfm_theme.'/login.css';
			$css=file_exists($cssfile)?file_get_contents($cssfile):'';
			echo preg_replace('/\s+/',' ',$css);
		?></style>
		<title>KFM - Kae's File Manager - Login</title>
	</head>
	<body>
		<form method="post" action="./">
			<table>
				<tr>
					<th colspan="2">KFM Login</th>
				</tr>
				<tr>
					<th>Username</th><td><input name="username" /></td>
				</tr>
				<tr>
					<th>Password</th><td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" value="Login" /></th>
				</tr>
			</table>
		</form>
		<?php if($err)echo $err; ?>
	</body>
</html>
