import React from 'react';

import {InputText, SubmitButton, Label} from './../source/fields.jsx';
import {TipControl} from "./tipControl.jsx";
import {LessonTextControl} from "./lessonTextControl.jsx";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);

        this.setName = this.setName.bind(this);
        this.collectTips = this.collectTips.bind(this);
        this.collectLessonTexts = this.collectLessonTexts.bind(this);
        this.submit = this.submit.bind(this);

        this.state = {};

        this.state.form = {
            name: "",
            tips: [],
            lessonTexts: []
        };
    }

    componentDidMount() {

    }

    setName(value) {
        this.setState(function(prevState) {
            prevState.form.name = value;
        });
    }

    collectTips(tips) {
        this.setState(function(prevState) {
            prevState.form.tips = tips;
        });
    }

    collectLessonTexts(texts) {
        this.setState(function(prevState) {
            prevState.form.lessonTexts = texts;
        });
    }

    submit() {

    }

    render() {
        const name = this.state.form.name;
        const tips = this.state.form.tips;
        const lessonTexts = this.state.form.lessonTexts;

        return <div>
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