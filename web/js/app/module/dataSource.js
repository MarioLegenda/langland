import {routes, RouteCreator} from './routes.js';
import {envr} from './env.js';

class Repository {
    fetchLessonList(learningUserCourseId) {
        return jQuery.ajax({
            url: RouteCreator.create('app_lesson_list', [learningUserCourseId]),
            method: 'GET'
        });
    }
}

export const DataSource = new Repository();