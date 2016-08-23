<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit Press Release</h1>
					<?php else: ?>
					<h1>Add New Press Release</h1>
					<?php endif ?>
				</div>
				<div class="span6">
					<div class="pull-right">
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content">
			<form class="tab-content required-form pr-form" method="post" action="manage/publish/pr/edit/save" id="content-form">				
				<?= $ci->load->view('manage/publish/pr-edit-form') ?>				
			</form>
		</div>
	</div>
</div>