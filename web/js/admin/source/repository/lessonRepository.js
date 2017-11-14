export class LessonRepository {
    constructor() {
        this.course = this._extractCourse();

        this.routes = {
            admin_api_lesson_new: '/app_dev.php/admin/api/v1/lesson/new'
        }
    }

    _extractCourse() {
        const urlSplitted = location.pathname.split('/');
        const course = urlSplitted.filter(function(val) {
            const entry = parseInt(val);

            if (entry !== Number.NaN) {
                return entry;
            }
        });

        if (course.length !== 1) {
            throw new Error(`Course could not be determined from url ${location.pathname}`);
        }

        return parseInt(course[0]);
    }

    newLesson(data, success, failure) {
        data.course = this.course;

        $.ajax({
            url: this.routes.admin_api_lesson_new,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json'
        }).done(success).fail(failure);
    }
}