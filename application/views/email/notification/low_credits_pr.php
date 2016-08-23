You are running low on premium press release credits.
<?php if ($stat->available > 0): ?>
You currently have just <strong><?= $stat->available ?></strong> credits available.
<?php else: ?>
You have <strong>0</strong> credits available.
<?php endif ?>
<br />
You can purchase <a href="<?= $ci->website_url('manage/upgrade') ?>">additional credits</a> 
from within your account control panel.