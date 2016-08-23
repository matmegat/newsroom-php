<section class="resources-block rb-additional-resources">
	<div class="row-fluid">
		<?php $tags = $vd->m_content->get_tags(); ?>
		<?php if($vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link) : ?>
		<?php if($tags) : ?>
			<div class="span6">
		<?php else : ?>
			<div class="span12">
		<?php endif; ?>
			<h3>
				Additional <strong>Links</strong>
			</h3>
			<ul>
				<?php if($vd->m_content->rel_res_pri_link) : ?>
				<?php if(!$vd->m_content->rel_res_pri_title) $vd->m_content->rel_res_pri_title = $vd->m_content->rel_res_pri_link; ?>
					<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_pri_link) ?>">
					<?php echo $vd->esc($vd->m_content->rel_res_pri_title) ?></a></li>
				<?php endif; ?>
				<?php if($vd->m_content->rel_res_sec_link) : ?>
				<?php if(!$vd->m_content->rel_res_sec_title) $vd->m_content->rel_res_sec_title = $vd->m_content->rel_res_sec_link; ?>	
					<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_sec_link) ?>">
					<?php echo $vd->esc($vd->m_content->rel_res_sec_title) ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php if($tags) : ?>
			<?php if($vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link) : ?>
				<div class="span6">
			<?php else : ?>
				<div class="span12">
			<?php endif; ?>
				<h3> 
					Related <strong>Tags</strong>
				</h3>
				<p>
					<?php foreach($tags as $i => $tag) : ?>
						<a href="<?php echo Tag::url($tag) ?>"><?php echo $vd->esc($tag) ?></a><?=
						(($i === count($tags) - 1) ? '' : ', ') ?>
					<?php endforeach; ?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</section>