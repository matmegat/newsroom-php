<div class="chunkination pagination pagination-centered">
	
	<ul>
		
		<?php if ($first === null): ?>
		<li class="disabled"><a>First</a></li>
		<?php else: ?>
		<li><a href="<?= $first->url ?>">First</a></li>
		<?php endif ?>
		
		<?php if ($prev === null): ?>
		<li class="disabled"><a>Prev</a></li>
		<?php else: ?>
		<li><a href="<?= $prev->url ?>">Prev</a></li>
		<?php endif ?>
		
	</ul>
	
	<ul>
		
		<?php if ($prev_2 !== null): ?>
		<li>
			<a href="<?= $prev_2->url ?>"><?= $prev_2->chunk ?></a>
		</li>
		<?php endif ?>
		
		<?php if ($prev !== null): ?>
		<li>
			<a href="<?= $prev->url ?>"><?= $prev->chunk ?></a>
		</li>
		<?php endif ?>
		
		<li class="active">
			<a href="<?= $current->url ?>">
				<?= $current->chunk ?>
			</a>
		</li>
		
		<?php if ($next !== null): ?>
		<li>
			<a href="<?= $next->url ?>"><?= $next->chunk ?></a>
		</li>
		<?php endif ?>
		
		<?php if ($next_2 !== null): ?>
		<li>
			<a href="<?= $next_2->url ?>"><?= $next_2->chunk ?></a>
		</li>
		<?php endif ?>
		
	</ul>
	
	<ul>
		
		<?php if ($next === null): ?>
		<li class="disabled"><a>Next</a></li>
		<?php else: ?>
		<li><a href="<?= $next->url ?>">Next</a></li>
		<?php endif ?>
		
		<?php if ($last === null): ?>
		<li class="disabled"><a>Last</a></li>
		<?php else: ?>
		<li><a href="<?= $last->url ?>">Last</a></li>
		<?php endif ?>
		
	</ul>	
	
</div>