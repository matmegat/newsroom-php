<section class="form-section supporting_quote">
	<h2>
		Supporting Quote
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_SQ ?>">
			<i class="icon-question-sign"></i>
		</a>	
	</h2>
	<ul>
		<li>
			<textarea class="in-text span12" name="supporting_quote" 
				placeholder="Enter Supporting Quote"><?= 
				$vd->esc(@$vd->m_content->supporting_quote)
			?></textarea>
		</li>
		<li>
			<div class="row-fluid">				
				<div class="span6">
					<input name="supporting_quote_name" placeholder="Name of Person"
						value="<?= $vd->esc(@$vd->m_content->supporting_quote_name) ?>"
						class="in-text span12" type="text"  />
				</div>
				<div class="span6">
					<input name="supporting_quote_title" placeholder="Title of Person"
						value="<?= $vd->esc(@$vd->m_content->supporting_quote_title) ?>"
						class="in-text span12" type="text"  />
				</div>
			</div>
		</li>
	</ul>
</section>