import {envr} from './env.js';

class Repository {
    fetchLessons(url) {
        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/find-lessons-by-course',
            method: 'GET'
        });
    }
}

export const DataSource = new Repository();