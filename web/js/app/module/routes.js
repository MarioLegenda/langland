import {envr} from './env.js';

export const routes = {
    app_course_language_info_exists: envr + 'langland/api/courses/is-info-looked',
    app_course_language_infos: envr + 'langland/api/courses/find-language-info',
    app_course_mark_info_looked: envr + 'langland/api/courses/mark-info-looked',
    app_language_course_list: envr + 'langland/api/courses/find-language-course-list',

    app_find_learnable_languages: envr + 'langland/api/language/find-learnable-languages',
    app_find_learning_languages: envr + 'langland/api/language/find-learning-languages',

    app_logged_in_user: envr + 'langland/api/user/find-logged-in-user',
    app_create_learning_user: envr + 'langland/api/user/create-learning-user',

    app_logout: envr + 'langland/logout',
    app_dashboard: envr + 'langland'
};

class AppRouter {
    constructor() {
        this.routes = {
            app_page_course_dashboard: envr + 'langland/course/:0/:1',
            app_course_actual_app_dashboard: envr + 'langland/dashboard/:0/:1',
            app_lesson_list: envr + 'langland/api/data/lesson-list/:0',
            app_page_lesson_list_dashboard: envr + 'langland/dashboard/:0/:1/lessons',
            app_page_lesson_start: envr + 'langland/dashboard/:0/:1/lesson/:2/:3'
        }
    }

    create(name, parameters) {
        if (this.routes.hasOwnProperty(name)) {
            let foundRoute = this.routes[name];

            for (let i = 0; i < parameters.length; i++) {
                let r = new RegExp(':' + i);

                foundRoute = foundRoute.replace(r, parameters[i])
            }

            return foundRoute;
        }

        throw new Error('AppRouter: Route ' + name + ' not found');
    }
}

export const RouteCreator = new AppRouter();