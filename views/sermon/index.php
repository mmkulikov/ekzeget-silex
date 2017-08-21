<?php
/**
 * @var \Application $app
 * @var Commentaries $this
 * @var \Propel\Runtime\Collection\Collection|\models\Tradition[] $commentaries
 * @var string $assets
 * @var boolean $isResearch
 * @var string $bookCode
 * @var integer $chapterNum
 */
use services\URLS;

?>
<div class="news-list">
<hr class="ekz">
                        <ul class="news">
<?php
foreach($events as $event):?>
<li> <h4><a style="font-size: 16px;" href="/sermon/<?= $event->getId()?>/"><?= $event->getName()?>
</a></h4>
</li>
<?php endforeach;

    ?>

</ul>
</div>