import {global, user} from "../../../global/constants.js";

export class LearningUserRepository {
    constructor() {
        this.routes = {
            register_learning_user: global.base_url + 'api/v1/learning-user/register-learning-user'
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
}