( function() {
    angular.module('app.factories', [])
        .factory('Path', ['$location', function ($location) {
            function Path() {
                var environment = null,
                    domain = null,
                    path = null,
                    url = null;

                var namespaces = {
                    language: {
                        create: 'admin/language/create',
                        find_all: 'admin/language/find-all',
                        update_working_language: 'admin/language/update-working-language'
                    },
                    category: {
                        create: 'admin/category/create',
                        find_all: 'admin/category/find-all'
                    },
                    word: {
                        create: 'admin/word/add-word',
                        remove: 'admin/word/remove-word',
                        schedule_removal: 'admin/word/schedule-removal'
                    },
                    sentence: {
                        create: 'admin/sentence/create',
                        find_internal_names: 'admin/sentence/find-internal-names',
                        find_lesson_sentence: 'admin/sentence/find-lesson-sentence',
                        update_lesson_sentence: 'admin/sentence/update-lesson-sentence'
                    },
                    search: {
                        search: 'admin/search/search-words',
                        find_last_words: 'admin/search/find-last-words'
                    },
                    course: {
                        create: 'admin/course/create',
                        get_all: 'admin/course/get-all',
                        initial_info: 'admin/course/get-initial-course-info'
                    },
                    'class': {
                        create: 'admin/class/create',
                        get_all_by_course: 'admin/class/find-classes-by-course',
                        update: 'admin/class/update'
                    },
                    lesson: {
                        create: 'admin/lesson/create',
                        find_by_class: 'admin/lesson/find-lessons-by-class',
                        rename: 'admin/lesson/rename-lesson'
                    },
                    theory: {
                        create: 'admin/theory/create',
                        find_all_by_lesson: 'admin/theory/find-all-by-lesson',
                        rename: 'admin/theory/rename',
                        create_deck: 'admin/theory/create-deck',
                        find_decks_by_theory: 'admin/theory/find-decks-by-theory',
                        find_deck: 'admin/theory/find-deck',
                        find_deck_sounds: 'admin/theory/find-deck-sounds',
                        find_deck_by_internal_name: 'admin/theory/find-deck-by-internal-name'
                    }
                };

                this.namespace = function (nms) {
                    var splitted = nms.split('.');
                    var namespaceType = splitted[0],
                        namespaceUrl = splitted[1];

                    if (!namespaces.hasOwnProperty(namespaceType)) {
                        throw new Error('Unknown namespace ' + namespaceType);
                    }

                    if (!namespaces[namespaceType].hasOwnProperty(namespaceUrl)) {
                        throw new Error('Unknown url ' + namespaceUrl);
                    }

                    url = namespaces[namespaceType][namespaceUrl];
                    return this;
                };

                this.construct = function () {
                    if (environment === null || path === null || url === null) {
                        throw new Error('Url cannot be constructed beacuse some of the parameters are null');
                    }

                    return domain + path + environment + url;
                };

                if ($location.host() === 'localhost') {
                    domain = $location.protocol() + '://' + $location.host() + ':' + $location.port();
                    path = $location.absUrl().slice(domain.length);

                    if (/app_dev.php\/?/.test(path)) {
                        path = path.replace(/app_dev.php\/?/, '');
                        environment = 'web/app_dev.php/';
                    }
                    else {
                        environment = '';
                    }

                    return this;
                }

                domain = $location.protocol() + '://' + $location.host() + ':' + $location.port();
                path = '/';
                environment = (/app_dev.php\/?/.test($location.absUrl())) ? 'web/app_dev.php/' : '';
            }

            return new Path();
        }])
        .factory('xFormParamConverter', [function(obj) {
            function XFormParamConverter() {
                this.convert = function(obj) {
                    var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

                    for(name in obj) {
                        value = obj[name];

                        if(value instanceof Array) {
                            for(i=0; i<value.length; ++i) {
                                subValue = value[i];
                                fullSubName = name + '[' + i + ']';
                                innerObj = {};
                                innerObj[fullSubName] = subValue;
                                query += this.convert(innerObj) + '&';
                            }
                        }
                        else if(value instanceof Object) {
                            for(subName in value) {
                                subValue = value[subName];
                                fullSubName = name + '[' + subName + ']';
                                innerObj = {};
                                innerObj[fullSubName] = subValue;
                                query += this.convert(innerObj) + '&';
                            }
                        }
                        else if(value !== undefined && value !== null)
                            query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                    }

                    return query.length ? query.substr(0, query.length - 1) : query;
                }
            }

            return new XFormParamConverter(obj);
        }])
        .factory('Server', ['$http', 'Path', 'xFormParamConverter', function($http, Path, xFormConverter) {
            function Server() {
                this.communicate = function(path, data, enctype) {
                    var options = {
                        method: 'POST',
                        url: Path.namespace(path).construct(),
                        data: xFormConverter.convert(data)
                    };

                    var headers =  {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    };

                    options.headers = headers;

                    return $http(options);
                }
            }

            return new Server();
        }])
        .factory('LanguageProvider', function() {
            function LanguageProvider() {
                var language = null,
                    allLanguages = null,
                    isDefault = false;

                this.setLanguage = function(providedLanguage) {
                    language = providedLanguage;
                };

                this.getLanguage = function() {
                    return language;
                };

                this.hasLanguages = function() {
                    return allLanguages !== null;
                };

                this.getAllLanguages = function() {
                    return allLanguages;
                };

                this.isDefault = function() {
                    return isDefault;
                };

                this.setLanguages = function(languages) {
                    if (language.value === null) {
                        isDefault = true;
                    }

                    allLanguages = languages;
                }
            }

            return new LanguageProvider();
        })
        .factory('PaginationBuilder', function() {
            function PaginationBuilder() {
                var paginatons = {},
                    pagObject = function() {
                        var current = 0,
                            step,
                            max = null;

                        this.step = function(a) {
                            step = a;
                        };

                        this.current = function() {
                            return current;
                        };

                        this.next = function() {
                            current+=step;

                            return current;
                        };

                        this.previous = function() {
                            current-=step;

                            if (current <= 0) {
                                current = 0;
                            }

                            return current;
                        };

                        this.reset = function() {
                            current = 0;
                        }
                    };

                this.create = function(name) {
                    if (paginatons.hasOwnProperty(name)) {
                        return paginatons[name];
                    }

                    paginatons[name] = new pagObject();

                    return paginatons[name];
                }
            }

            return new PaginationBuilder();
        })
        .factory('GenericDomHelper', function() {
            function GenericDomHelper() {
                this.extractCourseTranslations = function(transElem) {
                    var translations = [],
                        transElems = transElem.find('.translation');

                    for (var i = 0; i < transElems.length; i++) {
                        var value = $(transElems[i]).val();

                        if (value !== '') {
                            translations.push(value);
                        }
                    }

                    return translations;
                };

                this.extractCourseObjectTranslations = function(transField) {
                    var translations = [],
                        len = transField.length,
                        i,
                        transId,
                        translation = {};

                    for (i = 0; i < len; i++) {
                        transId = $(transField[i]).find('span').attr('id');
                        translation = $(transField[i]).find('.translation').val();

                        translations.push({
                            id: transId,
                            translation: translation
                        });
                    }

                    return translations;
                };

                this.resetCourseTranslations = function(transElem) {
                    for (var i = 0; i < transElem.length; i++) {
                        $(transElem[i]).val('');
                    }
                }
            }

            return new GenericDomHelper();
        })
        .factory('CourseInfo', function() {
            function CourseInfo() {
                this.name = null;
                this.language_id = null;
                this.id = null;

                this.init = function(course) {
                    this.name = course.name;
                    this.language_id = course.language_id;
                    this.id = course.id;
                };

                this.reset = function() {
                    this.name = null;
                    this.language_id = null;
                    this.id = null;
                };
            }

            return new CourseInfo();
        })
        .factory('RouteHelper', function() {
            function RouteHelper() {
                this.redirect = function(data) {
                    if (data.status === 403) {
                        window.location.href = data.data.redirect_url;
                    }
                }
            }

            return new RouteHelper();
        })
} () );
