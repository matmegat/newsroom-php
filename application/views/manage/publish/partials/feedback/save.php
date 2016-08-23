<div class="alert alert-success">
	<strong>Saved!</strong> The content has been saved.
	You can now <a href="view/<?= $m_content->slug ?>">view</a> or 
	<a href="manage/publish/<?= $content_type ?>/edit/<?= $m_content->id ?>">edit</a>
	the content. 
</div>

<?php if (@$post['view_now']): ?>
<script> window.open(<?= json_encode($ci->common()->url(
	$m_content->url())) ?>, "_blank"); </script>
<?php endif ?>