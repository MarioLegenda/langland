import React from "react";

export const Lesson = (props) => {
    return <div className="lesson-absolute-item-holder">
        <div className="lesson-menu-item">
            <button className="circle-wrapper">
                <span className="circle-wrapper-text lesson-text-wrapper">{props.item.name}</span>
            </button>
        </div>
    </div>
};