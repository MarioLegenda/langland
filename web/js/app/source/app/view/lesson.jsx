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

    _createPresentationItem(item) {
        return <div className="animated fadeIn fadeOut presentation-item">
            <h1>{item.name}</h1>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus
                lobortis commodo quam vel dictum. Interdum et malesuada fames ac
                ante ipsum primis in faucibus. Aenean vehicula neque ante.
                Duis euismod nibh non aliquet pretium. Morbi purus lorem, porta
                in ultricies a, suscipit faucibus tortor. Quisque eget sem in
                quam auctor faucibus. Phasellus dictum eros erat, iaculis varius
                arcu ultricies eget. Donec luctus consequat quam, vel pretium sem.
            </p>
            <button className="learn-button">Learn <i className="learn-button-icon fa fa-angle-right"></i></button>
        </div>;
    }

    enterLesson(e) {
        e.preventDefault();

        if (this.props.item.is_available === 0) {
            console.log('Lesson is not available');
            return false;
        }

        const presentationItem = this._createPresentationItem(this.props.item);

        this.props.displayPresentationItem(presentationItem);
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