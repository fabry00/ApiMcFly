(function () {

    'use strict';

    angular
            .module('authApp', ['ui.router', 'satellizer'])
            .config(function ($stateProvider, $urlRouterProvider, $authProvider, $httpProvider, $provide) {

                function redirectWhenLoggedOut($q, $injector) {

                    return {
                        responseError: function (rejection) {

                            // Need to use $injector.get to bring in $state or else we get
                            // a circular dependency error
                            var $state = $injector.get('$state');

                            // Instead of checking for a status code of 400 which might be used
                            // for other reasons in Laravel, we check for the specific rejection
                            // reasons to tell us if we need to redirect to the login state
                            var rejectionReasons = ['token_not_provided', 'token_expired', 'token_absent', 'token_invalid'];

                            // Loop through each rejection reason and redirect to the login
                            // state if one is encountered
                            angular.forEach(rejectionReasons, function (value, key) {

                                if (rejection.data.error === value) {

                                    // If we get a rejection corresponding to one of the reasons
                                    // in our array, we know we need to authenticate the user so
                                    // we can remove the current user from local storage
                                    localStorage.removeItem('user');

                                    // Send the user to the auth state so they can login
                                    $state.go('auth');
                                }
                            });

                            return $q.reject(rejection);
                        }
                    }
                }

                // Setup for the $httpInterceptor
                $provide.factory('redirectWhenLoggedOut', redirectWhenLoggedOut);

                // Push the new factory onto the $http interceptor array
                $httpProvider.interceptors.push('redirectWhenLoggedOut');

                $authProvider.loginUrl = '/api/public/authenticate';

                $urlRouterProvider.otherwise('/auth');

                $stateProvider
                        .state('auth', {
                            url: '/auth',
                            templateUrl: '../views/authView.html',
                            controller: 'AuthController as auth'
                        })
                        .state('users', {
                            url: '/users',
                            templateUrl: '../views/userView.html',
                            controller: 'UserController as user'
                        });
            })
            .run(function ($rootScope, $state,$auth) {

                $rootScope.setLoading = function(loading) {
			             $rootScope.isLoading = loading;
		            }
                // We would normally put the logout method in the same
                // spot as the login method, ideally extracted out into
                // a service. For this simpler example we'll leave it here
                $rootScope.logout = function(){
                  $auth.logout().then(function () {

                      // Remove the authenticated user from local storage
                      localStorage.removeItem('user');

                      // Flip authenticated to false so that we no longer
                      // show UI elements dependant on the user being logged in
                      $rootScope.authenticated = false;

                      // Remove the current user info from rootscope
                      $rootScope.currentUser = null;

                      // Redirect to auth (necessary for Satellizer 0.12.5+)
                      $state.go('auth');
                  });
                };
                // $stateChangeStart is fired whenever the state changes. We can use some parameters
                // such as toState to hook into details about the state as it is changing
                $rootScope.$on('$stateChangeStart', function (event, toState) {

                    // Grab the user from local storage and parse it to an object
                    var user = JSON.parse(localStorage.getItem('user'));

                    // If there is any user data in local storage then the user is quite
                    // likely authenticated. If their token is expired, or if they are
                    // otherwise not actually authenticated, they will be redirected to
                    // the auth state because of the rejected request anyway
                    if (user) {

                        // The user's authenticated state gets flipped to
                        // true so we can now show parts of the UI that rely
                        // on the user being logged in
                        $rootScope.authenticated = true;

                        // Putting the user's data on $rootScope allows
                        // us to access it anywhere across the app. Here
                        // we are grabbing what is in local storage
                        $rootScope.currentUser = user;

                        // If the user is logged in and we hit the auth route we don't need
                        // to stay there and can send the user to the main state
                        if (toState.name === "auth") {

                            // Preventing the default behavior allows us to use $state.go
                            // to change states
                            event.preventDefault();

                            // go to the "main" state which in our case is users
                            $state.go('users');
                        }
                    }
                });
            })
            .directive("notesContainer", function() {
              return {
                restrict : "E",
                templateUrl : "/directives/notesContainer.html",
                scope:{
                  containerClass: '@containerClass',
                  notes: "=notes",
                  showPublic: "=showpublic",
                  showattributes:"=showattributes",
                  showfavorite: "=showfavorite",
                  showdelete:"=showdelete",
                  addToFav: '&',
                  remToFav: '&',
                  publish : '&',
                  unpublish : '&',
                  deleteNote : '&',
                },
                link: function (scope) {
                  scope.addToFavorite = function (noteid) {
                      console.log("addToFavorite");
                      scope.addToFav({id: noteid});
                  };
                  scope.remToFavorite = function (noteid) {
                      console.log("remToFavorite");
                      scope.remToFav({id: noteid});
                  };

                  scope.publishNote = function (noteid) {
                      console.log("publish");
                      scope.publish({id: noteid});
                  };

                  scope.unpublishNote = function (noteid) {
                      console.log("unpublish");
                      scope.unpublish({id: noteid});
                  };

                  scope.deleteTheNote = function(noteid) {
                      console.log("deleteTheNote");
                      scope.deleteNote({id: noteid});
                  }


                }
            };
        });
})();
