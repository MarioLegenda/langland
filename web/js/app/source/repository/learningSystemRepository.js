import {global, user} from "../../../global/constants.js";

export class LearningSystemRepository {
    constructor() {
        this.routes = {
            initial_data_creation: global.base_url + 'api/v1/learning-system/make-initial-data-creation',
            get_learning_lesson_by_id: global.base_url + 'api/v1/learning-system/get-learning-lesson-by-id'
        }
    }

    _makeGenericCall(route, method, success, failure) {
        $.ajax({
            url: route,
            method: method,
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    makeInitialDataCreation(success, failure) {
        $.ajax({
            url: this.routes.initial_data_creation,
            method: 'POST',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }

    getLearningLessonById(learningLessonId, success, failure) {
        this._makeGenericCall(
            this.routes.get_learning_lesson_by_id + `/${learningLessonId}`,
            'GET',
            success,
            failure
        );
    }
}