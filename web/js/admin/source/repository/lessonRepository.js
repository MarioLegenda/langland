export class LessonRepository {
    constructor() {
        this.urlMetadata = this._extractUrlInformation();

        this.routes = {
            admin_api_lesson_new: '/app_dev.php/admin/api/v1/lesson/new',
            public_api_get_lesson_by_id: '/app_dev.php/api/v1/lesson/{id}'
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

        if (info.length < 0 || info.length > 2) {
            throw new Error(`Course and lesson could not be determined from url ${location.pathname}`);
        }

        return {
            courseId: parseInt(info[0]),
            lessonId: (info.length > 1) ? info[1] : null
        }
    }

    newLesson(data, success, failure) {
        data.course = this.urlMetadata.courseId;

        $.ajax({
            url: this.routes.admin_api_lesson_new,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json'
        }).done(success).fail(failure);
    }

    getLessonById(success, failure) {
        const lessonId = this.urlMetadata.lessonId;

        $.ajax({
            url: this.routes.public_api_get_lesson_by_id.replace(/{id}/, lessonId),
            method: 'GET',
            contentType: 'application/json',
            headers: {
                'X-LANGLAND-PUBLIC-API': 'root'
            }
        }).done(success).fail(failure);
    }
}