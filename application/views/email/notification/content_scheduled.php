We have received the <?= strtolower(Model_Content::full_type($content->type)) ?> submission detailed below. 
It has been stored in our database and scheduled for release. 

<br><br><code><b><?= $vd->esc($content->title) ?></b> 
(<?= Date::out($content->date_publish, $timezone)->format('M j, Y') ?>)</code>