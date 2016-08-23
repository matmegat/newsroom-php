<?php if($vd->m_content->supporting_quote) : ?>
	<blockquote>
		<q><?php echo $vd->esc($vd->m_content->supporting_quote) ?></q>
		<p class="author">
			<?php if ($vd->m_content->supporting_quote_title): ?>
			<?php echo $vd->esc($vd->m_content->supporting_quote_name) ?>,
			<?php echo $vd->esc($vd->m_content->supporting_quote_title) ?>
			<?php else: ?>
			<?php echo $vd->esc($vd->m_content->supporting_quote_name) ?>
			<?php endif ?>
		</p>
		<span class="arrow"></span>
	</blockquote>
<?php endif; ?>