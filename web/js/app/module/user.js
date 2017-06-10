import {routes} from './routes.js';

class LearningUser {
    constructor() {
        this.user = null;

        this._fetchLearningUser();
    }

    _fetchLearningUser() {
        jQuery.ajax({
            url: routes.app_find_learning_user,
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            this.user = data.data;
        }, this));
    }

    getLearningUser() {
        if (this.user === null) {
            throw new Error('User should have been fetched but did not');
        }

        return this.user;
    }
}

export const learningUser = new LearningUser();