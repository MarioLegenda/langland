import React from 'react';
import {RouteCreator} from './../routes.js';
import { Link } from 'react-router-dom';

const LessonItem = (props) => (
    <div onClick={props.chooseLesson} data-lesson-index={props.index} className={"lesson " + props.className}>
        <div>
            <h1>{props.lessonName}</h1>
        </div>

        {props.hasPassed === true &&
            <i className="fa fa-check"></i>
        }

        {props.hasPassed === false && props.isEligable === false &&
            <i className="fa fa-lock"></i>
        }
    </div>
);

class LessonList extends React.Component {
    constructor(props) {
        super(props);

        this.chooseLesson = this.chooseLesson.bind(this);
    }

    chooseLesson(e) {
        e.preventDefault();

        const itemIndex = e.currentTarget.getAttribute('data-lesson-index');
        const item = this.props.items[itemIndex];

        if (item.hasPassed === false && item.lesson.isInitialLesson === false && item.isEligable === false) {
            return false;
        }

        this.props.showLesson(itemIndex);
    }

    render() {
        const items = this.props.items.map((item, index) => {
            const passedClass = (item.hasPassed === true) ? 'passed-lesson' : '';

            return <div key={index}>
                {item.isEligable === true &&
                    <LessonItem
                        chooseLesson={this.chooseLesson}
                        index={index}
                        className={passedClass}
                        lessonName={item.lesson.name.toUpperCase()}
                        hasPassed={item.hasPassed}
                        isEligable={item.isEligable}
                    />
                }

                {item.hasPassed === true &&
                <LessonItem
                    chooseLesson={this.chooseLesson}
                    index={index}
                    className={passedClass}
                    lessonName={item.lesson.name.toUpperCase()}
                    hasPassed={item.hasPassed}
                    isEligable={item.isEligable}
                />
                }

                {item.hasPassed === false && item.isEligable === false &&
                <LessonItem
                    index={index}
                    className="unpassed-lesson"
                    lessonName={item.lesson.name.toUpperCase()}
                    hasPassed={item.hasPassed}
                    isEligable={item.isEligable}
                />
                }
            </div>
        });

        return (
            <div className="lesson-list">
                {items}
            </div>
        )
    }
}

class LessonStart extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.item === null) {
            return null;
        }

        const
            item = this.props.item,
            courseName = this.props.courseName,
            learningUserCourseId = this.props.learningUserCourseId,
            learningUserLessonId = item.learningUserLessonId,
            lessonName = item.lesson.lessonUrl,
            buttonText = (item.hasPassed === true) ? 'Do again' : 'Start';

        return (
            <div>
                {item !== null &&
                <div className="animated fadeInDown lesson-start-item margin-top-30">
                    <h1 className="full-width align-left margin-bottom-30">{item.lesson.name}</h1>

                    <p className="margin-bottom-30">{item.lesson.description}</p>

                    <div className="start-link margin-bottom-30">
                        <Link to={RouteCreator.create('app_page_lesson_start', [courseName, learningUserCourseId, lessonName, learningUserLessonId])}>{buttonText}</Link>
                    </div>
                </div>
                }
            </div>
        )
    }
}

export class LessonListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: null,
            itemsFetched: false,
            currentItem: null
        };

        this.showLesson = this.showLesson.bind(this);
    }

    _fetchLessons() {
        jQuery.ajax({
            url: RouteCreator.create('app_lesson_list', [this.props.learningUserCourseId]),
            method: 'GET'
        }).done(jQuery.proxy(function (data) {
            this.setState({
                items: data.data,
                itemsFetched: true
            });
        }, this));
    }

    showLesson(index) {
        this.setState({
            currentItem: this.state.items[index]
        });

        $("html, body").animate({ scrollTop: $(document).height() }, 3000);
    }

    componentDidMount() {
        this._fetchLessons();
    }

    render() {
        const
            items = this.state.items,
            itemsFetched = this.state.itemsFetched,
            currentItem = this.state.currentItem,
            courseName = this.props.courseName,
            learningUserCourseId = this.props.learningUserCourseId;

        if (items === null) {
            return null;
        }

        if (itemsFetched === false) {
            return null;
        }

        return (
            <div className="animated fadeInDown lesson-list working-area">
                <LessonList items={items} showLesson={this.showLesson}/>
                <LessonStart
                    item={currentItem}
                    courseName={courseName}
                    learningUserCourseId={learningUserCourseId}
                />
            </div>
        )
    }
}