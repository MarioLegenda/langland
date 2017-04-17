(function () {
    var app = angular.module('admin', [
        'admin.factories',
        'ngDialog'
    ]).config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    });

    app.directive('workingLanguage', function() {
        return {
            restrict: 'E',
            replace: true,
            templateUrl: 'languageDropdown.html',
            scope: {},
            controller: [
                '$scope',
                'Server',
                'ngDialog',
                function(
                    $scope,
                    Server,
                    ngDialog
                ) {

                    var languages = [];
                    $scope.workingLanguage = null;

                    Server.communicate('language.find_all', {}).then(function(data) {
                        languages = data.data.data.languages;

                        $scope.workingLanguage = languages[0].language;
                    });

                    $scope.chooseLanguage = function($event) {
                        $event.preventDefault();

                        ngDialog.open({
                            template: 'languageList.html',
                            className: 'ngdialog-theme-default',
                            width: 400,
                            controller: [
                                '$scope',
                                'Server',
                                function(
                                    $scope,
                                    Server
                                ) {
                                    $scope.languages = languages;
                                }
                            ]
                        });

                        return false;
                    }
                }]
        }
    })
}());