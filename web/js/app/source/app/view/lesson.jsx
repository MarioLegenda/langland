import React from "react";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);
    }

    _makeClasses(item) {
        let classes = {
            'lesson-menu': 'not-available-lesson-menu-item',
            'circle-wrapper': 'not-available-circle-wrapper'
        };

        if (item.is_available === 1) {
            classes['lesson-menu'] = 'available-lesson-menu-item';
            classes['circle-wrapper'] = 'available-circle-wrapper';
        }

        return classes;
    }

    render() {
        const item = this.props.item;
        const classes = this._makeClasses(item);

        return <div className="lesson-absolute-item-holder">
            <div className={classes['lesson-menu']}>
                <button className={classes['circle-wrapper']}>
                    <span className="circle-wrapper-position lesson-text-wrapper">{item.name}</span>
                    {item.is_available === 0 &&
                        <span className="circle-wrapper-position lesson-icon-wrapper fa fa-lock"></span>
                    }
                </button>
            </div>
        </div>
    }
}