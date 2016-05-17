(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $auth, $rootScope, $state) {

        var vm = this;

        vm.users;
        vm.error;

        vm.getUsers = function () {

            //Grab the list of users from the API
            $http.get('api/authenticate').success(function (users) {
                vm.users = users;
            }).error(function (error) {
                vm.error = error;
            });
        }
    }

})();
