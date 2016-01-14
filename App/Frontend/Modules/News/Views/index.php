<?php
foreach ($listeNews as $news)
{
?>
  <h2><a href="<?=$router->getUrl('Frontend', 'News', 'show', array('id' => $news['id'])) ?>"><?= htmlspecialchars($news['titre']) ?></a></h2>
  <p><?= nl2br(htmlspecialchars($news['contenu'])) ?></p>
<?php
}