<html>
    <head>
        <title><?php echo Engine::getAppName(), ' - ',  Engine::getAppVersion()?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php echo hlp::link('framework')?>
        <?php echo hlp::link('style')?>
        <?php echo hlp::loadJScript('jqueryui')?>
        <?php echo hlp::dynamicJs();//carga automaticamente las scripts puestas en /js/views/ControllerName/*.js?>
    </head>
    <body>
        <div id="wrapper">
            <div id="workspace">
                <div id="content"><?php  EView::getView()?></div>
            </div> 
        </div>
		<div id="general-dialog"></div>  <!-- div destinado para crear los dialogos de jquery ui -->      
    </body>
</html>
