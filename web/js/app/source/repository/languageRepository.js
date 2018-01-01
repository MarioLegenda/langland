import {global, user} from "../../../global/constants.js";

export class LanguageRepository {
    constructor(cache) {
        this.routes = {
            get_all_languages: global.base_url + 'api/v1/language'
        };

        this.cache = cache;
    }

    getAllAlreadyLearning(success, failure) {
        const cacheKey = 'LanguageRepository-getAllAlreadyLearning';

        if (this.cache.has(cacheKey)) {
            const cacheValue = this.cache.get(cacheKey);

            success(cacheValue.data, cacheValue.success, cacheValue.xhr);

            return null;
        }

        $.ajax({
            url: this.routes.get_all_languages,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure).then($.proxy(function(data, success, xhr) {
            if (typeof data !== 'undefined' && data !== null) {
                this.cache.add(cacheKey, {
                    data: data,
                    success: success,
                    xhr: xhr
                });
            }
        }, this));
    }
}

