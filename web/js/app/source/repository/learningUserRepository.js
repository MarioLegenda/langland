import {global, user} from "../../../global/constants.js";

export class LearningUserRepository {
    constructor() {
        this.routes = {
            armor_register_language_session: global.base_url + 'api/v1/language-session/register-language-session',
            mark_language_info_looked: global.base_url + 'api/v1/learning-user/language-info/mark-language-info-looked',
            is_language_info_looked: global.base_url + 'api/v1/learning-user/language-info/is-language-info-looked',
            get_dynamic_components_status: global.base_url + 'api/v1/learning-user/get-dynamic-components-status',
            get_questions: global.base_url + 'api/v1/learning-user/questions/get-questions',
            mark_questions_answered: global.base_url + 'api/v1/learning-user/questions/mark-questions-answered',
            validate_question_answers: global.base_url + 'api/v1/learning-user/questions/validate',
        };

        this.currentLearningUser = null;
    }

    registerLanguageSession(languageId, success, failure) {
        if (this.currentLearningUser !== null) {
            success(this.currentLearningUser);

            return;
        }

        $.ajax({
            url: this.routes.register_learning_user,
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

    getDynamicComponentsStatus(success, failure) {
        $.ajax({
            url: this.routes.get_dynamic_components_status,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    getQuestions(success, failure) {
        $.ajax({
            url: this.routes.get_questions,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    markQuestionsAnswered(data, success, failure) {
        $.ajax({
            url: this.routes.mark_questions_answered,
            method: 'POST',
            data: {
                questionAnswers: data
            },
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    validateQuestions(data, success, failure) {
        return $.ajax({
            url: this.routes.validate_question_answers,
            method: 'POST',
            data: {
                questionAnswers: data
            },
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }
}