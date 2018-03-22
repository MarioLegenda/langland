import React from "react";
import {Lesson} from "./lesson.jsx";

export class LessonItem extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const course = this.props.item.course,
              lessons = this.props.item.lessons.map((item, index) => {
                  return <Lesson key={index} item={item}/>
              });

        return <div className="course-list">
            <h1>{course.type}</h1>
            <div className="lesson-items">
                {lessons}
            </div>
        </div>
    }
}