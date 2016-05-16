<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ApiMCFly</title>
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    </head>
    <body ng-app="authApp">

        <div class="container">
            <div ui-view></div>
        </div>        

    </body>

    <!-- Application Dependencies -->
    <script src="node_modules/angular/angular.min.js"></script>
    <script src="node_modules/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="node_modules/satellizer/satellizer.min.js"></script>

    <!-- Application Scripts -->
    <script src="js/app.js"></script>
    <script src="js/authController.js"></script>
    <script src="js/userController.js"></script>
</html>