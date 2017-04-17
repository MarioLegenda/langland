( function() {
    angular.module('admin.factories', [])
        .factory('Path', ['$location', function ($location) {
            function Path() {
                var environment = null,
                    domain = null,
                    path = null,
                    url = null;

                var namespaces = {
                    language: {
                        find_all: 'api/shared/language/find-all'
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
        }]);
} () );