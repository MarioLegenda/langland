import {routes, RouteCreator} from './routes.js';

class Repository {
    fetchLessonList(learningUserCourseId) {
        return jQuery.ajax({
            url: RouteCreator.create('app_lesson_list', [learningUserCourseId]),
            method: 'GET'
        });
    }

    fetchLearnableLanguages() {
        return jQuery.ajax({
            url: routes.app_find_learnable_languages,
            method: 'POST'
        });
    }

    fetchLearningLanguages() {
        return jQuery.ajax({
            url: routes.app_find_learning_languages,
            method: 'POST'
        });
    }

    fetchLoggedInUser() {
        return jQuery.ajax({
            url: routes.app_logged_in_user,
            method: 'POST'
        });
    }

    fetchCourseList() {
        return jQuery.ajax({
            url: routes.app_language_course_list,
            method: 'POST',
        });
    }

    fetchIsInfoLooked() {
        return jQuery.ajax({
            url: routes.app_course_language_info_exists,
            method: 'POST'
        });
    }

    fetchMarkInfoLooked() {
        return jQuery.ajax({
            url: routes.app_course_mark_info_looked,
            method: 'POST'
        });
    }

    fetchLanguaageInfos(languageId) {
        return jQuery.ajax({
            url: routes.app_course_language_infos,
            method: 'POST',
            data: {
                languageId: languageId
            }
        });
    }

    fetchLesson(learningUserLessonId) {
        return jQuery.ajax({
            url: RouteCreator.create('app_learning_user_lesson', [learningUserLessonId]),
            method: 'GET'
        });
    }

    fetchMarkLessonFinished(...args) {
        return jQuery.ajax({
            url: routes.app_learning_user_mark_lesson_passed,
            method: 'POST',
            data: {
                learningUserLessonId: args[0],
                courseName: args[1],
                learningUserCourseId: args[2]
            }
        });
    }

    fetchGamesList(learningUserCourseId) {
        return jQuery.ajax({
            url: RouteCreator.create('app_find_available_games', [learningUserCourseId]),
            method: 'GET'
        });
    }

    createLearningUser(languageId) {
        return jQuery.ajax({
            url: routes.app_create_learning_user,
            method: 'POST',
            data: {
                languageId: languageId
            }
        });
    }
}

export const DataSource = new Repository();