angular.module('app.mainPageDirectives', ['app.factories'])
    .directive('wordMenu', function() {
        return {
            restrict: 'E',
            replace: true,
            scope: {},
            template: '<a href="#" ng-click="openModal()" class="nav-link">Add word<i class="fa fa-envelope"></i></a>',
            controller: [
                '$scope',
                'ngDialog',
                'Upload',
                'Path',
                'Server', function(
                    $scope,
                    ngDialog,
                    Upload,
                    Path,
                    Server
                ) {

                    $scope.openModal = function() {
                        ngDialog.open({
                            template: 'wordModal.html',
                            className: 'ngdialog-theme-default',
                            width: 400,
                            controller: [
                                '$scope',
                                '$rootScope',
                                'Server',
                                'LanguageProvider',
                                'RouteHelper', function(
                                    $scope,
                                    $rootScope,
                                    Server,
                                    LanguageProvider,
                                    RouteHelper
                                ) {

                                    $scope.numberOfTranslations = [0];
                                    $scope.word = null;
                                    $scope.type = null;
                                    $scope.loading = false;
                                    $scope.translations = null;
                                    $scope.serverErrors = [];
                                    $scope.success = false;
                                    $scope.category = null;
                                    $scope.disableButton = false;
                                    $scope.imageFile = null;

                                    $scope.error = {
                                        translation : {
                                            status: false,
                                            message: 'There has to be at least one translation of a word'
                                        },
                                        invalid: false
                                    };

                                    if (LanguageProvider.getLanguage().value === null) {
                                        $scope.serverErrors.push('You haven\'t specified any working language. Go to \'Choose language\' dropdown menu and choose a working language');
                                    }

                                    var currentNum = 0;
                                    $scope.addTranslationField = function() {
                                        $scope.numberOfTranslations.push(++currentNum);
                                    };

                                    $scope.removeTranslation = function($event, item) {
                                        $($event.currentTarget).parent().remove();
                                        var index = $scope.numberOfTranslations.indexOf(item);

                                        $scope.numberOfTranslations.splice(index, 1);
                                    };

                                    $scope.addWord = function(imageFile) {
                                        if ($scope.serverErrors.length > 0) {
                                            return false;
                                        }

                                        if ($scope.word === null) {
                                            $scope.error.invalid = true;

                                            return false;
                                        } else if(LanguageProvider.getLanguage().value === null) {
                                            $scope.serverErrors.push('You haven\'t specified any working language. Go to \'Choose language\' dropdown menu and choose a working language');

                                            return false;
                                        } else {
                                            $scope.error.invalid = false;
                                        }

                                        var translationsElem = $('.modal-container .translation');

                                        var translations = [];
                                        for (var i = 0; i < translationsElem.length; i++) {
                                            var value = $(translationsElem[i]).val();

                                            if (value !== '') {
                                                translations.push(value);
                                            }
                                        }

                                        if (translations.length === 0) {
                                            $scope.error.translation.status = true;
                                            $scope.error.translation.message = 'There has to be at least one translation of a word';

                                            return false;
                                        } else {
                                            $scope.error.translation.status = false;
                                            $scope.error.translation.message = '';
                                        }

                                        $scope.translations = translations;
                                        $scope.success = false;
                                        $scope.loading = true;

                                        var wordData = {
                                            word: $scope.word,
                                            type: $scope.type,
                                            language_id: LanguageProvider.getLanguage().value,
                                            translations: $scope.translations,
                                            image: imageFile
                                        };

                                        if ($scope.category === null) {
                                            wordData.category = null;
                                        } else if ($scope.category.value === null) {
                                            wordData.category = null;
                                        } else if ($scope.category.value !== null) {
                                            wordData.category = $scope.category.value;
                                        }

                                        $scope.disableButton = true;

                                        Upload.upload({
                                            url: Path.namespace('word.create').construct(),
                                            data: wordData,
                                            enctype: 'multipart/form-data',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        }).then(function(data) {
                                            var d = data.data;

                                            if (d.status === 'success') {
                                                $scope.success = true;
                                                $scope.loading = false;
                                                $scope.word = null;
                                                $scope.type = null;

                                                var translationsElem = $('.modal-container .translation');
                                                for (var i = 0; i < translationsElem.length; i++) {
                                                    $(translationsElem[i]).val('');
                                                }

                                                $scope.numberOfTranslations = [0];

                                                $rootScope.$emit('action.emit.create_word', {});

                                                if (typeof imageFile !== 'undefined' && imageFile !== null) {
                                                    ngDialog.close('dialog1');
                                                }
                                            }

                                            if (d.status === 'failure') {
                                                $scope.loading = false;
                                                $scope.serverErrors = d.errors;

                                                return false;
                                            }

                                            $scope.buttonDisabled = false;
                                        }, function(data) {
                                            RouteHelper.redirect(data);
                                        });
                                    }
                                }]
                        });
                    }
                }]
        }
    })
    .directive('categoryMenu', function() {
        return {
            restrict: 'E',
            replace: true,
            scope: {},
            template: '<a href="#" ng-click="openModal()" class="nav-link">Add category<i class="fa fa-align-justify"></i></a>',
            controller: [
                '$scope',
                'ngDialog', function(
                    $scope,
                    ngDialog
                ) {

                    $scope.openModal = function() {
                        ngDialog.open({
                            template: 'categoryModal.html',
                            className: 'ngdialog-theme-default',
                            width: 400,
                            controller: [
                                '$scope',
                                'Server',
                                'LanguageProvider',
                                'RouteHelper', function(
                                    $scope,
                                    Server,
                                    LanguageProvider,
                                    RouteHelper
                                ) {
                                    $scope.category = null;
                                    $scope.loading = false;
                                    $scope.success = false;
                                    $scope.serverErrors = [];
                                    $scope.disableButton = false;

                                    $scope.error = {
                                        exists: {
                                            status: false
                                        },
                                        invalid : false
                                    };

                                    if(LanguageProvider.getLanguage().value === null) {
                                        $scope.serverErrors.push('You haven\'t specified any working language. Go to \'Choose language\' dropdown menu and choose a working language');

                                        return false;
                                    }

                                    $scope.createCategory = function($event) {
                                        $event.preventDefault();

                                        if ($scope.category === null) {
                                            $scope.error.invalid = true;

                                            return false;
                                        }

                                        $scope.success = false;
                                        $scope.loading = true;
                                        $scope.error.exists.status = false;

                                        $scope.disableButton = true;
                                        Server.communicate('category.create', {
                                            category: $scope.category,
                                            language_id: LanguageProvider.getLanguage().value
                                        }).then(function(data) {
                                            $scope.loading = false;

                                            if (data.data.status === 'failure') {
                                                $scope.serverErrors = data.data.errors;
                                            }

                                            if (data.data.status === 'success') {
                                                $scope.category = null;
                                                $scope.success = true;

                                                $scope.serverErrors = [];
                                            }

                                            $scope.error.invalid = false;
                                            $scope.disableButton = false;
                                        }, function(data) {
                                            RouteHelper.redirect(data);
                                        });

                                        return false;
                                    }
                                }]
                        });
                    }
                }]
        }
    })
    .directive('languageMenu', function() {
        return {
            restrict: 'E',
            replace: true,
            template: '<a href="#" ng-click="openModal()" class="nav-link">Add language<i class="fa fa-language"></i></a>',
            controller: [
                '$scope',
                'ngDialog', function(
                    $scope,
                    ngDialog
                ) {
                    $scope.openModal = function() {
                        ngDialog.open({
                            templateUrl: '/web/app_dev.php/admin/language/create?v=sdkfjasgjsl',
                            className: 'ngdialog-theme-default',
                            width: 400,
                            controller: [
                                '$scope',
                                '$rootScope',
                                'Server',
                                'RouteHelper', function(
                                    $scope,
                                    $rootScope,
                                    Server,
                                    RouteHelper
                                ) {
                                    $scope.language = null;
                                    $scope._token = null;
                                    $scope.loading = false;
                                    $scope.success = false;
                                    $scope.serverErrors = [];
                                    $scope.disableButton = false;

                                    $scope.error = {
                                        invalid : false
                                    };

                                    $scope.addLanguage = function() {
                                        if ($scope.language === null) {
                                            $scope.error.invalid = true;

                                            return false;
                                        }

                                        $scope.success = false;
                                        $scope.loading = true;

                                        $scope.disableButton = true;
                                        Server.communicate('language.create', {
                                            'language[name]': $scope.language,
                                            'language[_token]': $('#language__token').val()
                                        }).then(function(data) {
                                            $scope.loading = false;
                                            var d = data.data;

                                            if (d.status === 'failure') {
                                                $scope.serverErrors = d.errors;
                                            }

                                            if (d.status === 'success') {
                                                $scope.language = null;
                                                $scope.success = true;

                                                var createdLanguage = {
                                                    text: d.data.language.language,
                                                    value: d.data.language.id
                                                };

                                                $rootScope.$broadcast('action.broadcast.language_create', createdLanguage);
                                            }

                                            $scope.error.invalid = false;
                                            $scope.disableButton = false;
                                        }, function(data) {
                                            RouteHelper.redirect(data);
                                        });
                                    }
                                }]
                        });
                    }
                }]
        }
    })
    .directive('courseMenu', function() {
        return {
            restrict: 'E',
            replace: true,
            template: '<a href="#" ng-click="openModal()" class="nav-link">Courses<i class="fa fa-mortar-board"></i></a>',
            scope: {},
            controller: [
                '$scope',
                'ngDialog', function(
                    $scope,
                    ngDialog
                ) {
                    $scope.openModal = function() {
                        ngDialog.open({
                            template: 'courseModal.html',
                            className: 'ngdialog-theme-default',
                            width: 400,
                            controller: [
                                '$scope',
                                '$rootScope',
                                '$location',
                                'Server',
                                'LanguageProvider',
                                'RouteHelper', function(
                                    $scope,
                                    $rootScope,
                                    $location,
                                    Server,
                                    LanguageProvider,
                                    RouteHelper
                                )
                                {

                                    $scope.course = {
                                        'new' : false,
                                        load: false,
                                        courseName: '',
                                        success: false,
                                        loading: false,
                                        serverErrors: [],
                                        courses: [],
                                        noCourses: false
                                    };

                                    $scope.course.show = function(menu) {
                                        var valids = ['new', 'load'], i;

                                        for (i = 0; i < valids.length; i++) {
                                            if ($scope.course.hasOwnProperty(valids[i])) {
                                                if (valids[i] === menu) {
                                                    $scope.course[valids[i]] = true;
                                                } else {
                                                    $scope.course[valids[i]] = false;
                                                }
                                            }
                                        }
                                    };

                                    $scope.course.newCourse = function($event) {
                                        $scope.course.load = false;
                                        $scope.course.new = true;
                                        $scope.course.loading = true;
                                        $scope.course.success = false;

                                        Server.communicate('course.create', {
                                            name: $scope.course.courseName,
                                            language_id: LanguageProvider.getLanguage().value
                                        }).then(function(data) {
                                            var d = data.data;

                                            if (d.status === 'success') {
                                                $scope.course.loading = false;
                                                $scope.course.success = true;
                                                $scope.course.courseName = '';

                                                ngDialog.close('dialog1');
                                                $location.url('/admin/course/' + d.data.id);
                                            }

                                            if (d.status === 'failure') {
                                                $scope.course.loading = false;
                                                $scope.course.serverErrors = d.errors;
                                            }

                                        }, function(data) {
                                            RouteHelper.redirect(data);
                                        })
                                    };

                                    $scope.course.loadCourses = function() {
                                        $scope.course.new = false;
                                        $scope.course.load = true;
                                        $scope.course.noCourses = false;

                                        Server.communicate('course.get_all', {
                                            language_id: LanguageProvider.getLanguage().value
                                        }).then(function(data) {
                                            var d = data.data;

                                            if (d.status === 'success') {
                                                if (d.data.courses.length === 0) {
                                                    $scope.course.noCourses = true;

                                                    return false;
                                                }

                                                $scope.course.courses = d.data.courses;
                                            }
                                        }, function(data) {
                                            RouteHelper.redirect(data);
                                        })
                                    };

                                    $scope.course.loadCourse = function(id) {
                                        $rootScope.$emit('action.load_course', {
                                            id: id
                                        });

                                        ngDialog.close('dialog1');
                                    }
                                }]
                        })
                    }
                }]
        }
    })
    .directive('languageDropdown', function() {
        return {
            restrict: 'E',
            replace: true,
            templateUrl: 'languageDropdown.html',
            scope: {},
            controller: [
                '$scope',
                '$rootScope',
                'Server',
                'LanguageProvider', function(
                    $scope,
                    $rootScope,
                    Server,
                    LanguageProvider
                ) {
                    $scope.languageOptions = [];
                    $scope.languageSelected = {};

                    $rootScope.$on('action.broadcast.language_create', function(event, data) {
                        if ($scope.languageOptions.length === 1 && $scope.languageOptions[0].value === null) {
                            $scope.languageOptions = [data];

                            $scope.languageSelected = data;

                            return null;
                        }

                        $scope.languageOptions.push(data);
                    });

                    $scope.$watch('languageSelected', function(newVal, oldVal) {
                        LanguageProvider.setLanguage(newVal);

                        if (newVal.value !== null) {
                            Server.communicate('language.update_working_language', {
                                language_id: LanguageProvider.getLanguage().value,
                                working_language: 1
                            });
                        }

                        $scope.$emit('action.emit.language_change', newVal);
                    }, true);

                    if (LanguageProvider.isDefault() === true) {
                        $scope.languageSelected = LanguageProvider.getAllLanguages()[0];
                        $scope.languageOptions = LanguageProvider.getAllLanguages();
                    } else {
                        $scope.languageOptions = LanguageProvider.getAllLanguages();
                        $scope.languageSelected = LanguageProvider.getLanguage();
                    }
                }]
        }
    })
    .directive('categoryDropdown', function() {
        return {
            restrict: 'E',
            replace: true,
            templateUrl: 'categoryDropdown.html',
            controller: [
                '$scope',
                'Server',
                'LanguageProvider', function(
                    $scope,
                    Server,
                    LanguageProvider
                ) {

                    $scope.categoryOptions = [];
                    $scope.categorySelected = {};

                    Server.communicate('category.find_all', { language_id: LanguageProvider.getLanguage().value })
                        .then(function(data) {
                            var categories = data.data.data.categories,
                                parsedCategories = [],
                                i;

                            if (categories.length > 0) {
                                for (i = 0; i < categories.length; i++) {
                                    var temp = {
                                        text: categories[i].category,
                                        value: categories[i].id
                                    };

                                    parsedCategories.push(temp);
                                }
                            }

                            $scope.categorySelected = {
                                text: 'Nothing selected',
                                value: null
                            };

                            $scope.categoryOptions = parsedCategories;
                        });

                    $scope.$watch('categorySelected', function(newVal, oldVal) {
                        $scope.category = $scope.categorySelected;
                    }, true);
                }]
        };
    })
    .directive('mainSearch', function() {
        return {
            restrict: 'E',
            replace: true,
            templateUrl: 'search.html',
            scope: {},
            controller: [
                '$scope',
                '$rootScope',
                '$window',
                'Server',
                'LanguageProvider',
                'PaginationBuilder',
                'RouteHelper', function(
                    $scope,
                    $rootScope,
                    $window,
                    Server,
                    LanguageProvider,
                    PaginationBuilder,
                    RouteHelper
                ) {
                    $scope.search = {};

                    $scope.search.foundWords = [];
                    $scope.search.input = null;
                    $scope.search.noWords = false;
                    $scope.search.noWordsSearch = false;
                    $scope.search.noLanguages = false;
                    $scope.search.currentLanguage = LanguageProvider.getLanguage().text;
                    $scope.search.waiting = false;
                    $scope.search.loadMoreButton = false;
                    $scope.search.nextLoading = false;
                    $scope.search.previousLoading = false;

                    var foundLastWords = false,
                        pagination = PaginationBuilder.create('search');

                    pagination.step(12);

                    var findLastWords = function(data) {
                        Server.communicate('search.find_last_words', data).then(function(data) {
                            var d = data.data;

                            if (d.status === 'success') {
                                var wordData = d.data.words;

                                $scope.search.foundWords = wordData.word_blocks;

                                $scope.search.noWords = false;

                                if (wordData.word_count === 12) {
                                    $scope.search.loadMoreButton = true;
                                }

                                $scope.search.waiting = false;
                                $scope.search.nextLoading = false;
                                $scope.search.previousLoading = false;
                            }

                            if (d.status === 'failure') {
                                if (LanguageProvider.getLanguage().value === null) {
                                    $scope.search.noLanguages = true;

                                    return null;
                                }

                                $scope.search.noWords = true;
                                $scope.search.foundWords = [];
                                $scope.search.currentLanguage = LanguageProvider.getLanguage().text;
                                $scope.search.nextLoading = false;
                                $scope.search.previousLoading = false;

                                $scope.search.waiting = false;
                            }
                        }, function(data) {
                            RouteHelper.redirect(data);
                        });
                    };

                    if (LanguageProvider.getLanguage().value !== null) {
                        $scope.search.waiting = true;

                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    }

                    $scope.search.next = function() {
                        $scope.search.nextLoading = true;
                        pagination.next();

                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    };

                    $scope.search.previous = function() {
                        $scope.search.previousLoading = true;
                        pagination.previous();

                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    };

                    $scope.search.removeWord = function($event, id) {
                        $($event.currentTarget).parent().parent().css({display: 'none'});

                        Server.communicate('word.schedule_removal', {
                            word_id: id
                        }).then(function() {
                            findLastWords({
                                language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                                offset: pagination.current()
                            });

                            Server.communicate('word.remove', {
                                word_id: id
                            });
                        }).then(function(data) {
                            RouteHelper.redirect(data);
                        });
                    };

                    $scope.search.loadMore = function() {
                        pagination.next();

                        $scope.search.loadMoreButton = false;

                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    };

                    $scope.search.search = function() {
                        if ($scope.search.input !== '' && $scope.search.input !== null) {
                            pagination.reset();

                            Server.communicate('search.search', {
                                language_id: LanguageProvider.getLanguage().value,
                                word: $scope.search.input,
                                offset: pagination.current()
                            }).then(function(data) {
                                var d = data.data;

                                if (d.status === 'success') {
                                    $scope.search.foundWords = d.data.words;
                                    $scope.search.noWords = false;
                                    $scope.search.noWordsSearch = false;
                                }

                                if (d.status === 'failure') {
                                    $scope.search.foundWords = [];
                                    $scope.search.noWordsSearch = true;
                                }
                            }, function(data) {
                                RouteHelper.redirect(data);
                            });

                            foundLastWords = false;
                        } else if ($scope.search.input === '' || $scope.search.input === null) {
                            if (foundLastWords === false) {
                                findLastWords({
                                    language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                                    offset: pagination.current()
                                });

                                foundLastWords = true;
                            }
                        }
                    };

                    $rootScope.$on('action.broadcast.add_word', function() {
                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    });

                    $scope.$on('action.search_only.language_change', function() {
                        findLastWords({
                            language_id: (LanguageProvider.getLanguage().value === null) ? '' : LanguageProvider.getLanguage().value,
                            offset: pagination.current()
                        });
                    });
                }]
        }
    });