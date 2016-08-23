<div style="padding:10px">
	<img src="<?= $vd->assets_base ?>im/logo.png" alt="iNewswire" style="margin:10px 0">
	<font color="#666666">
		<br><br>Hello <?= $user->first_name ?>
		<br><br>This is a notification email from 
			<a href="http://www.i-newswire.com/">iNewswire</a>.
		<br><br>
	</font>
	<div style="border:1px solid #ccc;padding:10px;">
		<font color="#111111">
			<?= $content_view ?>
		</font>
	</div>
	<font color="#999999" size="2">
		<br><br>This is an automated email. 
			Please visit our website to contact us.
	</font>
</div>