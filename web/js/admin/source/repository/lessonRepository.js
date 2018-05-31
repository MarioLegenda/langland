import {UserRepository} from "./userRepository.jsx";

export class LessonRepository {
    constructor() {
        this.urlMetadata = this._extractUrlInformation();
        this.userRepository = new UserRepository();

        this.routes = {
            admin_api_lesson_new: '/app_dev.php/admin/api/v1/lesson/new',
            admin_api_lesson_update: '/app_dev.php/admin/api/v1/lesson/update',
            admin_get_lesson_by_id: '/app_dev.php/admin/api/v1/lesson/{id}',
        }
    }

    _extractUrlInformation() {
        const urlSplitted = location.pathname.split('/');
        const info = urlSplitted.filter(function(val) {
            const entry = parseInt(val);

            if (entry !== Number.NaN) {
                return entry;
            }
        });

        if (info.length < 0 || info.length > 1) {
            throw new Error(`Course and lesson could not be determined from url ${location.pathname}`);
        }

        return {
            lessonId: (info.length === 1) ? parseInt(info[0]) : null
        }
    }

    newLesson(data, success, failure) {
        $.ajax({
            url: this.routes.admin_api_lesson_new,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json'
        }).done(success).fail(failure);
    }

    updateLesson(data, success, failure) {
        $.ajax({
            url: this.routes.admin_api_lesson_update,
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json'
        }).done(success).fail(failure);
    }

    getLessonById(success, failure) {
        const lessonId = this.urlMetadata.lessonId;

        if (lessonId !== null) {
            this.userRepository.getLoggedInUser($.proxy(function(data) {
                $.ajax({
                    url: this.routes.admin_get_lesson_by_id.replace(/{id}/, lessonId),
                    method: 'GET',
                    contentType: 'application/json',
                    headers: {
                        'X-LANGLAND-PUBLIC-API': data.username
                    }
                }).done(success).fail(failure);
            }, this), function(xhr) {
                throw new Error('Invalid request');
            });
        }
    }
}