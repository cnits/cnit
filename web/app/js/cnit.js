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
    $scope.T = "0";
    $scope.T2 = "0";
    $scope.$watch("T", function (v) {
        console.log("Change 1", parseFloat(v));
    });
    $scope.$watch("T2", function (v) {
        console.log("Change 2", parseFloat(v));
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
                        if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                            var iVal = fromKeyCode(e.keyCode);
                            if (checkMaxValue(iVal)) {
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
                                    if (cur_pos + 1 >= ele.val().length) {
                                        e.target.selectionStart = ele.val().length - 1;
                                    } else {
                                        e.target.selectionStart = cur_pos + 1;
                                    }
                                }
                            }
                            scope.ngVal = ele.val();
                        }
                        if ((ele.val().indexOf('.') !== -1) && ([190, 110].indexOf(e.keyCode) !== -1)) {
                            //Do not allow entering more than one "." sign
                            e.preventDefault();
                        }
                        if ((arrayAllow.indexOf(e.keyCode) !== -1) ||
                            (e.keyCode === 65 && e.ctrlKey === true) || (e.keyCode === 67 && e.ctrlKey === true) ||
                            (e.keyCode === 88 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // Allow: delete, backspace, escape, enter , Ctrl+A+C+X , home, end, left, right, point
                            if (ele.val() == "") {
                                ele.val(0);
                            }
                            scope.ngVal = ele.val();
                            return;
                        }
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                        if ((e.which === 46) && (ele.val().indexOf('.') === -1)) {
                            setTimeout(function () {
                                if (ele.val().substring(ele.val().indexOf('.')).length > 3) {
                                    ele.val(ele.val().substring(0, ele.val().indexOf('.') + 3));
                                }
                            }, 1);
                        }
                        if ((ele.val().indexOf('.') !== -1) &&
                            (ele.val().substring(ele.val().indexOf('.')).length > scope.digitNumber) &&
                            (e.which !== 0 && e.which !== 8) &&
                            (e.target.selectionStart >= ele.val().length - parseInt(scope.digitNumber))) {
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
                        scope.ngVal = ele.val();
                    };

                    function replaceAt(str, index, replace) {
                        return str.substr(0, index) + replace + str.substr(index + replace.length);
                    }

                    function fromKeyCode(keyCode) {
                        var code = (96 <= keyCode && keyCode <= 105) ? keyCode - 48 : keyCode;
                        return String.fromCharCode(code);
                    }

                    function checkMaxValue(inputValue) {
                        if (!isFinite(inputValue)) {
                            return true;
                        }
                        scope.ngVal = ele.val();

                        var s = scope.ngVal.toString(), p = s.indexOf(".");
                        var l = s, r = "", v = "";
                        if (p !== -1) {
                            l = s.substr(0, p);
                            r = s.substr(p + 1);
                            if (p < ele[0].selectionStart) {
                                r = replaceAt(r, ele[0].selectionStart, inputValue);
                                v = parseFloat(l.concat(".", r));
                            } else {
                                v = parseFloat(l.concat(inputValue, ".", r));
                            }
                        } else {
                            v = parseFloat(l.concat(inputValue));
                        }
                        if (isFinite(scope.max) && v > parseInt(scope.max)) {
                            return true;
                        } else {
                            return false;
                        }
                    }

                    function addCommas(nStr) {
                        nStr += '';
                        x = nStr.split('.');
                        x1 = x[0];
                        x2 = x.length > 1 ? '.' + x[1] : '';
                        var rgx = /(\d+)(\d{3})/;
                        while (rgx.test(x1)) {
                            x1 = x1.replace(rgx, '$1' + ',' + '$2');
                        }
                        return x1 + x2;
                    }
                }
            };
        }
    };
});

app.directive("tplJqueryNumber", function () {
    return {
        restrict: "E",
        replace: true,
        scope: {
            max: "@",
            ngVal: "=",
            digitNumber: "@"
        },
        template: '<input type="text" ng-keyup="bindingNumber()" ng-keydown="validating($event)"/>',
        compile: function () {
            return {
                post: function (scope, ele, attr, formCtrl) {
                    ele.val(0);
                    scope.$watch("digitNumber", function (val) {
                        if (isFinite(val)) {
                            $(ele).number(true, scope.digitNumber);
                        }
                    });
                    scope.bindingNumber = function () {
                        scope.ngVal = ele.val().replace(/\,/g, "");
                    };
                    scope.validating = function (e) {
                        if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                            var iVal = fromKeyCode(e.keyCode);
                            var point = ele.val().toString().indexOf(".");
                            if (checkLimitation(iVal)) {
                                if ((-1 !== point && e.target.selectionStart <= point)) {
                                    e.preventDefault();
                                }
                            }
                        }
                    };
                    function replaceAt(str, index, replace) {
                        return str.substr(0, index) + replace + str.substr(index + replace.length);
                    }

                    function fromKeyCode(keyCode) {
                        var code = (96 <= keyCode && keyCode <= 105) ? keyCode - 48 : keyCode;
                        return String.fromCharCode(code);
                    }

                    function checkLimitation(inputValue) {
                        if (!isFinite(inputValue)) {
                            return true;
                        }
                        var s = ele.val(), p = s.indexOf(".");
                        if (p !== -1) {
                            s = s.substr(0, p);
                        }
                        s.concat(inputValue);
                        if (isFinite(scope.max) && s.length > parseInt(scope.max)) {
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