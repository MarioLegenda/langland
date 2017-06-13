import React from 'react';
import {RouteCreator} from './../routes.js';
import { Link } from 'react-router-dom';

import {ListingItem} from './listingItem.jsx';

class LessonList extends React.Component {
    constructor(props) {
        super(props);

        this.chooseItem = this.chooseItem.bind(this);
    }

    chooseItem(e) {
        e.preventDefault();

        const itemIndex = e.currentTarget.getAttribute('data-item-index');
        const item = this.props.items[itemIndex];

        if (item.hasPassed === false && item.lesson.isInitialLesson === false && item.isEligable === false) {
            return false;
        }

        this.props.showItem(itemIndex);
    }

    render() {
        const items = this.props.items.map((item, index) => {
            const passedClass = (item.hasPassed === true) ? 'passed-item' : '';

            return <div key={index}>
                {item.isEligable === true &&
                    <ListingItem
                        chooseItem={this.chooseItem}
                        index={index}
                        className={passedClass}
                        title={item.lesson.name.toUpperCase()}
                        hasPassed={item.hasPassed}
                        isEligable={item.isEligable}
                    />
                }

                {item.hasPassed === true &&
                <ListingItem
                    chooseItem={this.chooseItem}
                    index={index}
                    className={passedClass}
                    title={item.lesson.name.toUpperCase()}
                    hasPassed={item.hasPassed}
                    isEligable={item.isEligable}
                />
                }

                {item.hasPassed === false && item.isEligable === false &&
                <ListingItem
                    index={index}
                    className="unpassed-item"
                    title={item.lesson.name.toUpperCase()}
                    hasPassed={item.hasPassed}
                    isEligable={item.isEligable}
                />
                }
            </div>
        });

        return (
            <div>
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
                <div className="animated fadeInDown item-start-item margin-top-30">
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
            currentItem: null
        };

        this.showItem = this.showItem.bind(this);
    }

    showItem(index) {
        this.setState({
            currentItem: this.state.items[index]
        });

        $("html, body").animate({ scrollTop: $(document).height() }, 3000);
    }

    componentDidMount() {
        this.props.DataSource.fetchLessonList(this.props.learningUserCourseId)
            .done(jQuery.proxy(function (data) {
                if (data.status === 'success') {
                    this.setState({
                        items: data.data
                    });
                }
            }, this));
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

        return (
            <div className="animated fadeInDown item-list working-area">
                <LessonList items={items} showItem={this.showItem}/>
                <LessonStart
                    item={currentItem}
                    courseName={courseName}
                    learningUserCourseId={learningUserCourseId}
                />
            </div>
        )
    }
}