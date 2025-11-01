<?php
// index.php — صفحة عرض التسجيلات
// UTF-8, RTL
header('Content-Type: text/html; charset=utf-8');

$audioDir = __DIR__ . '/audio';
$files = array_values(array_diff(scandir($audioDir), array('.', '..')));

function safeFileName($name){
    return htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>القاموس البدوي الصوتي</title>
  <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <h1>القاموس البدوي الصوتي</h1>
    <p class="lead">توثيق النطق البدوي والخليجي والحوراني — أرشيف صوتي بصيغة WAV</p>
  </header>

  <main class="container">
    <?php if (empty($files)): ?>
      <p class="empty">لا توجد ملفات صوتية في المجلد.</p>
    <?php else: ?>
      <div class="grid">
        <?php foreach ($files as $file):
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          if (!in_array($ext, ['wav','txt'])) continue;

          if ($ext === 'wav'):
            $base = pathinfo($file, PATHINFO_FILENAME);
            $txtFile = "$audioDir/$base.txt";
            $txtContent = is_file($txtFile) ? file_get_contents($txtFile) : "لا يوجد وصف.";
        ?>
        <article class="card">
          <h2><?php echo safeFileName($base); ?></h2>
          <audio controls preload="none">
            <source src="audio/<?php echo rawurlencode($file); ?>" type="audio/wav">
            متصفحك لا يدعم مشغل الصوت.
          </audio>
          <div class="desc">
            <pre><?php echo nl2br(safeFileName($txtContent)); ?></pre>
          </div>
          <a class="download" href="audio/<?php echo rawurlencode($file); ?>" download>تحميل .wav</a>
        </article>
        <?php endif; endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <footer class="site-footer">
    <p>حقوق الطبع والنشر © نورس الجميلي — القاموس البدوي الصوتي</p>
  </footer>
</body>
</html>
