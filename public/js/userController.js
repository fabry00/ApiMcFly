(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $auth, $rootScope, $state) {

        var vm = this;

        vm.error;
        vm.public_notes = [];

        vm.getUserNotes = function(callback){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/notes').then(function (response) {
              vm.user_notes = response.data;
          });
        };

        vm.getUserFavNotes = function(callback){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/favnotes').then(function (response) {
              vm.user_fav_notes = response.data;
          });
        };
        vm.getPublicNotes = function(callback){
          // retrieve public notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/notes/public').then(function (response) {
              vm.public_notes = response.data;
              if(typeof callback != 'undefined'){
                callback();
              }
          });
        };

        vm.addToFavorite = function(id){
          $rootScope.setLoading(true);
          $http.post('api/auth/user/favorite',{noteid:id,fav:true}).then(function (response) {
              init();
          });
        };

        vm.remToFavorite = function(id){
          $rootScope.setLoading(true);
          $http.post('api/auth/user/favorite',{noteid:id,fav:false}).then(function (response) {
              init();
          });
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
            vm.getPublicNotes(function(){$rootScope.setLoading(false);});
        }
        init();

    }

})();
