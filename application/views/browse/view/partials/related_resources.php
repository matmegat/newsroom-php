<?php if ($vd->m_content->stored_file_id_1 || $vd->m_content->stored_file_id_2 || 
	$vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link): ?>
			
<section class="resources-block rb-additional-resources">
	<div class="row-fluid">
		
		<?php if ($vd->m_content->stored_file_id_1 || $vd->m_content->stored_file_id_2): ?>
			<?php if ($vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link): ?>
			<div class="span6">
			<?php else: ?>
			<div class="span12">
			<?php endif ?>
				<h3>Related <strong>Files</strong></h3>
				<ul class="related-files-with-icons">
					<?php if ($vd->m_content->stored_file_id_1) : ?>
						<?= $ci->load->view('browse/view/partials/related_resources_file',
							array('stored_file_id' => $vd->m_content->stored_file_id_1,
									'stored_file_name' => $vd->m_content->stored_file_name_1)); ?>
					<?php endif; ?>
					<?php if ($vd->m_content->stored_file_id_2) : ?>
						<?= $ci->load->view('browse/view/partials/related_resources_file',
							array('stored_file_id' => $vd->m_content->stored_file_id_2,
									'stored_file_name' => $vd->m_content->stored_file_name_2)); ?>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ($vd->m_content->rel_res_pri_link || $vd->m_content->rel_res_sec_link): ?>
			<?php if ($vd->m_content->stored_file_id_1 || $vd->m_content->stored_file_id_2): ?>
			<div class="span6">
			<?php else: ?>
			<div class="span12">
			<?php endif ?>
				<h3>Additional <strong>Links</strong></h3>
				<ul>
					<?php if($vd->m_content->rel_res_pri_link) : ?>
					<?php if(!$vd->m_content->rel_res_pri_title) $vd->m_content->rel_res_pri_title = $vd->m_content->rel_res_pri_link; ?>
						<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_pri_link) ?>"><?= 
							$vd->esc($vd->m_content->rel_res_pri_title) ?></a></li>
					<?php endif; ?>
					<?php if($vd->m_content->rel_res_sec_link) : ?>
					<?php if(!$vd->m_content->rel_res_sec_title) $vd->m_content->rel_res_sec_title = $vd->m_content->rel_res_sec_link; ?>	
						<li><a href="<?php echo $vd->esc($vd->m_content->rel_res_sec_link) ?>"><?= 
							$vd->esc($vd->m_content->rel_res_sec_title) ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
		
	</div>
</section>

<?php endif ?>