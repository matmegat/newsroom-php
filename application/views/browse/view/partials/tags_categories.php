<div class="row-fluid rb-categories-block">
	
	<?php $tags = $vd->m_content->get_tags(); ?>
	
	<?php if (count($tags)): ?>
	<div class="span6">
	<?php else: ?>
	<div class="span12">
	<?php endif ?>
		<section class="rb-categories">
			<h3>
				<i class="icon-list"></i> Categories:
			</h3>
			<p>
				<?php if($vd->m_content->cat_1_id) : ?>
					<?php $cat_1 = Model_Cat::find($vd->m_content->cat_1_id) ?>
					<a href="<?php echo $cat_1->url() ?>"><?php echo $vd->esc($cat_1->name) ?></a><?php
					echo (($vd->m_content->cat_3_id || $vd->m_content->cat_2_id) ? ', ' : '') ?>
				<?php endif; ?>
				<?php if($vd->m_content->cat_2_id) : ?>
					<?php $cat_2 = Model_Cat::find($vd->m_content->cat_2_id) ?>
					<a href="<?php echo $cat_2->url() ?>"><?php echo $vd->esc($cat_2->name) ?></a><?php
					 echo (($vd->m_content->cat_3_id) ? ', ' : '') ?>
				<?php endif; ?>
				<?php if($vd->m_content->cat_3_id) : ?>
					<?php $cat_3 = Model_Cat::find($vd->m_content->cat_3_id) ?>
					<a href="<?php echo $cat_3->url() ?>"><?php echo $vd->esc($cat_3->name) ?></a>
				<?php endif; ?>
			</p>
		</section>
	</div>
	
	<?php if (count($tags)): ?>
	<div class="span6"> 
		<section class="rb-categories rb-tags">
			<h3> 
				<i class="icon-tags"></i> Tags:
			</h3>
			<p>
				<?php foreach($tags as $i => $tag) : ?>
					<a href="<?php echo Tag::url($tag) ?>"><?php echo $vd->esc($tag) ?></a><?=
					(($i === count($tags) - 1) ? '' : ', ') ?>
				<?php endforeach; ?>
			</p>
		</section>
	</div>
	<?php endif ?>
	
</div>