var mpCommonAngular = angular.module('com.magpleasure.common', ["ngDraggable","mp.blog.layout"], function ($httpProvider) {
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.transformRequest = [function (data) {
        return Object.toQueryString(data);
    }];
});

mpCommonAngular
    .config(function($interpolateProvider){
            $interpolateProvider.startSymbol('{{{').endSymbol('}}}');
        }
    );
angular.element(document).ready(function () {
    var doc = document.body;
    if (doc && !jQuery(doc).hasClassName('ng-scope')) {
        angular.bootstrap(doc, ['com.magpleasure.common']);
    }
});