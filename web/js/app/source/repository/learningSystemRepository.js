import {global, user} from "../../../global/constants.js";

export class LearningSystemRepository {
    constructor() {
        this.routes = {
            initial_data_creation: global.base_url + 'api/v1/learning-system/make-initial-data-creation',
        }
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
}