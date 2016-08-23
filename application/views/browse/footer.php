					</div>					
				</section>

				<aside class="span4 aside aside-right">

					<?php if ($vd->wide_view): ?>
					<?php if (!$ci->is_common_host): ?>					
					<?= $ci->load->view('browse/partials/content_types') ?>					
					<?php endif ?>
					<?php endif ?>

					<?php if (@$vd->nr_contact): ?>
					
					<?= $ci->load->view('browse/partials/contact') ?>
					
					<?php endif ?>

					<?php if (@$vd->nr_profile->summary || @$vd->nr_profile->description
					       || @$vd->nr_profile->website): ?>
					
					<section class="al-block">
						<h3>About <?= $vd->esc($ci->newsroom->company_name) ?></h3>
						<div class="aside-content">
							
							<?php if ($ci->is_common_host): ?>
							<?php $lo_im = Model_Image::find(@$vd->nr_custom->logo_image_id); ?>
							<?php if ($lo_im): ?>
							<?php $lo_variant = $lo_im->variant('header-sidebar'); ?>
							<?php if (!$lo_variant) $lo_variant = $lo_im->variant('header'); ?>
							<?php $lo_url = Stored_File::url_from_filename($lo_variant->filename); ?>
							<div class="marbot-15">
								<img alt="<?= $vd->esc($ci->newsroom->company_name) ?>" 
									src="<?= $lo_url ?>" />
							</div>
							<?php endif ?>
							<?php endif ?>
							
							<?php if (@$vd->nr_profile->website) : ?>
								<div class="aside-website marbot">
									<a href="<?= $vd->esc($vd->nr_profile->website) ?>">
									<i class="icon-globe"></i> Visit Website</a></div>
							<?php endif; ?>
							
							<?php if (@$vd->nr_profile->summary): ?>
							
								<p><?= nl2p($vd->esc($vd->nr_profile->summary)) ?></p>
																
								<?php if (!$ci->is_common_host): ?>									
									<p><a href="about">Learn More &#187;</a></p>	
								<?php endif ?>
							
							<?php elseif (@$vd->nr_profile->description): ?>
							
								<?php $desc = strip_tags($vd->nr_profile->description); ?>
								<?php if (strlen($desc) > 1000): ?>
								<p><?= nl2p($vd->esc($vd->cut($desc, 1000))) ?> ...</p>
								<?php else: ?>
								<p><?= nl2p($vd->esc($desc)) ?></p>
								<?php endif ?>
								
								<?php if (!$ci->is_common_host): ?>									
									<p><a href="about">Learn More &#187;</a></p>	
								<?php endif ?>
							
							<?php endif ?>
							
							<?= $ci->load->view('browse/partials/socials') ?>
							
						</div>
					</section>
					
					<?php endif ?>

					<?php if ($vd->wide_view): ?>	
									
					<?= $this->load->view('browse/partials/address') ?>	
									
					<?php endif ?>
					
				</aside>
		
			</div>
		</div>

		<?php if (!$ci->is_own_domain): ?>
		<footer class="footer">
			<div class="container">
				<div class="row-fluid">
					<div class="span3">
						<a href="<?= $ci->conf('website_url') ?>" class="footer-brand footer-brand-logo">iNews<b>Wire</b></a>
					</div>
					<div class="span9">
						<nav class="footer-menu">
							<ul>
								<li><a href="<?= $ci->conf('website_url') ?>helpdesk/">
									<i class="icon-book"></i> Support</a></li>
								<li><a href="<?= $ci->conf('website_url') ?>about-us">
									<i class="icon-info-sign"></i> About iNewsWire</a></li>
								<?php if (Auth::is_user_online()): ?>				
								<li><a href="manage">
									<i class="icon-signin"></i> Control Panel</a></li>
								<?php else: ?>					
								<li><a href="shared/login">
									<i class="icon-signin"></i> Login</a></li>
								<!-- <li><a href="<?= $ci->conf('website_url') ?>pricing-plans">
									<i class="icon-circle-arrow-right"></i> Get a Newsroom</a></li> -->
								<?php endif ?>
							</ul>
						</nav>

					</div>
				</div>
			</div>
		</footer>
		<?php endif ?>
		
		<div id="ln-container-loader"></div>
		
		<?php if ($ci->eob): ?>
		<div id="eob">
			<?php foreach ($ci->eob as $eob) 
				echo $eob; ?>
		</div>
		<?php endif ?>

		<script src="<?= $vd->assets_base ?>lib/imagesloaded.min.js"></script>
		<script src="<?= $vd->assets_base ?>lib/masonry.min.js"></script>
		<script src="<?= $vd->assets_base ?>js/nav.js?<?= $vd->version ?>"></script>
		
		<?php if (!$ci->is_detached_host): ?>
		<?= $ci->load->view('partials/piwik', null, true); ?>
		<?= $ci->load->view('partials/ganal', null, true); ?>
		<?php endif ?>
		
	</body>
</html>