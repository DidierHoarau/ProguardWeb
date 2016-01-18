angular
.module('trace', ['ngMaterial'])
.controller('TraceCtrl', ['$scope', '$http', function($scope, $http) {

    $scope.param = {trace:"", app:""};
    $scope.result = {output:"", return_code:"", show:false, processing:false};

    // Get the list of apps
    $scope.appList = [ ];
    $http.get('../api/appList.php').then(
        function successCallback(response) {
            $scope.appList = response.data;
        }, 
        function errorCallback(response) {
            // TODO
        }
    );

    // Execute the retrace
    $scope.retrace = function() {
        $scope.param.app = $scope.app;
        // Loading UI
        $scope.result.output = "Loading retrace";
        $scope.result.show = true;
        $scope.result.processing = true;
        var data = { "trace": $scope.param.trace, "app": $scope.param.app };
        // Request
        $http.post('../api/retrace.php', data).then(
            function successCallback(response) {
                $scope.result.processing = false;                
                if (response.data.return_code==0) {
                    $scope.result.output = response.data.retrace_output;
                    $scope.result.processing = false;                
                } else {
                    $scope.result.output = "Error: "+response.data.error_message;
                }
            }, 
            function errorCallback(response) {
                $scope.result.processing = false;
                $scope.result.output = "Error geting retrace";
            }
        );
    }

    // Reset Output
    $scope.reset_output = function() {
        $scope.result.processing = false;
        $scope.result.show = "";
        $scope.result.output = "";
    }
}]);
