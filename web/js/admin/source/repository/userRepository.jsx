export class UserRepository {
    constructor() {
        this.routes = {
            armor_get_logged_in_user: '/app_dev.php/admin/get-logged-in-user',
        };

        this.loggedInUser = null;
    }

    getLoggedInUser(success, failure) {
        if (this.loggedInUser !== null) {
            success(this.loggedInUser);

            return;
        }

        $.ajax({
            url: this.routes.armor_get_logged_in_user,
            method: 'GET',
            contentType: 'application/json'
        }).done($.proxy(function(data) {
            data = JSON.parse(data);

            success(data);

            this.loggedInUser = data;
        }, this)).fail(failure);
    }
}