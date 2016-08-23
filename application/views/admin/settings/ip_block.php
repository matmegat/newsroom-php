<div class="row-fluid marbot-20">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>IP Block</h1>
				</div>				
				<div class="span6">
					<form action="admin/settings/ip_block/add" method="post">
						<div class="pull-right">
							<input type="text" placeholder="IP Address" name="addr" />
							<button type="submit" class="bt-publish bt-silver">
								Block
							</button>
						</div>
					</form>
				</div>
			</div>
		</header>
	</div>
</div>

<?= $this->load->view('admin/partials/filters'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">IP Address</th>
						<th>Date</th>
						<th>Renew</th>
						<th>Remove</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr class="result">
						<td class="left">
							<h3 class="nopadbot"><?= $vd->esc($result->addr) ?></h3>
						</td>
						<td>
							<?php $dt_blocked = Date::out($result->date_blocked) ?>
							<?= $dt_blocked->format('M j, Y'); ?>
						</td>
						<td>
							<form action="admin/settings/ip_block/add" method="post">
								<input type="hidden" name="addr" value="<?= $vd->esc($result->addr) ?>" />
								<a class="a-submit" href="#">Renew</a>
							</form>
						</td>
						<td>
							<form action="admin/settings/ip_block/delete" method="post">
								<input type="hidden" name="addr" value="<?= $vd->esc($result->addr) ?>" />
								<a class="a-submit" href="#">Remove</a>
							</form>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="clearfix">
				<div class="pull-right grid-report">
					Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> 
					Addresses
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
			
			<script>
			
			$(document).on("click", ".a-submit", function() {
				$(this).parents("form").submit();
				return false;
			});
			
			</script>
		
		</div>
	</div>
</div>