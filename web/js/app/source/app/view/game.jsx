import React from "react";

export class Game extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const item = this.props.item;

        return <div className="lesson-absolute-item-holder">
            <div className="available-lesson-menu-item">
                <button className="available-circle-wrapper">
                    <span className="circle-wrapper-position lesson-text-wrapper">{item.id}</span>
                </button>
            </div>
        </div>
    }
}