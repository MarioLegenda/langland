$(document).ready(function() {
    var util = {};

    ( function() {
        function Util() {
            this.getElementSize = function(elem) {
                var viewPortWidth;
                var viewPortHeight;

                // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
                if (typeof elem.innerWidth != 'undefined') {
                    viewPortWidth = elem.innerWidth;
                    viewPortHeight = elem.innerHeight;
                } else if (typeof elem.offsetWidth != 'undefined') {
                    viewPortWidth = elem.offsetWidth;
                    viewPortHeight = elem.offsetHeight;
                }

                return {
                    width: viewPortWidth,
                    height: viewPortHeight
                };
            };

            this.parseUrl = function() {
                var pathname = location.pathname;

                if (/\/web\/app_dev.php/.test(pathname)) {
                    var path = pathname.replace('/web/app_dev.php', "");

                    if (path.length === 0) {
                        return '/';
                    }

                    return path.substring(1).split('/');
                }
            }
        }

        util = new Util();
    } (util));

    ( function() {
            var windowSize,
                landingPageElem = $('.landing-page'),
                overlay = $('.transparent-overlay');

            windowSize = util.getElementSize(window);

            landingPageElem.css({
                width: windowSize.width,
                height: windowSize.height
            });

            overlay.css({
                width: windowSize.width,
                height: windowSize.height
            });
    } (util) );
});