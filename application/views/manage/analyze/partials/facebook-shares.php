<strong><?= $vd->facebook_shares ?></strong>
<?php if ($vd->m_content->post_id_facebook): ?>
<a href="https://www.facebook.com/<?= $vd->m_content->post_id_facebook ?>">
<em>Facebook</em></a>
<?php else: ?>
<em>Facebook</em>
<?php endif ?>