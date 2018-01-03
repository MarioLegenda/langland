import {global, user} from "../../../global/constants.js";

export class LearningUserRepository {
    constructor() {
        this.routes = {
            register_learning_user: global.base_url + 'api/v1/learning-user/register-learning-user',
            mark_language_info_looked: global.base_url + 'api/v1/learning-user/language-info/mark-language-info-looked',
            is_language_info_looked: global.base_url + 'api/v1/learning-user/language-info/is-language-info-looked'
        }
    }

    registerLearningUser(languageId, success, failure) {
        $.ajax({
            url: this.routes.register_learning_user,
            method: 'POST',
            data: {
                languageId: languageId
            },
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