import React from "react";
import { Link } from "react-router-dom";
import {
    Step,
    Stepper,
    StepButton,
} from 'material-ui/Stepper';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import {factory} from "../../repository/factory.js";

export class LessonContainer extends React.Component {
    constructor(props) {
        super(props);

        this.learningSystemRepository = factory('learning-system');
    }

    componentDidMount() {
        const learningLessonId = this.props.match.params.learningLessonId;

        this.learningSystemRepository.getLearningLessonById(learningLessonId);
    }

    render() {
        return <div></div>;
    }
}