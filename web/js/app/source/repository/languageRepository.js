import {global, user} from "../../../global/constants.js";

export class LanguageRepository {
    constructor() {
        this.routes = {
            get_all_languages: global.base_url + 'api/v1/language'
        }
    }

    getAll(success, failure) {
        $.ajax({
            url: this.routes.get_all_languages,
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': user.current.username
            }
        }).done(success).fail(failure);
    }
}

