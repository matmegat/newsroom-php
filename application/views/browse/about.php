<div class="span8 main-content">
	<section class="content-view">
		
		<header class="cv-header"></header>

		<div id="cv-container">
			
			<div class="inner-content">

				<div class="clearfix">
					<div class="company-top"> 
						<ul>
							<?php if (@$vd->nr_profile->website) : ?>
								<li><i class="icon-globe"></i> <a href="<?= 
									$vd->esc($vd->nr_profile->website) ?>">Visit Website</a></li>
							<?php endif; ?>
							<?php if (@$vd->nr_profile->email) : ?>
								<li><i class="icon-envelope-alt"></i> <a class="email-obfuscated"
									href="mailto:<?= $vd->esc(strrev($vd->nr_profile->email)) ?>">Email</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				
				<?php if (@$vd->nr_profile->beat_id): ?>
				<p class="company-details-info">
					<?php $beat = Model_Beat::find($vd->nr_profile->beat_id); ?>
					Industry: <strong><?= $vd->esc($beat->name) ?></strong>
				</p>
				<?php endif ?>
				
				<?php if (@$vd->nr_profile->year): ?>
				<p class="company-details-info">
					Founded: <strong><?= $vd->nr_profile->year ?></strong>
				</p>
				<?php endif ?>

				<?php if (@$vd->nr_profile->description) : ?>
				<div class="html-content">
					<?= $vd->nr_profile->description ?>
				</div>
				<?php endif; ?>

			</div>
			
		</div>
		
	</section>	
</div>