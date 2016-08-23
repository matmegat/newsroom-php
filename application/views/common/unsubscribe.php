<div style="width: 500px; margin: 20px auto;">
	<form action="" method="post">
		<div class="alert alert-warning clearfix" style="padding: 14px">
			<div class="row-fluid">
				<div class="span2">
					<input type="submit" class="btn btn-danger pull-left span12" name="confirm" 
						value="Confirm" />
				</div>
				<div style="line-height: 18px; padding-left: 4px;" class="span10">
					You are about to unsubscribe from the 
					<strong><?= $vd->newsroom->company_name ?></strong> mailing list. You will not receive
					further emails from <strong><?= $vd->newsroom->company_name ?></strong> once this is 
					action is confirmed.
				</div>
			</div>
		</div>
	</form>
</div>