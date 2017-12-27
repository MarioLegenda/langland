import {user} from "../../../global/constants.js";

export class UserRepository {
    constructor() {
        this.routes = {
            armor_get_logged_in_user: '/app_dev.php/langland/get-logged-in-user',
        };

        this.loggedInUser = null;
    }

    getLoggedInUser(success, failure) {
        $.ajax({
            url: this.routes.armor_get_logged_in_user,
            method: 'GET',
            contentType: 'application/json'
        }).done($.proxy(function(data) {
            data = JSON.parse(data);

            user.current = data;

            success(data);

            this.loggedInUser = data;
        }, this)).fail(failure);
    }
}