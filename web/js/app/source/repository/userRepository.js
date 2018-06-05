import {user, env} from "../../../global/constants.js";

export class UserRepository {
    constructor() {
        this.routes = {
            armor_get_logged_in_public_api_user: env.current + 'langland/get-logged-in-public-user',
        };

        this.loggedInUser = null;
    }

    getLoggedInUser(success, failure) {
        $.ajax({
            url: this.routes.armor_get_logged_in_public_api_user,
            method: 'GET',
            contentType: 'application/json'
        }).done($.proxy(function(data) {
            user.current = data.resource.data;

            success(data);

            this.loggedInUser = data;
        }, this)).fail(failure);
    }
}