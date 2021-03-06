import {global, user} from "../../../global/constants.js";

export class MetadataPresentationRepository {
    constructor() {
        this.routes = {
            get_learning_lesson_presentation: global.base_url + 'api/v1/learning-system/lesson-presentation'
        }
    }

    getLearningLessonPresentation(success, failure) {
        $.ajax({
            url: this.routes.get_learning_lesson_presentation,
            method: 'POST',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }
}