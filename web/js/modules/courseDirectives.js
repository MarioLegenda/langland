angular.module('app.courseDirectives', ['app.factories'])
.directive('courseDashboard', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'courseDashboard.html',
        scope: {
            course: '=course'
        },
        controller: [
            '$scope',
            '$rootScope',
            'Server',
            'RouteHelper',
            function(
                $scope,
                $rootScope,
                Server,
                RouteHelper
            ) {

            $scope.course.classes = false;

            $scope.course.waiting = true;

            Server.communicate('class.get_all_by_course', {
                course_id: $scope.course.id
            }).then(function(data) {
                var d = data.data;

                if (d.status === 'failure') {
                    $scope.course.waiting = false;

                    return false;
                }

                if (d.status === 'success') {
                    $scope.course.classes = d.data.classes;
                    $scope.course.waiting = false;

                    return false;
                }
            }, function(data) {
                RouteHelper.redirect(data);
            });

            $rootScope.$on('action.add_class', function($event, data) {
                if (angular.isArray($scope.course.classes)) {
                    $scope.course.classes.push(data.class);
                } else {
                    $scope.course.classes = [];
                    $scope.course.classes.push(data.class);
                }
            });
        }]
    }
})
.directive('individualLesson', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'lessonMenu.html',
        controller: [
            '$scope',
            'Server',
            'RouteHelper',
            function(
                $scope,
                Server,
                RouteHelper
            ) {
                $scope.lesson = {
                    lesson_id: $scope.individualLesson.id,
                    class_id: $scope.individualLesson.class_id,
                    name: $scope.individualLesson.name,
                    newName: $scope.individualLesson.name
                };

                $scope.page = {
                    open: false,
                    loading: false,
                    success: false,
                    serverErrors: []
                };

                $scope.lesson.open = function() {
                    $scope.page.open = $scope.page.open === false;
                    $scope.page.serverErrors = [];
                    $scope.lesson.newName = $scope.lesson.name;
                };

                $scope.lesson.rename = function() {
                    $scope.page.loading = true;
                    $scope.page.success = false;
                    $scope.page.serverErrors = [];

                    Server.communicate('lesson.rename', {
                        class_id: $scope.lesson.class_id,
                        name: $scope.lesson.newName,
                        lesson_id: $scope.lesson.lesson_id
                    }).then(function(data) {
                        var d = data.data;

                        $scope.page.loading = false;

                        if (d.status === 'success') {
                            $scope.page.success = true;
                            $scope.lesson.name = $scope.lesson.newName;
                        }

                        if (d.status === 'failure') {
                            $scope.page.success = false;
                            $scope.page.serverErrors = d.errors;
                        }
                    }, function(data) {
                        RouteHelper.redirect(data);
                    });
                }
            }
        ]
    }
})
.directive('individualClass', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'individualClass.html',
        controller: [
            '$scope',
            'Server',
            'RouteHelper',
            function(
                $scope,
                Server,
                RouteHelper
            ) {

            $scope.thisClass = {
                mainMenu: false,
                serverErrors: [],
                loading: false,
                success: false,
                'class': {
                    class_id: $scope.individualClass.id,
                    course_id: $scope.individualClass.course_id,
                    name: $scope.individualClass.name,
                    loading: false,
                    success: false,
                    serverErrors: [],
                    newName: $scope.individualClass.name
                },
                lesson: {
                    name: '',
                    list: [],
                    waiting: true
                }
            };

            $scope.thisClass.open = function() {
                $scope.thisClass.mainMenu = $scope.thisClass.mainMenu === false;
                $scope.thisClass.class.serverErrors = [];
                $scope.thisClass.newName = $scope.thisClass.class.name;

                if ($scope.thisClass.mainMenu === true) {
                    $scope.thisClass.serverErrors = [];

                    Server.communicate('lesson.find_by_class', {
                        class_id: $scope.thisClass.class.class_id
                    }).then(function(data) {
                        var d = data.data.data;

                        $scope.thisClass.lesson.list = d.lessons;

                        $scope.thisClass.lesson.waiting = false;
                    }, function(data) {
                        RouteHelper.redirect(data);
                    });
                }
            };

            $scope.thisClass.class.renameClass = function() {
                $scope.thisClass.class.loading = true;
                $scope.thisClass.class.success = false;
                $scope.thisClass.class.serverErrors = [];

                Server.communicate('class.update', {
                    name: $scope.thisClass.class.newName,
                    class_id: $scope.thisClass.class.class_id,
                    course_id: $scope.thisClass.class.course_id
                }).then(function(data) {
                    var d = data.data;

                    if (d.status === 'success') {
                        $scope.thisClass.class.name = $scope.thisClass.class.newName;

                        $scope.thisClass.class.loading = false;
                        $scope.thisClass.class.success = true;
                    }

                    if (d.status === 'failure') {
                        $scope.thisClass.class.loading = false;
                        $scope.thisClass.class.success = false;

                        $scope.thisClass.class.serverErrors = d.errors;
                    }
                }, function(data) {
                    RouteHelper.redirect(data);
                });
            };

            $scope.thisClass.createLesson = function() {
                $scope.thisClass.loading = true;
                $scope.thisClass.success = false;

                Server.communicate('lesson.create', {
                    class_id: $scope.thisClass.class.class_id,
                    name: $scope.thisClass.lesson.name
                }).then(function(data) {
                    var d = data.data;

                    if (d.status === 'success') {
                        $scope.thisClass.lesson.list.push(d.data.created_lesson);

                        $scope.thisClass.lesson.name = '';

                        $scope.thisClass.loading = false;
                        $scope.thisClass.success = true;

                        $scope.thisClass.serverErrors = [];
                    }

                    if (d.status === 'failure') {
                        $scope.thisClass.loading = false;
                        $scope.thisClass.success = false;

                        $scope.thisClass.serverErrors = d.errors;
                    }
                }, function(data) {
                    RouteHelper.redirect(data);
                });
            }
        }]
    }
})
.directive('theory', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'theoryMenu.html',
        scope: {
            lesson: '=lesson'
        },
        controller: [
            '$scope',
            'Server',
            'RouteHelper', function(
                $scope,
                Server,
                RouteHelper
            ) {
                $scope.theory = {
                    name: '',
                    decks: []
                };

                $scope.page = {
                    loading: false,
                    success: false,
                    serverErrors: [],
                    open: false
                };

                $scope.theory.open = function() {
                    $scope.page.open = $scope.page.open === false;
                    $scope.page.serverErrors = [];

                    if ($scope.page.open === true) {
                        Server.communicate('theory.find_all_by_lesson', {
                            lesson_id: $scope.lesson.lesson_id
                        }).then(function(data) {
                            var d = data.data;

                            if (d.status === 'success') {
                                $scope.theory.decks = d.data.theories;
                            }

                            if (d.status === 'failure') {
                                $scope.theory.decks = [];
                            }
                        }, function(data) {
                            RouteHelper.redirect(data);
                        });
                    }
                };

                $scope.theory.create = function() {
                    $scope.page.loading = true;
                    $scope.page.success = false;

                    Server.communicate('theory.create', {
                        name: $scope.theory.name,
                        lesson_id: $scope.lesson.lesson_id
                    }).then(function(data) {
                        var d = data.data;

                        if (d.status === 'success') {
                            $scope.page.loading = false;
                            $scope.page.success = true;
                            $scope.page.serverErrors = [];
                            $scope.theory.name = '';

                            $scope.theory.decks.push(d.data.created_theories);
                        }

                        if (d.status === 'failure') {
                            $scope.page.loading = false;
                            $scope.page.success = false;
                            $scope.page.serverErrors = d.errors;
                        }
                    }, function(data) {
                        RouteHelper.redirect(data);
                    })
                }
            }
        ]
    }
})
.directive('theoryDeck', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'theoryDeck.html',
        controller: [
            '$scope',
            'Server',
            'RouteHelper',
            'Upload',
            'Path',
            function(
                $scope,
                Server,
                RouteHelper,
                Upload,
                Path
            ) {

                var populateDecks = function() {
                    Server.communicate('theory.find_decks_by_theory', {
                        theory_id: $scope.deck.theory_id
                    }).then(function(data) {
                        var decks = data.data.data.selection_decks,
                            parsedSelections = [],
                            i;

                        if (decks.length > 0) {
                            for (i = 0; i < decks.length; i++) {
                                var temp = {
                                    text: decks[i].internal_name,
                                    value: decks[i].id
                                };

                                parsedSelections.push(temp);
                            }
                        }

                        $scope.deck.deckSelected = {
                            text: 'Nothing selected',
                            value: null
                        };

                        $scope.deck.deckOptions = parsedSelections;
                    }, function(data) {
                        RouteHelper.redirect(data);
                    });
                };

                $scope.theory_deck = {
                    name: $scope.deck.name,
                    id: $scope.deck.id,
                    lesson_id: $scope.deck.lesson_id,
                    newName: $scope.deck.name
                };

                $scope.deck = {
                    deck_id: null,
                    theory_id: $scope.theory_deck.id,
                    deck_data: '',
                    show_on_page: false,
                    ordering: null,
                    internal_name: '',
                    internal_description: '',
                    editor: null,
                    soundFile: null,
                    deckOptions: [],
                    deckSelected: {},
                    deckSounds: null
                };

                $scope.deck.initDeck = function() {
                    var editor = ace.edit('deck-editor');
                    var session = editor.getSession();

                    editor.getSession().setMode("ace/mode/html");
                    editor.renderer.setShowGutter(true);
                    editor.setFontSize(18);
                    editor.setHighlightActiveLine(true);
                    editor.setWrapBehavioursEnabled(true);
                    editor.setOption('firstLineNumber', 1);

                    $scope.deck.editor = session;

                    populateDecks();
                };

                $scope.page = {
                    open: false,
                    loading: false,
                    success: false,
                    serverErrors: [],
                    deck_loading: false,
                    deck_success: false,
                    deck_errors: [],
                    openSoundsList: false,
                    noSounds: false
                };

                $scope.page.showSounds = function() {
                    $scope.page.openSoundsList = $scope.page.openSoundsList === false;

                    if ($scope.deck.deck_id === null) {
                        $scope.page.noSounds = true;
                    } else {
                        $scope.deck.noSounds = false;

                        if ($scope.page.openSoundsList === true) {
                            Server.communicate('theory.find_deck_sounds', {
                                'deck_id': $scope.deck.deck_id
                            }).then(function(data) {
                                $scope.deck.deckSounds = data.data.data.sounds;
                            });                            
                        }
                    }
                };

                $scope.theory_deck.open = function() {
                    $scope.page.open = $scope.page.open === false;
                    $scope.page.serverErrors = [];
                    $scope.page.deck_errors = [];
                };

                $scope.theory_deck.save = function(soundFile) {
                    $scope.page.deck_loading = true;
                    $scope.page.deck_success = false;
                    $scope.page.deck_errors = [];
                    $scope.page.serverErrors = [];

                    Server.communicate('theory.find_deck_by_internal_name', {
                        theory_id: $scope.deck.theory_id,
                        internal_name: $scope.deck.internal_name
                    }).then(function(data) {
                        var d = data.data;

                        if (d.status === 'success') {
                            $scope.page.deck_errors.push('Internal name already exists');
                            $scope.page.deck_loading = false;
                            $scope.page.deck_success = false;
                        } else if (d.status === 'failure') {
                            Upload.upload({
                                url: Path.namespace('theory.create_deck').construct(),
                                data: {
                                    deck_id: $scope.deck.deck_id,
                                    theory_id: $scope.deck.theory_id,
                                    internal_name: $scope.deck.internal_name,
                                    deck_data: $scope.deck.editor.getValue(),
                                    show_on_page: $scope.deck.show_on_page,
                                    ordering: $scope.deck.ordering,
                                    internal_description: $scope.deck.internal_description,
                                    soundFile: soundFile
                                },
                                enctype: 'multipart/form-data',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }).then(function(data) {
                                var d = data.data;

                                if (d.status === 'success') {
                                    $scope.page.deck_loading = false;
                                    $scope.page.deck_success = true;
                                    $scope.page.deck_errors = [];

                                    $scope.deck.deck_id = d.data.deck_id;

                                    populateDecks();
                                }

                                if (d.status === 'failure') {
                                    $scope.page.deck_loading = false;
                                    $scope.page.deck_success = false;
                                    $scope.page.deck_errors = d.errors;
                                }
                            });
                        }
                    });
                };

                $scope.theory_deck.rename = function() {
                    $scope.page.loading = true;
                    $scope.page.success = false;

                    Server.communicate('theory.rename', {
                        lesson_id: $scope.theory_deck.lesson_id,
                        name: $scope.theory_deck.newName,
                        theory_id: $scope.theory_deck.id
                    }).then(function(data) {
                        var d = data.data;

                        if (d.status === 'success') {
                            $scope.page.loading = false;
                            $scope.page.success = true;

                            $scope.theory_deck.name = $scope.theory_deck.newName;
                            $scope.deck.serverErrors = [];
                        }

                        if (d.status === 'failure') {
                            $scope.page.loading = false;
                            $scope.page.success = false;
                            $scope.page.serverErrors = d.errors;
                        }
                    }, function(data) {
                        RouteHelper.redirect(data);
                    });
                };

                $scope.theory_deck.reset = function() {
                    $scope.deck.deck_id = null;
                    $scope.deck.deck_data = '';
                    $scope.deck.show_on_page = false;
                    $scope.deck.ordering = null;
                    $scope.deck.internal_name = '';
                    $scope.deck.internal_description = '';

                    $scope.deck.editor.setValue('');

                    $scope.deck.deckSelected = {
                        text: 'Nothing selected',
                        value: null
                    };
                };

                $scope.$watch('deck.deckSelected', function(newVal, oldVal) {
                    if (oldVal.value !== newVal.value && newVal.value !== null) {
                        var id = newVal.value;

                        Server.communicate('theory.find_deck', {
                            deck_id: id
                        }).then(function(data) {
                            var d = data.data;

                            if (d.status === 'success') {
                                var deck = d.data.deck;

                                $scope.deck.deck_id = deck.id;
                                $scope.deck.deck_data = deck.deck_data;
                                $scope.deck.show_on_page = deck.show_on_page;
                                $scope.deck.ordering = deck.ordering;
                                $scope.deck.internal_name = deck.internal_name;
                                $scope.deck.internal_description = deck.internal_description;

                                $scope.deck.editor.setValue((deck.deck_data === null) ? '' : deck.deck_data );
                            }

                            if (d.status === 'failure') {
                                $scope.page.deck_errors = d.errors;
                            }
                        }, function(data) {
                            RouteHelper.redirect(data);
                        });
                    }
                }, true);
            }
        ]
    }
})
.directive('sentenceLearning', function() {
    return {
        restrict: 'E',
        replace: true,
        templateUrl: 'sentenceLearning.html',
        scope: {
            lesson: '=lesson'
        },
        controller: [
            '$scope',
            'Server',
            'RouteHelper',
            'GenericDomHelper',
            function($scope, Server, RouteHelper, GenericDomHelper) {
                function findInternalNames() {
                    Server.communicate('sentence.find_internal_names', {
                        lesson_id: $scope.lesson.lesson_id
                    }).then(function(data) {
                        $scope.page.internalNames = data.data.data.internal_names;
                    });
                }

                $scope.page = {
                    visible: false,
                    serverErrors: [],
                    loading: false,
                    success: false,
                    newSentence: true,
                    oldSentence: false,
                    internalNames: [],
                    realTranslations: [],
                    open: function() {
                        $scope.page.visible = $scope.page.visible === false;

                        if ($scope.page.visible === true) {
                            findInternalNames();
                        }
                    },
                    addTranslationField: function() {
                        $scope.sentence.translations.push({
                            id: null,
                            translation: ''
                        });
                    },
                    removeDomTranslation: function(id) {
                        var len = $scope.sentence.translations.length,
                            i;

                        for (i = 0; i < len; i++) {
                            $scope.sentence.translations.splice(i, 1);
                        }
                    },
                    removeDbTranslation: function(id) {

                    },
                    updateTranslation: function(id) {

                    },
                    fetchSentence: function(internalName) {
                        Server.communicate('sentence.find_lesson_sentence', {
                            internal_name: internalName,
                            lesson_id: $scope.lesson.lesson_id
                        }).then(function(data) {
                            var sentence = data.data.data.sentence;

                            $scope.sentence.lesson_sentence_id = sentence.lesson_sentence_id;
                            $scope.sentence.sentence = sentence.sentence;
                            $scope.sentence.translations = sentence.translations;

                            $scope.page.oldSentence = true;
                            $scope.page.newSentence = false;
                        });
                    }
                };

                $scope.sentence = {
                    lesson_sentence_id: null,
                    internal_name: null,
                    sentence: '',
                    translations: [],
                    create: function() {
                        $scope.page.serverErrors = [];

                        $scope.page.loading = true;
                        $scope.page.success = false;

                        Server.communicate('sentence.create', {
                            lesson_id: $scope.lesson.lesson_id,
                            sentence: $scope.sentence.sentence,
                            translations: GenericDomHelper.extractCourseTranslations($('.collapseable-fields')),
                            internal_name: $scope.sentence.internal_name
                        }).then(function(data) {

                            var d = data.data;

                            if (d.status === 'success') {
                                $scope.page.loading = false;
                                $scope.page.success = true;

                                $scope.sentence.internal_name = '';
                                $scope.sentence.sentence = '';
                                $scope.page.serverErrors = [];
                                $scope.page.numberOfTranslations = [0];
                                $scope.sentence.translations = [];

                                GenericDomHelper.resetCourseTranslations($('.collapseable-fields'));
                                findInternalNames();

                                return false;
                            }

                            if (d.status === 'failure') {
                                $scope.page.loading = false;
                                $scope.page.success = false;
                                $scope.page.serverErrors = d.errors;

                                return false;
                            }
                        }, function(data) {
                            RouteHelper.redirect(data);
                        });
                    },
                    update: function() {
                        $scope.page.serverErrors = [];

                        $scope.page.loading = true;
                        $scope.page.success = false;

                        Server.communicate('sentence.update_lesson_sentence', {
                            lesson_id: $scope.lesson.lesson_id,
                            lesson_sentence_id: $scope.sentence.lesson_sentence_id,
                            internal_name: $scope.sentence.internal_name,
                            sentence: $scope.sentence.sentence,
                            translations: GenericDomHelper.extractCourseObjectTranslations($('.collapseable-fields .translation-field'))
                        }).then(function(data) {
                            var d = data.data,
                                sentence;

                            if (d.status === 'success') {
                                $scope.page.loading = false;
                                $scope.page.success = true;

                                sentence = d.data.sentence;

                                $scope.sentence.internal_name = sentence.internal_name;
                                $scope.sentence.sentence = sentence.sentence;
                                $scope.sentence.lesson_sentence_id = sentence.lesson_sentence_id;
                            }

                            if (d.status === 'failure') {
                                $scope.page.loading = false;
                                $scope.page.success = false;

                                $scope.page.serverErrors = d.errors;
                            }
                        });
                    }
                };
            }
        ]
    };
})
.directive('addClass', function() {
    return {
        restrict: 'E',
        replace: true,
        template: '<a href="#" ng-click="openModal()" class="nav-link">Add class<i class="fa fa-bell"></i></a>',
        scope: {},
        controller: [
            '$scope',
            'ngDialog',
            function(
                $scope,
                ngDialog
            ) {
            $scope.openModal = function() {
                ngDialog.open({
                    template: 'classModal.html',
                    className: 'ngdialog-theme-default',
                    width: 400,
                    controller: [
                        '$scope',
                        '$rootScope',
                        'CourseInfo',
                        'Server',
                        'RouteHelper',
                        function(
                            $scope,
                            $rootScope,
                            CourseInfo,
                            Server,
                            RouteHelper
                        ) {

                        $scope.course = {
                            name: CourseInfo.name,
                            id: CourseInfo.id,
                            language_id: CourseInfo.language_id,
                            noLessons: false
                        };

                        $scope.class = {
                            name: null,
                            serverErrors: [],
                            loading: false,
                            success: false
                        };

                        $scope.class.createClass = function() {
                            $scope.class.loading = true;
                            $scope.class.success = false;

                            Server.communicate('class.create', {
                                name: $scope.class.name,
                                course_id: $scope.course.id
                            }).then(function(data) {
                                var d = data.data;

                                if (d.status === 'success') {
                                    $scope.class.loading = false;
                                    $scope.class.success = true;
                                    $scope.class.name = '';

                                    $rootScope.$emit('action.add_class', {
                                        'class': d.data.class
                                    });

                                    return false;
                                }

                                if (d.status === 'failure') {
                                    $scope.class.serverErrors = d.errors;
                                    $scope.class.loading = false;
                                    $scope.class.success = false;

                                    return false;
                                }
                            }, function(data) {
                                RouteHelper.redirect(data);
                            });
                        }
                    }]
                })
            }
        }]
    }
});
