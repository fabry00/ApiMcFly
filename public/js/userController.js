(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $auth, $rootScope, $state) {

        var vm = this;

        vm.error;
        vm.public_notes = [];

        vm.getPublicNotes = function(){
          // retrieve public notes
          // this sould be provided by a service or a different module
          $http.get('api/public/notes/public').then(function (response) {

              $rootScope.setLoading(false);
              vm.public_notes = response.data;
          });
        };

        vm.getUserNotes = function(){
          // retrieve public notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/notes').then(function (response) {
              console.log("AAAA");
          });
        };

        function init(){
            $rootScope.setLoading(true);
            vm.getUserNotes();
            vm.getPublicNotes();
        }
        init();

    }

})();
