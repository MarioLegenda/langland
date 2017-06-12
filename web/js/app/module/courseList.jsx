import React from 'react';
import {routes, RouteCreator} from './routes.js';

import { Link } from 'react-router-dom';

class CourseItem extends React.Component {
    constructor(props) {
        super(props);

        this.startCourse = this.startCourse.bind(this);
    }

    startCourse() {

    }

    render() {
        const that = this;

        const items = this.props.items.map(function(item, index) {
            let inactiveItemClass = '',
                inactiveStartLinkClass = '';

            const languageName = that.props.languageData.languageName,
                  courseName = item.course.courseUrl,
                  learningUserCourseId = item.id;

            const courseUrl = RouteCreator.create('app_course_actual_app_dashboard', [courseName, learningUserCourseId]);

            if (item.hasPassed === false && item.course.initialCourse === false) {
                inactiveItemClass = 'inactive';
                inactiveStartLinkClass = 'inactive-start-link'
            }

            return (
                <div key={index} className={"animated fadeInDown item relative " + inactiveItemClass}>
                    {item.hasPassed === false && item.course.initialCourse === false &&
                    <div className="inactive-capsule absolute"></div>
                    }

                    <div className="title-holder margin-bottom-30">
                        <h2 className="full-width-item-name">{item.course.name}</h2>
                    </div>

                    <p className="margin-bottom-30">{item.course.whatToLearn}</p>

                    {item.hasPassed === false && item.course.initialCourse === false &&
                    <div className="start-link">
                        <a
                            className={inactiveStartLinkClass}
                            data-item-id={item.id}>
                            Start
                        </a>
                    </div>
                    }

                    {item.course.initialCourse === true &&
                    <div className="start-link">
                        <Link to={courseUrl} data-item-id={item.id} onClick={that.startCourse}>Start</Link>
                    </div>
                    }
                </div>
            )
        });

        return (
            <div className="list">
                {items}
            </div>
        )
    }
}

export class CourseListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: null
        }
    }

    _fetchCourseList() {
        this.props.DataSource.fetchCourseList()
            .done(jQuery.proxy(function(data) {
                if (data.status === 'success') {
                    this.setState({
                        items: data.data
                    });
                }
            }, this));
    }

    componentDidMount() {
        this._fetchCourseList();
    }

    render() {
        if (this.state.items === null) {
            return null;
        }

        const items = this.state.items;
        const languageData = this.props.languageData;

        return (
            <div className="component">
                <CourseItem items={items} languageData={languageData}/>
            </div>
        )
    }
}
