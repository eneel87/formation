<!DOCTYPE html>
<html>
  <head>
    <title>
      <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
    
    <meta charset="utf-8" />

    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
          <?php if (!$user->isAuthenticated() ) {?>
          <li><a href="/admin/">Connexion</a></li> <?php } ?>
          <?php if ($user->isAuthenticated()) { ?>
          <?php if($user->getAttribute('admin')->level()==1){ ?>
          <li><a href="/admin/members.html">Gestion des utilisateurs</a></li><?php } ?>
          <li><a href="/admin/news.html">Gestion des news</a></li>
          <li><a href="/admin/deconnexion.html">Déconnexion de <?php echo htmlspecialchars($user->getAttribute('admin')->login()); } ?></a></li>

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