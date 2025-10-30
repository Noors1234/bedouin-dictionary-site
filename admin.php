<?php
// admin.php — لوحة إدارة بسيطة
// ملاحظة: هذه لوحة إدارة مبسطة للمشاريع الصغيرة. عند النشر العملي على الإنترنت
// يُنصح باستخدام آليات مصادقة أقوى (HTTP auth, OAuth, أو لوحة إدارة داخلية مؤمنة).

header('Content-Type: text/html; charset=utf-8');
$audioDir = __DIR__ . '/audio';
$allowedExt = ['wav','txt'];
$maxSize = 25 * 1024 * 1024; // 25MB limit per file (قابل للتعديل)

// Upload handling
$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $f = $_FILES['file'];
  if ($f['error'] !== UPLOAD_ERR_OK) {
    $messages[] = 'حدث خطأ أثناء الرفع.';
  } else {
    $name = basename($f['name']);
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
      $messages[] = 'نوع الملف غير مسموح. يسمح فقط بـ .wav و .txt';
    } elseif ($f['size'] > $maxSize) {
      $messages[] = 'حجم الملف أكبر من الحد المسموح.';
    } else {
      // sanitize filename: remove dangerous chars
      $safe = preg_replace('/[^A-Za-z0-9\x{0600}-\x{06FF}_\-\. ]+/u', '', $name);
      $target = $audioDir . '/' . $safe;
      if (move_uploaded_file($f['tmp_name'], $target)) {
        $messages[] = 'تم رفع الملف بنجاح: ' . $safe;
      } else {
        $messages[] = 'فشل نقل الملف إلى المجلد.';
      }
    }
  }
}

// Delete handling
if (isset($_GET['delete'])) {
  $del = basename($_GET['delete']);
  $path = $audioDir . '/' . $del;
  if (is_file($path) && strpos(realpath($path), realpath($audioDir)) === 0) {
    unlink($path);
    $messages[] = 'تم حذف الملف: ' . $del;
  } else {
    $messages[] = 'لم يتم العثور على الملف للحذف.';
  }
}

$files = array_values(array_diff(scandir($audioDir), array('.', '..')));
function safeFileName($name){ return htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>لوحة إدارة — القاموس البدوي الصوتي</title>
  <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container admin">
    <h1>لوحة إدارة الملفات</h1>

    <?php foreach ($messages as $m): ?>
      <div class="msg"><?php echo safeFileName($m); ?></div>
    <?php endforeach; ?>

    <section class="upload">
      <h2>رفع ملف (.wav أو .txt)</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept="audio/wav,text/plain" required>
        <button type="submit">رفع</button>
      </form>
      <p class="note">ملحوظة: لا ترفع أي بيانات حساسة. الملفات النصية يجب أن تكون وصفًا ثقافيًا فقط.</p>
    </section>

    <section class="files">
      <h2>الملفات الحالية</h2>
      <ul>
        <?php foreach ($files as $f): ?>
          <li>
            <?php echo safeFileName($f); ?>
            <a href="audio/<?php echo rawurlencode($f); ?>" target="_blank">عرض</a>
            <a href="?delete=<?php echo rawurlencode($f); ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

  </main>
</body>
</html>
