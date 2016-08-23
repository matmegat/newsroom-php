<section class="form-section social-media">
	<h2>
		Social Media
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_SOCIAL ?>">
			<i class="icon-question-sign"></i>
		</a>	
	</h2>
	<ul>
		<?php if (!Auth::user()->is_free_user()): ?> 
		<?php if (@$vd->social->twitter): ?>
		<li>
			<label class="checkbox-container">
				<input type="checkbox" name="post_to_twitter" value="1" 
					<?= value_if_test(@$vd->m_content->post_to_twitter, 'checked') ?> /> 
				<span class="checkbox"></span>
				Post this content to Twitter
			</label>
		</li>
		<?php endif ?>
		<?php if (@$vd->social->facebook): ?>
		<li>
			<label class="checkbox-container">
				<input type="checkbox" name="post_to_facebook" value="1" 
					<?= value_if_test(@$vd->m_content->post_to_facebook, 'checked') ?> /> 
				<span class="checkbox"></span>
				Post this content to Facebook
			</label>
		</li>
		<?php endif ?>
		<?php endif ?>
		<li class="configure-social">
			<a href="manage/newsroom/social" target="_blank">Manage Accounts</a>
		</li>
	</ul>
</section>