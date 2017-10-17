export class Lesson {
    constructor(...props) {
        this.id = props.id;
        this.name = props.name;
        this.description = props.description;
        this.lessonDescription = props.lessonDescription;
        this.isInitialLesson = props.isInitialLesson;
        this.createdAt = props.createdAt;
        this.updatedAt = props.updatedAt;
        this.course = props.course;
        this.lessonTexts = props.lessonTexts;
    }
}