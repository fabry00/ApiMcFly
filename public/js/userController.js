(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $auth, $rootScope, $state) {

        var vm = this;

        vm.error;
        vm.newNote = {
          text : "",
          public : false,
          favorite : false
        }
        vm.addNote = function(){
          console.log(vm.newNote);
          if(vm.newNote == ''){
            alert("Insert text");
            return;
          }
          $http.put('api/auth/note',vm.newNote).then(function (response) {
              init();
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        }

        vm.getUserNotes = function(callback){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/notes').then(function (response) {
              vm.user_notes = response.data;
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        };

        vm.getUserFavNotes = function(callback){
          // retrieve user notes
          // this sould be provided by a service or a different module
          $http.get('api/auth/user/favnotes').then(function (response) {
              vm.user_fav_notes = response.data;
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
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
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        };

        vm.addToFavorite = function(id){
          $rootScope.setLoading(true);
          $http.post('api/auth/user/favorite',{noteid:id,fav:true}).then(function (response) {
              init();
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        };

        vm.remToFavorite = function(id){
          $rootScope.setLoading(true);
          $http.post('api/auth/user/favorite',{noteid:id,fav:false}).then(function (response) {
              init();
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        };

        vm.unpublish = function(id)
        {
          $rootScope.setLoading(true);
          $http.post('api/auth/user/publish',{noteid:id,publish:false}).then(function (response) {
              init();
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
        }

        vm.publish = function(id)
        {
          $rootScope.setLoading(true);
          $http.post('api/auth/user/publish',{noteid:id,publish:true}).then(function (response) {
              init();
          },function (data) {
            $rootScope.setLoading(false);
            alert("Error: "+data.message);
          });
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
