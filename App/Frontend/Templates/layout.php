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

  <?= $menu ?>

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