<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ApiMCFly</title>
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
        <link rel="stylesheet" href="css/app.css">
    </head>
    <body ng-app="authApp">
        <!-- Static navbar -->
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a href="#" class="navbar-brand">ApiMCFly</a>

            </div>
            <ul class="nav navbar-nav navbar-right">
                  <li ng-if="authenticated">
                      <p class="navbar-btn">
                          <a href="#" class="btn btn-danger" ng-click="logout()">Logout</a>
                      </p>
                  </li>
              </ul>
          </div><!--/.container-fluid -->
        </nav>

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
