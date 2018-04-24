import React from "react";
import { Link } from "react-router-dom";
import {factory} from "../../repository/factory.js";
import {LessonRunner} from "./runner.jsx";

export class LessonRunnerContainer extends React.Component {
    constructor(props) {
        super(props);

        console.log(props);

        this.learningSystemRepository = factory('learning-system');

        this.state = {
            learningLesson: null
        };

        console.log('Running lesson runner');
    }

    componentDidMount() {
        const learningLessonId = this.props.match.params.learningLessonId;

        this.learningSystemRepository.getLearningLessonById(learningLessonId, $.proxy(function(data) {
            data = data.resource.data;

            data.json_lesson = JSON.parse(data.json_lesson);

            this.setState({
                learningLesson: data
            });
        }, this));
    }

    render() {
        const learningLesson = this.state.learningLesson;

        if (learningLesson === null) {
            return null;
        }

        return <div className="animated fadeIn languages-wrapper">
            <LessonRunner item={learningLesson}/>
        </div>
    }
}