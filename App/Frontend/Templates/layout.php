<!DOCTYPE html>
<html>
<head>
  <title>
    <?= isset($title) ? $title : 'Mon super site' ?>
  </title>

  <meta charset="utf-8" />

  <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
</head>

<body>
<div id="wrap">
  <header>
    <h1><a href="/">Mon super site</a></h1>
    <p>Comment ça, il n'y a presque rien ?</p>
  </header>

  <nav>
    <ul>
      <li><a href="/">Accueil</a></li>
      <?php if ($user->isAuthenticated()== false ) {?>
        <li><a href="/admin/">Connexion</a></li> <?php }
      else {?>
      <li><a href="/admin/">Administration</a></li>
      <li><a href="/admin/deconnexion.html">Déconnexion de <?php echo $user->getAttribute('admin')->login(); } ?></a></li>
    </ul>
  </nav>

  <div id="content-wrap">
    <section id="main">
      <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

      <?= $content ?>
    </section>
  </div>

  <footer></footer>
</div>
</body>
</html>