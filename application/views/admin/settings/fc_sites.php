<div class="row-fluid marbot-20">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>FC Sites</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<?= $this->load->view('admin/partials/filters'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid fin-services-grid">
				<thead>
					
					<tr>
						<th class="left">Site</th>
						<th>Hash</th>
						<th>Upload</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr class="result <?= value_if_test($result->logo_image_id, 'has-im') ?>" 
						data-hash="<?= $result->hash ?>">
						<td class="left">							
							<h3 class="nopadbot">
								<a href="<?= $vd->esc($result->url) ?>">
									<?= $vd->esc($result->name) ?>
								</a>
							</h3>
						</td>		
						<td>
							<?= $result->hash ?>
						</td>
						<td>
							<a class="a-upload" href="#">Upload</a>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="clearfix">
				<div class="pull-right grid-report">
					Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> 
					Sites
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
			
			<script>
			
			$(function() {
			
				$(document).on("click", ".a-upload", function() {
					var result = $(this).parents(".result");
					var fake_in = $.create("input");
					fake_in.attr("name", "file");
					fake_in.attr("type", "file");
					fake_in.on("change", function() {
						var hash = result.data("hash");
						result.addClass("loader");
						fake_in.ajax_upload({
							callback: function() { 
								result.removeClass("loader");
								result.addClass("has-im");
							},
							url: "admin/settings/fc_sites/upload",
							data: { hash: hash }
						});
					});
					fake_in.click();
					return false;
				});
				
			});
			
			</script>
		
		</div>
	</div>
</div>