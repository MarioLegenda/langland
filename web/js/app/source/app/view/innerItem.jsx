import React from "react";
import {Lesson} from "./lesson.jsx";
import {Game} from "./game.jsx";

export class InnerItem extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const type = this.props.type;
        const course = this.props.item.course,
              items = this.props.item.items.map((item, index) => {
                  return (type === 'lesson') ?
                      <Lesson key={index} item={item}/> :
                      <Game key={index} item={item}/>
              });

        return <div className="course-list">
            <h1>{course.type}</h1>
            <div className="lesson-items">
                {items}
            </div>
        </div>
    }
}