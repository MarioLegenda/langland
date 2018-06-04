import {global, user} from "../../../global/constants.js";

export class LanguageRepository {
    constructor(cache) {
        this.routes = {
            get_all_languages: global.base_url + 'api/v1/language/',
            get_language_info: global.base_url + 'api/v1/language/language-info/'
        };

        this.cache = cache;
    }

    getAllAlreadyLearning(success, failure) {
        $.ajax({
            url: this.routes.get_all_languages,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    getLanguageInfo(languageId, success, failure) {
        $.ajax({
            url: this.routes.get_language_info + languageId,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }
}

