/**
 * Created by CCI on 3/10/2015.
 */
var app = angular.module('cnit', []);
app.run(function($rootScope){
    $rootScope.Name = "CNIT";
});
app.controller('cnitController', ['$scope', function($scope){
    $scope.Name = "C-NIT";
}]);

app.controller('PlayerController', ['$scope', function($scope){
    $scope.playing = false;
    $scope.audio = document.createElement('audio');
    $scope.audio.src = './app/assets/media/audio/TinhYeuMauNangLive-ThuyChi-2913670.mp4';
    $scope.play = function(){
        $scope.audio.play();
        $scope.playing = true;
    };
    $scope.stop = function(){
        $scope.audio.pause();
        $scope.playing = false;
    };
    $scope.audio.addEventListener('tat', function(){
       $scope.$apply(function(){
           $scope.stop();
       });
    });
}]);

app.controller('RelatedController', ['$scope', function($scope){

}]);