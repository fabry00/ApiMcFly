(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $auth, $rootScope, $state) {

        var vm = this;

        vm.error;
        vm.public_notes = [];

        vm.getUserNotes = function(){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/notes').then(function (response) {
              vm.user_notes = response.data;
          });
        };

        vm.getUserFavNotes = function(){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/favnotes').then(function (response) {
              vm.user_fav_notes = response.data;
          });
        };
        vm.getPublicNotes = function(){
          // retrieve public notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/notes/public').then(function (response) {

              $rootScope.setLoading(false);
              vm.public_notes = response.data;
          });
        };



        vm.addToFavorite = function(id){
          alert("add to fav "+id);
        };

        vm.remToFavorite = function(id){
          alert("rem to fav "+id);
        };

        vm.unpublish = function(id)
        {
          alert("unpublish "+id);
        }

        vm.publish = function(id)
        {
          alert("publish "+id);
        }

        function init(){
            $rootScope.setLoading(true);
            vm.getUserNotes();
            vm.getUserFavNotes();
            vm.getPublicNotes();
        }
        init();

    }

})();
