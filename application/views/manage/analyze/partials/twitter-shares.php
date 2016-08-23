<strong><?= $vd->twitter_shares ?></strong>
<?php if ($vd->m_content->post_id_twitter): ?>
<a href="https://twitter.com/statuses/<?= $vd->m_content->post_id_twitter ?>">
<em>Twitter</em></a>
<?php else: ?>
<em>Twitter</em>
<?php endif ?>