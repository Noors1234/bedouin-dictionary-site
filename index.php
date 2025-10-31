<?php
$dir = "audio/";
$files = array_filter(scandir($dir), function($f){
  return pathinfo($f, PATHINFO_EXTENSION) === "wav";
});
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>القاموس البدوي الصوتي</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <h1>القاموس البدوي الصوتي</h1>
    <p class="lead">توثيق النطق البدوي والخليجي والحوراني بصوت بشري</p>
    <a class="download" href="info.html">عن المشروع</a>
  </header>

  <main class="container grid">
    <?php foreach($files as $file): 
      $base = pathinfo($file, PATHINFO_FILENAME);
      $txt = $dir . $base . ".txt";
      $desc = file_exists($txt) ? file_get_contents($txt) : "لا يوجد وصف نصي.";
    ?>
    <div class="card">
      <h2><?= htmlspecialchars($base) ?></h2>
      <audio class="audio" controls src="<?= $dir . $file ?>"></audio>
      <p class="desc"><?= nl2br(htmlspecialchars($desc)) ?></p>
    </div>
    <?php endforeach ?>
  </main>

  <footer class="site-footer">
    &copy; نورس الجميلي — أبو خليل المجابلة
  </footer>
</body>
</html>
