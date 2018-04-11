import React from "react";
import {Lesson} from "./lesson.jsx";
import {Game} from "./game.jsx";

export class InnerItem extends React.Component {
    constructor(props) {
        super(props);

        this.displayPresentationItem = this.displayPresentationItem.bind(this);

        this.state = {
            presentationItem: null
        }
    }

    displayPresentationItem(item) {
        this.setState((prevState) => {
            prevState.presentationItem = item;
        });
    }

    render() {
        const presentationItem = this.state.presentationItem;
        const type = this.props.type;
        const course = this.props.item.course,
              items = this.props.item.items.map((item, index) => {
                  return (type === 'lesson') ?
                      <Lesson key={index} item={item} displayPresentationItem={this.displayPresentationItem}/> :
                      <Game key={index} item={item} displayPresentationItem={this.displayPresentationItem}/>
              });

        return <div className="course-list">
            <h1>{course.type}</h1>
            <div className="lesson-items">
                {items}

                {presentationItem}
            </div>
        </div>
    }
}