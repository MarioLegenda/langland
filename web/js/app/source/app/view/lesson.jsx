import React from "react";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);

        this.enterLesson = this.enterLesson.bind(this);
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

    enterLesson(e) {
        e.preventDefault();

        if (this.props.item.is_available === 0) {
            console.log('Lesson is not available');
            return false;
        }

        console.log('Lesson is available');
    }

    render() {
        const item = this.props.item;
        const classes = this._makeClasses(item);
        const clickableMethod = (item.is_available === 1) ? this.enterLesson : null;

        return <div className="lesson-absolute-item-holder">
            <div className={classes['lesson-menu']}>
                <button className={classes['circle-wrapper']} onClick={clickableMethod}>
                    <span className="circle-wrapper-position lesson-text-wrapper">{item.name}</span>
                    {item.is_available === 0 &&
                        <span className="circle-wrapper-position lesson-icon-wrapper fa fa-lock"></span>
                    }
                </button>
            </div>
        </div>
    }
}