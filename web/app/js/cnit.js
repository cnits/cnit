/**
 * Created by CCI on 3/10/2015.
 */
var app = angular.module('cnit', []);
app.run(function ($rootScope) {
    $rootScope.Name = "CNIT";
});
app.controller('cnitController', ['$scope', function ($scope) {
    $scope.Name = "C-NIT";
}]);

app.controller('PlayerController', ['$scope', function ($scope) {
    $scope.playing = false;
    $scope.audio = document.createElement('audio');
    $scope.audio.src = './app/assets/media/audio/TinhYeuMauNangLive-ThuyChi-2913670.mp4';
    $scope.play = function () {
        $scope.audio.play();
        $scope.playing = true;
    };
    $scope.stop = function () {
        $scope.audio.pause();
        $scope.playing = false;
    };
    $scope.audio.addEventListener('tat', function () {
        $scope.$apply(function () {
            $scope.stop();
        });
    });
}]);

app.controller('RelatedController', ['$scope', function ($scope) {
    $scope.T = "";
    $scope.$watch("T", function(v) {
        console.log("Change", v);
    });
}]);

app.directive("tplNumber", function () {
    return {
        restrict: "E",
        replace: true,
        scope: {
            max: "@",
            ngVal: "=",
            digitNumber: "@"
        },
        template: '<input type="text" ng-keydown="bindNumber($event)" ng-blur="validateNumber()"/>',
        compile: function () {
            return {
                post: function (scope, ele, attr, formCtrl) {
                    var dv = "0.000000000000000000000000000000000";
                    ele.val(dv.substr(0, parseInt(scope.digitNumber) + 2));

                    scope.bindNumber = function (e) {
                        var arrayAllow = [46, 8, 27, 13, 38, 40, 190, 110];
                        if (e.keyCode >= 48 && e.keyCode <= 57) {
                            var iVal = String.fromCharCode(e.keyCode);
                            if(checkMaxValue(ele.val(), iVal)) {
                                e.preventDefault();
                            }
                            var point = ele.val().toString().indexOf(".");
                            if (-1 !== point && e.target.selectionStart > point) {
                                if (e.target.selectionStart >= ele.val().length) {
                                    var extStr = ele.val().substr(point + 1);
                                    if (extStr.length >= parseInt(scope.digitNumber)) {
                                        ele.val(replaceAt(ele.val(), ele.val().length - 1, iVal));
                                    }
                                } else {
                                    var cur_pos = e.target.selectionStart;
                                    ele.val(replaceAt(ele.val(), e.target.selectionStart, iVal));
                                    e.target.selectionStart = cur_pos + 1;
                                }
                                scope.ngVal = ele.val();
                            }
                        }
                        if ((e.target.value.indexOf('.') !== -1) && ([190, 110].indexOf(e.keyCode) !== -1)) {
                            e.preventDefault();
                        }
                        if ((arrayAllow.indexOf(e.keyCode) !== -1) ||
                            (e.keyCode === 65 && e.ctrlKey === true) || (e.keyCode === 67 && e.ctrlKey === true) ||
                            (e.keyCode === 88 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // Allow: delete, backspace, escape, enter , Ctrl+A+C+X , home, end, left, right, point
                            if(ele.val() == "") {
                                ele.val(0);
                            }
                            scope.ngVal = ele.val();
                            return;
                        }
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                        if ((e.which === 46) && (e.target.value.indexOf('.') === -1)) {
                            setTimeout(function () {
                                if (e.target.value.substring(e.target.value.indexOf('.')).length > 3) {
                                    ele.val(e.target.value.substring(0, e.target.value.indexOf('.') + 3));
                                }
                            }, 1);
                        }
                        if (parseInt(ele.val()) >= parseInt(e.target.max)) {
                            e.preventDefault();
                        }
                        if ((e.target.value.indexOf('.') !== -1) &&
                            (e.target.value.substring(e.target.value.indexOf('.')).length > scope.digitNumber) &&
                            (e.which !== 0 && e.which !== 8) &&
                            (e.target.selectionStart >= e.target.value.length - parseInt(scope.digitNumber))) {
                            e.preventDefault();
                        }
                    };
                    scope.validateNumber = function () {
                        var s = ele.val().toString();
                        if (s.substr(-1) === ".") {
                            ele.val(s.substring(0, s.length - 1));
                        } else if (s.substr(0, 1) === ".") {
                            ele.val("0" + s);
                        } else {
                            //Anything else
                        }
                    };

                    function replaceAt(str, index, replace) {
                        return str.substr(0, index) + replace + str.substr(index + replace.length);
                    }

                    function checkMaxValue(c, i) {
                        if(!c || !i) {
                            return false;
                        }
                        var s = c.toString(), p = s.indexOf(".");
                        var l = s.substr(0, p), r = s.substr(p + 1), v = "";
                        if(r.length > 0) {
                            v = parseFloat(l.toString() + i.toString() + "." + r);
                        } else {
                            v = parseFloat(l.toString() + i.toString());
                        }
                        console.log("FFFFFF", c, v, r, scope.ngVal);
                        if(isFinite(scope.max) && v > parseInt(scope.max)) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            };
        }
    };
});