import React from 'react';

import {InputText, SubmitButton, Label, Notification} from './../source/fields.jsx';
import {TipControl} from "./tipControl.jsx";
import {LessonTextControl} from "./lessonTextControl.jsx";
import {LessonRepository} from "../source/repository/lessonRepository";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);

        this.lessonRepository = new LessonRepository();

        this.setName = this.setName.bind(this);
        this.collectTips = this.collectTips.bind(this);
        this.collectLessonTexts = this.collectLessonTexts.bind(this);
        this.submit = this.submit.bind(this);

        this.state = {};

        this.state.model = {
            name: "",
            tips: [],
            lessonTexts: []
        };

        this.state.form = {
            internalError: false,
            isValid: false,
            errors: [],
            success: false
        }
    }

    componentDidMount() {

    }

    setName(value) {
        this.setState(function(prevState) {
            prevState.model.name = value;
        });
    }

    collectTips(tips) {
        this.setState(function(prevState) {
            prevState.model.tips = tips;
        });
    }

    collectLessonTexts(texts) {
        this.setState(function(prevState) {
            prevState.model.lessonTexts = texts;
        });
    }

    submit() {
        this.setState(function(prevState) {
            prevState.form.internalError = false;
            prevState.form.success = false;
        });

        const errors = this._validate();

        if (errors.length > 0) {
            this.setState(function(prevState) {
                prevState.form.errors = errors;
            });

            return;
        }

        this.lessonRepository.newLesson(this.state.model, $.proxy(function(data) {
            this.setState(function(prevState) {
                prevState.form = {
                    internalError: false,
                    isValid: false,
                    errors: [],
                    success: true
                };

                prevState.model = {
                    name: "",
                    tips: [],
                    lessonTexts: []
                };
            });
        }, this), $.proxy(function(xhr) {
            if (xhr.status === 500) {
                this.setState(function(prevState) {
                    prevState.form.internalError = true;
                });
            }
        }, this));
    }

    _validate() {
        let errors = [];
        const model = this.state.model;

        if (model.name.length === 0) {
            errors.push(<Notification key={0} className={'alert alert-danger'} message={'Name cannot be empty'} />)
        }

        if (model.lessonTexts.length === 0) {
            errors.push(<Notification key={1} className={'alert alert-danger'} message={'There has to be at least one lesson text associated with this lesson'} />)
        }

        return errors;
    }

    render() {
        const name = this.state.model.name;
        const tips = this.state.model.tips;
        const lessonTexts = this.state.model.lessonTexts;
        const internalError = this.state.form.internalError;
        const errors = this.state.form.errors;
        const success = this.state.form.success;

        return <div>
                    {internalError === true &&
                        <Notification className={'alert alert-danger'} message={"An internal error occurred"} />
                    }

                    {success === true &&
                        <Notification className={'alert alert-success'} message={"Lesson created successfully"} />

                    }

                    {errors.length > 0 &&
                    <div>{errors}</div>
                    }

                    <div className={"margin-top-20"}>
                        <Label labelText={"Name:"}/>
                        <InputText
                            labelText={"Name:"}
                            inputClass={"form-control"}
                            dataCollector={this.setName}
                            inputValue={name}
                        />
                    </div>

                    <TipControl tipCollector={this.collectTips} tips={tips}/>

                    <LessonTextControl textCollector={this.collectLessonTexts} lessonTexts={lessonTexts}/>

                    <SubmitButton
                        wrapperClass={"col-xs-12 no-padding margin-top-30"}
                        buttonClass={"btn btn-success move-right"}
                        buttonText={"Create lesson"}
                        dataCollector={this.submit}
                    />
               </div>
    }
}