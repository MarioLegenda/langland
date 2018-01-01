import {global, user} from "../../../global/constants.js";

export class LanguageRepository {
    constructor() {
        this.routes = {
            get_all_languages: global.base_url + 'api/v1/language',
            get_language_info: global.base_url + 'api/v1/language/language-info/',
            mark_language_info_looked: global.base_url + 'api/v1/language/language-info/mark-language-info-looked',
            is_language_info_looked: global.base_url + 'api/v1/language/language-info/is-language-info-looked',
        };
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

    markLanguageInfoLooked(success, failure) {
        $.ajax({
            url: this.routes.mark_language_info_looked,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    isLanguageInfoLooked(success, failure) {
        $.ajax({
            url: this.routes.is_language_info_looked,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }
}

