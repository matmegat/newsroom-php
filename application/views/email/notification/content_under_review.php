The <?= strtolower(Model_Content::full_type($content->type)) ?> submission detailed below is now under review. 
Our staff will review the content within the next few hours. We will notify you again when the content goes live. 

<br><br><code><b><?= $vd->esc($content->title) ?></b> 
(<?= Date::out($content->date_publish, $timezone)->format('M j, Y') ?>)</code>