<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $this->escape($title) . ' - ' : '' ?>Mini Blog</title>
</head>
<body>
  <div id="header">
    <h1>
      <a href="<?= $base_url ?>"></a>
    </h1>
  </div>
  <div id="main">
    <?= $_content ?>
  </div>
</body>
</html>