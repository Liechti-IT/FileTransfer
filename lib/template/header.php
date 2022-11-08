<?php
header('Content-Type: text/html; charset=utf-8');
header('x-ua-compatible: ie=edge');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Liechti-IT - FileUpload</title>
  <link id="shortcut_icon" rel="shortcut icon" href="<?php echo 'media/' . $cfg['style'] . '/favicon.ico'; ?>">
  <link id="stylesheet" rel="stylesheet" href="<?php echo 'media/' . $cfg['style'] . '/style.css.php'; ?>" type="text/css" />
  <link rel="icon" type="image/x-icon" href="media/favicon.ico">
</head>
<body>
<script type="text/javascript" src="<?php echo 'lib/functions.js.php'; ?>"></script>
<script type="text/javascript" lang="Javascript">color_scheme_preferences();</script>
<div id="content">
  <h1>
    <a href="./">
      <?php echo (true === empty($cfg['title']))? t('JI_WEB_RE') : $cfg['title']; ?>
    </a>
  </h1>
