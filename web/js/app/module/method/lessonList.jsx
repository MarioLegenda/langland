import React from 'react';
import {RouteCreator} from './../routes.js';
import { Link } from 'react-router-dom';

class LessonList extends React.Component {
    constructor(props) {
        super(props);

        this.chooseLesson = this.chooseLesson.bind(this);
    }

    chooseLesson(e) {
        e.preventDefault();

        const itemIndex = e.currentTarget.getAttribute('data-lesson-index');
        const item = this.props.items[itemIndex];

        if (item.hasPassed === false && item.lesson.isInitialLesson === false) {
            return false;
        }

        this.props.showLesson(itemIndex);
    }

    render() {
        const that = this;

        const items = this.props.items.map(function(item, index) {
            const passedClass = (item.hasPassed === true) ? 'passed-lesson' : '';
            return (
                <div key={index}>
                    {item.lesson.isInitialLesson === true &&
                    <div onClick={that.chooseLesson} data-lesson-index={index} className={"lesson " + passedClass}>
                        <div>
                            <h1>{item.lesson.name.toUpperCase()}</h1>
                        </div>

                        {item.hasPassed === true &&
                            <i className="fa fa-check"></i>
                        }
                    </div>
                    }

                    {item.hasPassed === false && item.isEligable === true &&
                    <div onClick={that.chooseLesson} data-lesson-index={index} className={"lesson " + passedClass}>
                        <div>
                            <h1>{item.lesson.name.toUpperCase()}</h1>
                        </div>

                        {item.hasPassed === true &&
                        <i className="fa fa-check"></i>
                        }
                    </div>
                    }

                    {item.hasPassed === false && item.lesson.isInitialLesson === false && item.isEligable === false &&
                    <div className="unpassed-lesson">
                        <div>
                            <h1>{item.lesson.name.toUpperCase()}</h1>
                        </div>
                    </div>
                    }
                </div>
            )
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

        this.showLessonStart = this.showLessonStart.bind(this);
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

    showLessonStart(index) {
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
            <div className="animated fadeInDown lesson-list">
                <LessonList items={items} showLesson={this.showLessonStart}/>
                <LessonStart
                    item={currentItem}
                    courseName={courseName}
                    learningUserCourseId={learningUserCourseId}
                />
            </div>
        )
    }
}