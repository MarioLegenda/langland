(function () {
    var app = angular.module('app', [
        'app.factories',
        'app.mainPageDirectives',
        'app.courseDirectives',
        'ngDialog',
        'ngDropdowns',
        'ngFileUpload',
        'ngAnimate',
        'ngRoute',
        'autocomplete'
    ]).config(function($interpolateProvider, $routeProvider, $locationProvider) {
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        $routeProvider
            .when('/admin', {
                templateUrl: 'mainPage.html',
                controller: 'MainPageController'
            })
            .when('/admin/course/page/:id', {
                templateUrl: 'coursePage.html',
                controller: 'CourseController'
            });

        $locationProvider.html5Mode(true);
    });

    app.controller('AppController', [
        '$scope',
        '$rootScope',
        '$route',
        '$location',
        function(
            $scope,
            $rootScope,
            $route,
            $location
        ) {

        $scope.page = {};

        console.log(JSON.stringify({
            name: 'Mile',
            lastname: 'Mirko',
            age: '18',
            personal_data: {
                birth_day: 18,
                month: 6,
                year: 1986,
                day: 'Monday'
            }
        }));

        $scope.page.goHome = function() {
            $location.url('/admin');
        };

        $rootScope.$on('action.load_course', function(event, data) {
            $location.url('/admin/course/page/' + data.id);
        });

    }]);

    app.controller('MainPageController', [
        '$scope',
        '$rootScope',
        'Server',
        'LanguageProvider',
        'RouteHelper',
        function(
            $scope,
            $rootScope,
            Server,
            LanguageProvider,
            RouteHelper
        ) {
            $scope.mainPage = {
                show: false,
                internalError: false,
                languageDetected: false,
                waiting: true
            };

            Server.communicate('language.find_all', {}).then(function(data) {
                var languages = data.data.data.languages,
                    parsedLanguages = [],
                    i,
                    appLanguages,
                    languageSelected,
                    workingLanguage;

                if (languages.length > 0) {
                    for (i = 0; i < languages.length; i++) {
                        var temp = {
                            text: languages[i].language,
                            value: languages[i].id
                        };

                        if (languages[i].working_language === '1') {
                            workingLanguage = temp;
                        }

                        parsedLanguages.push(temp);
                    }
                }

                appLanguages = parsedLanguages;

                if (appLanguages.length === 0) {
                    languageSelected = {
                        text: 'No language',
                        value: null
                    };

                    LanguageProvider.setLanguage(languageSelected);
                    LanguageProvider.setLanguages([languageSelected]);
                } else {
                    LanguageProvider.setLanguage(workingLanguage);
                    LanguageProvider.setLanguages(appLanguages);
                }

                $scope.mainPage.show = true;
                $scope.mainPage.waiting = false;
                $scope.mainPage.languageDetected = true;

            }, function(data) {
                RouteHelper.redirect(data);
            });

            $scope.$on('action.emit.language_change', function(event, data) {
                LanguageProvider.setLanguage(data);

                if (data.value !== null) {
                    $scope.mainPage.languageDetected = true;
                }

                $scope.$broadcast('action.search_only.language_change');
            });

            $rootScope.$on('action.emit.create_word', function() {
                $rootScope.$broadcast('action.broadcast.add_word');
            });
    }]);

    app.controller('CourseController', [
        '$scope',
        '$route',
        'Server',
        'CourseInfo',
        function(
            $scope,
            $route,
            Server,
            CourseInfo
        ) {

        CourseInfo.reset();

        $scope.coursePage = {
            show: false,
            internalError: false,
            waiting: true,
            notFound: false
        };

        $scope.course = {
            id: null,
            name: null,
            language_id: null
        };

        Server.communicate('course.initial_info', {
            id: $route.current.params.id
        }).then(function(data) {
            var d = data.data;

            if (d.status === 'success') {
                $scope.course.name = d.data.name;
                $scope.course.language_id = d.data.language_id;
                $scope.course.id = d.data.id;

                CourseInfo.init($scope.course);

                $scope.coursePage.waiting = false;
                $scope.coursePage.show = true;

                return false;
            }

            if (d.status === 'failure') {
                $scope.coursePage.waiting = false;
                $scope.coursePage.notFound = true;

                return false;
            }
        })
    }]);
}());