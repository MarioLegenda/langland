import React from 'react';
import {
    Step,
    Stepper,
    StepLabel,
    StepButton,
} from 'material-ui/Stepper';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import {Link} from 'react-router-dom';
import {env} from "../../../../global/constants.js";

export class LessonRunner extends React.Component {
    constructor(props) {
        super(props);

        this.handleNext = this.handleNext.bind(this);
        this.handlePrev = this.handlePrev.bind(this);

        this.state = {
            stepIndex: 0
        };
    }

    _hasFinished() {
        return this.state.stepIndex === (this.props.item.json_lesson.lessonTexts.length - 1);
    }

    handleNext() {
        if (this._hasFinished()) {
            return;
        }

        const {stepIndex} = this.state;
        this.setState({
            stepIndex: stepIndex + 1,
        });
    };

    handlePrev() {
        const {stepIndex} = this.state;
        if (stepIndex > 0) {
            this.setState({stepIndex: stepIndex - 1});
        }
    };

    getStepContent(stepIndex) {
        return this.props.item.json_lesson.lessonTexts[stepIndex]
    }

    render() {
        const stepIndex = this.state.stepIndex;
        const lessonTexts = this.props.item.json_lesson.lessonTexts;
        const lessonLanguageUrl = this.props.item.lesson_language_url;

        let steps = [];

        for (let i = 0; i < lessonTexts.length; i++) {
            steps.push(<Step key={i}>
                <StepButton
                    onClick={() => this.setState({stepIndex: i})}>

                    <StepLabel color="orange"></StepLabel>
                </StepButton>
            </Step>);
        }

        return <div className="base-runner">
            <MuiThemeProvider>

                <div className="runner">
                    <Link to={env.current + lessonLanguageUrl}>Quit lesson</Link>
                    <Stepper linear={false} activeStep={stepIndex}>
                        {steps}
                    </Stepper>

                    <div>
                        <div className="content">{this.getStepContent(stepIndex)}</div>
                        <div style={{marginTop: 12}}>
                            <div className="direction-buttons">
                                <FlatButton
                                    label="Previous"
                                    onClick={this.handlePrev}
                                    style={{marginRight: 12}}
                                />

                                <RaisedButton
                                    backgroundColor="orange"
                                    label={(this._hasFinished()) ? 'Finish' : 'Next'}
                                    primary={true}
                                    onClick={this.handleNext}
                                    style={{float: "right"}}
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </MuiThemeProvider>
        </div>
    }
}