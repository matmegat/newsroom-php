<section class="al-block aside-press-contact row-fluid">
	
	<h3>Press Contact</h3>

	<div class="aside-content">
		
		<?php $contact_image = Model_Image::find(@$vd->nr_contact->image_id); ?>
		
		<?php if (!$ci->is_common_host): ?>
		<a href="view/contact/<?= $vd->nr_contact->id ?>">
		<?php endif ?>
		
			<?php if (@$contact_image): ?>			
				<?php $ci_variant = $contact_image->variant('contact'); ?>
				<?php $ci_url = Stored_File::url_from_filename($ci_variant->filename); ?>
				<img src="<?= $ci_url ?>" alt="<?= $vd->esc($vd->nr_contact->name) ?>" />			
			<?php else: ?>
				<img src="<?= $vd->assets_base ?>im/contact_image.png" />			
			<?php endif ?>
		
		<?php if (!$ci->is_common_host): ?>
		</a>
		<?php endif ?>

		<div class="aside-content-block">
			<span class="aside-pc-name">
				<?php if ($ci->is_common_host): ?>
					<?= $vd->esc($vd->nr_contact->name) ?>
				<?php else: ?>
					<a href="view/contact/<?= $vd->nr_contact->id ?>"><?= 
					$vd->esc($vd->nr_contact->name) ?></a>
				<?php endif ?>
			</span>
			<span class="aside-pc-position">
				<?= $vd->esc($vd->nr_contact->title) ?>
			</span>
			<ul>
				
				<?php if ($vd->nr_contact->email): ?>
				<li>
					<a href="mailto:<?= $vd->esc(strrev($vd->nr_contact->email)) ?>" 
						target="_blank" class="email-obfuscated">
						<i class="icon-envelope-alt"></i> Email
					</a>
				</li>
				<?php endif ?>
				
				<?php if ($vd->nr_contact->twitter): ?>
				<li>
					<a href="http://www.twitter.com/<?= $vd->esc($vd->nr_contact->twitter) ?>" 
						target="_blank">
						<i class="icon-twitter-sign"></i> Twitter
					</a>
				</li>
				<?php endif ?>
				
				<?php if ($vd->nr_contact->facebook): ?>
				<li>
					<a href="http://www.facebook.com/<?= $vd->esc($vd->nr_contact->facebook) ?>" 
						target="_blank">
						<i class="icon-facebook-sign"></i> Facebook
					</a>
				</li>
				<?php endif ?>
				
			</ul>
		</div>
	</div>
</section>