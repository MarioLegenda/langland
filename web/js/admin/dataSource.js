import {envr} from './env.js';

class Repository {
    fetchLessons(url) {
        return jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/lesson/lessons-by-course',
            method: 'GET'
        });
    }

    fetchAutocompleteLessons(url) {
        return jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/lesson/lessons-by-course?type=autocomplete',
            method: 'GET'
        });
    }
}

export const DataSource = new Repository();