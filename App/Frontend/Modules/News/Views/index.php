<?php
foreach ($listeNews as $news)
{
?>
  <h2><a href="news-<?= htmlspecialchars($news['id']) ?>.html"><?= htmlspecialchars($news['titre']) ?></a></h2>
  <p><?= nl2br(htmlspecialchars($news['contenu'])) ?></p>
<?php
}