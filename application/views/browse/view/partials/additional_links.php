<?php if ($vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link) : ?>
<section class="resources-block">
	<h3>
		Additional <strong>Links</strong>
	</h3>
	<ul>
		<?php if($vd->m_content->rel_res_pri_link) : ?>
		<?php if(!$vd->m_content->rel_res_pri_title) $vd->m_content->rel_res_pri_title = $vd->m_content->rel_res_pri_link; ?>
			<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_pri_link) ?>"><?php 
				echo $vd->esc($vd->m_content->rel_res_pri_title) ?></a></li>
		<?php endif; ?>
		<?php if($vd->m_content->rel_res_sec_link) : ?>
		<?php if(!$vd->m_content->rel_res_sec_title) $vd->m_content->rel_res_sec_title = $vd->m_content->rel_res_sec_link; ?>	
			<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_sec_link) ?>"><?php 
				echo $vd->esc($vd->m_content->rel_res_sec_title) ?></a></li>
		<?php endif; ?>
	</ul>
</section>
<?php endif; ?>