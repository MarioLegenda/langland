import React from 'react';

import {RemovableTextarea, Label} from './../source/fields.jsx';

export class LessonTextControl extends React.Component {
    constructor(props) {
        super(props);

        this.addLessonText = this.addLessonText.bind(this);
        this.removeLessonText = this.removeLessonText.bind(this);
        this.dataCollector = this.dataCollector.bind(this);

        this.state = {};

        this.state.lessonTexts = [];
        this.state.textValues = [];
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.lessonTexts.length === 0) {
            this.setState(function(prevState) {
                prevState.lessonTexts = [];
                prevState.textValues = [];
            });
        }
    }

    removeLessonText(controlKey) {
        this.setState(function(prevState) {
            prevState.lessonTexts.splice(controlKey, 1);
            prevState.textValues.splice(controlKey, 1);

            this.props.textCollector(prevState.textValues);

            return prevState;
        });
    }

    dataCollector(key, value) {
        this.setState(function(prevState) {
            prevState.textValues[key] = value;

            this.props.textCollector(prevState.textValues);

            return prevState;
        });
    }

    addLessonText() {
        this.setState(function(prevState) {
            const len = prevState.lessonTexts.length;

            return prevState.lessonTexts.push(<RemovableTextarea
                key={len}
                controlKey={len}
                remove={this.removeLessonText}
                buttonClass={'btn btn-sm btn-danger margin-top-20'}
                className={"form-control lessonText"}
                buttonText={"Remove lesson text"}
                dataCollector={this.dataCollector}
            />);
        });
    }

    render() {
        const lessonTexts = this.state.lessonTexts;

        return <div className="col-xs-12 no-padding margin-top-10">
            <div className={"col-xs-12 no-padding bottom-line margin-bottom-10"}>
                <Label labelText={"Lesson texts: "}/>
            </div>

            {lessonTexts}

            <div className="col-xs-12 no-padding">
                <button className="btn btn-sm btn-info margin-top-20" onClick={this.addLessonText}>Add lesson text</button>
            </div>
        </div>
    }
}