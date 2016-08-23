<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Contacts Manager</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/contact/import" class="bt-publish bt-silver">Import</a>
						<a href="manage/contact/contact/download" class="bt-publish bt-silver">Export All</a>
						<a href="manage/contact/contact/edit" class="bt-publish bt-orange">Add Contact</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/contact/list" href="manage/contact/list">Lists</a></li>
			<li><a data-on="^manage/contact/contact" href="manage/contact/contact">Contacts</a></li>
		</ul>
	</div>
</div>

<?= $this->load->view('manage/contact/partials/contact_listing', null, true) ?>