<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Content Review</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="content content-no-tabs">
	
	<form action="<?= $ci->uri->uri_string ?>" method="post">
		
		<input type="hidden" name="last-location" 
			value="<?= $vd->esc($_SERVER['HTTP_REFERER']) ?>" />
		
		<div class="row-fluid">
			<div class="span8 information-panel">
				<h2>Feedback for the customer</h2>
				<div class="row-fluid">
					<textarea class="span12" placeholder="Comments" 
						name="comments" cols="30" rows="5"></textarea>
				</div>
				<ul class="canned-list marbot-20">
					<?php foreach ($vd->canned as $canned): ?>
					<li class="marbot-10">
						<div class="checkbox-container-box">
							<label class="checkbox-container louder">
								<input type="checkbox" name="canned[]" value="<?= $canned->id ?>" />
								<span class="checkbox"></span>
								<?= $vd->esc($canned->title) ?>
							</label>
							<p class="muted">
								<?= $vd->esc($canned->content) ?>
							</p>
						</div>
					</li>
					<?php endforeach ?>
				</ul>
			</div>
			
			<aside class="span4 aside aside-fluid">
				<div id="locked_aside">
					<div class="aside-properties">
						<section class="ap-block">
							<h5><?= $vd->esc($vd->content->title) ?></h5>
							<p><?= $vd->esc($vd->content->summary) ?></p>
							<div class="row-fluid">
								<button type="submit" name="confirm" value="1" 
									class="span12 bt-orange marbot-5">Confirm Rejection</button>
							</div>
						</section>
					</div>
				</div>
			</aside>
		</div>
	</form>
	
	<script>
	
	$(function() {
		
		var options = { offset: { top: 20 } };
		$.lockfixed("#locked_aside", options);
		
	});
	
	</script>
	
</div>