angular
.module('config', ['ngMaterial'])
.controller('AppConfigCtrl', ['$scope', '$mdDialog', '$mdMedia', '$http', function($scope, $mdDialog, $mdMedia, $http) {
  // Get config
  $scope.config = {sdk_path:"", mapping_path:""};
  $http.get('../api/configGet.php').then(
    function successCallback(response) {
      $scope.config = response.data;
    }, 
    function errorCallback(response) {
        // TODO
    }
  );
  // Dialog
  $scope.status = '  ';
  $scope.customFullscreen = $mdMedia('xs') || $mdMedia('sm');
  $scope.showAdvanced = function(ev) {
    var useFullScreen = ($mdMedia('sm') || $mdMedia('xs'))  && $scope.customFullscreen;
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'configuration.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      fullscreen: useFullScreen
    });
    $scope.$watch(function() {
      return $mdMedia('xs') || $mdMedia('sm');
    }, function(wantsFullScreen) {
      $scope.customFullscreen = (wantsFullScreen === true);
    });
  };

  // Save
  $scope.saveConfig = function() {
    $mdDialog.hide();
        // Request
        $http.post('../api/configUpdate.php', $scope.config).then(
            function successCallback(response) {
            }, 
            function errorCallback(response) {
              // TODO
            }
        );
  }

}]);
function DialogController($scope, $mdDialog) {
  $scope.hide = function() {
    $mdDialog.hide();
  };
  $scope.cancel = function() {
    $mdDialog.cancel();
  };
  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };
}