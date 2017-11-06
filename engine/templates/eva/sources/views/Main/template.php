<!DOCTYPE html>
<html>
<head>
    <title><?php echo Engine::getAppName()?></title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
  <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <?php hlp::dynamicCss();?>
</head>
<body>
  <?php EView::getView();?>
  <script src="https://unpkg.com/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vue-router@3.0.1/dist/vue-router.js "></script>
  <script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
  <?php hlp::dynamicJs();?>
</body>
</html>
