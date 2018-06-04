import {global, user} from "../../../global/constants.js";

export class LanguageSessionRepository {
    constructor() {
        this.routes = {
            armor_register_language_session: global.base_url + 'api/v1/language-session/register-language-session',
        };

        this.currentLearningUser = null;
    }

    registerLanguageSession(languageId, success, failure) {
        if (this.currentLearningUser !== null) {
            success(this.currentLearningUser);

            return;
        }

        $.ajax({
            url: this.routes.armor_register_language_session,
            method: 'POST',
            data: {
                languageId: languageId
            },
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done($.proxy(function(data) {
            success(data);

            this.currentLearningUser = data.resource.data;
        }, this)).fail(failure);
    }
}