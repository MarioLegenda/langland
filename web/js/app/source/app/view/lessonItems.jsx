import React from "react";

import {LessonItem} from "./lessonItem.jsx";

export class LessonItems extends React.Component {
    constructor(props) {
        super(props);
    }

    _buildItems(items) {
        const courses = items.data.blocks.courses;

        return courses.map((item, key) => {
            return <LessonItem key={key} item={item}/>
        });
    }

    render() {
        const items = this._buildItems(this.props.items);

        return <div>
            {items}
        </div>
    }
}