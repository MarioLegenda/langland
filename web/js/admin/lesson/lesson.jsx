import React from 'react';

import {InputText, SubmitButton, Label, Notification} from './../source/fields.jsx';
import {TipControl} from "./tipControl.jsx";
import {InputTextarea} from "../source/fields.jsx";
import {LessonTextControl} from "./lessonTextControl.jsx";
import {LessonRepository} from "../source/repository/lessonRepository";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);

        this.formType = this._getPageType();

        this.lessonRepository = new LessonRepository();

        this.setName = this.setName.bind(this);
        this.collectTips = this.collectTips.bind(this);
        this.collectLessonTexts = this.collectLessonTexts.bind(this);
        this.setLearningOrder = this.setLearningOrder.bind(this);
        this.setDescription = this.setDescription.bind(this);
        this.submit = this.submit.bind(this);

        console.log(this._getPageType());
        this.state = {};

        this.state.model = {
            name: "",
            learningOrder: "",
            tips: [],
            lessonTexts: [],
            description: ""
        };

        this.state.form = {
            internalError: false,
            isValid: false,
            errors: [],
            success: false
        };

        this.lessonId = null;
        this.lessonUuid = null;
    }

    _getPageType() {
        const regex = /(create|edit)/;
        const match = regex.exec(location.pathname);
        const type = match[1];

        if (type === 'create' || type === 'edit') {
            return type;
        }

        throw new Error(`Lesson form type could not be determined. Expected \'create\' or \'edit\', got \'${type}\'`)
    }

    componentDidMount() {
        this.lessonRepository.getLessonById($.proxy(function(data) {
            const lesson = JSON.parse(data);

            this.lessonId = lesson.id;
            this.lessonUuid = lesson.uuid;

            this.setState({
                model: {
                    name: lesson.name,
                    description: lesson.description,
                    tips: lesson.json_lesson.tips,
                    learningOrder: lesson.learningOrder,
                    lessonTexts: lesson.json_lesson.lessonTexts
                }
            });
        }, this), $.proxy(function(xhr) {

        }, this));
    }

    setName(value) {
        this.setState((prevState) => {
            prevState.model.name = value;

            return prevState;
        });
    }

    setDescription(value) {
        this.setState((prevState) => {
            prevState.model.description = value;

            return prevState;
        });
    }

    collectTips(value) {
        this.setState((prevState) => {
            prevState.model.tips = value;

            return prevState;
        });
    }

    setLearningOrder(value) {
        this.setState((prevState) => {
            prevState.model.learningOrder = value;

            return prevState;
        });
    }

    collectLessonTexts(value) {
        this.setState((prevState) => {
            prevState.model.lessonTexts = value;

            return prevState;
        });
    }

    submit() {
        this.setState({
            form: {
                internalError: false,
                success: false
            }
        });

        const errors = this._validate();

        if (errors.length > 0) {
            this.setState((prevState) => {
                prevState.form.errors = errors;

                return prevState;
            });

            return;
        }

        const model = this._createModel();

        if (model.hasOwnProperty('id')) {
            this._update(model);
        } else if (!model.hasOwnProperty('id')) {
            this._create(model);
        }
    }

    _update(model) {
        this.lessonRepository.updateLesson(model, $.proxy(function() {
            this.setState((prevState) => {
                prevState.form = {
                    internalError: false,
                    isValid: false,
                    errors: [],
                    success: true
                };
            });

        }, this), $.proxy(function(xhr) {
            if (xhr.status === 500) {
                this.setState((prevState) => {
                    prevState.form.internalError = true;

                    return prevState;
                });
            }

            if (xhr.status === 400) {
                this.setState((prevState) => {
                    prevState.form = {
                        internalError: false,
                        isValid: false,
                        errors: [],
                        success: false
                    };

                    return prevState;
                });

                this.setState((prevState) => {
                    let errors = [];

                    for (let i = 0; i < xhr.responseJSON.length; i++) {
                        errors.push(<Notification key={i} className={'alert alert-danger'} message={xhr.responseJSON[i]} />)
                    }

                    return prevState.form.errors = errors;
                });
            }
        }, this));
    }

    _create(model) {
        this.lessonRepository.newLesson(model, $.proxy(function() {
            this.setState({
                form: {
                    internalError: false,
                    isValid: false,
                    errors: [],
                    success: true
                },
                model: {
                    name: "",
                    tips: [],
                    lessonTexts: [],
                    description: "",
                    learningOrder: "",
                }
            });
        }, this), $.proxy(function(xhr) {
            if (xhr.status === 500) {
                this.setState((prevState) => {
                    prevState.form.internalError = true;

                    return prevState;
                });
            }

            if (xhr.status === 400) {
                this.setState((prevState) => {
                    prevState.form = {
                        internalError: false,
                        isValid: false,
                        errors: [],
                        success: false
                    };

                    return prevState;
                });

                this.setState((prevState) => {
                    let errors = [];

                    for (let i = 0; i < xhr.responseJSON.length; i++) {
                        errors.push(<Notification key={i} className={'alert alert-danger'} message={xhr.responseJSON[i]} />)
                    }

                    prevState.form.errors = errors;

                    return prevState;
                });
            }
        }, this));
    }

    _validate() {
        let errors = [];
        const model = this.state.model;

        if (model.name.length === 0) {
            errors.push(<Notification key={1} className={'alert alert-danger'} message={'Name cannot be empty'} />)
        }

        if (Number.isInteger(parseInt(model.learningOrder)) === false) {
            errors.push(<Notification key={0} className={'alert alert-danger'} message={'Learning order cannot be empty and it has to be an integer'} />)
        }

        if (model.lessonTexts.length === 0) {
            errors.push(<Notification key={2} className={'alert alert-danger'} message={'There has to be at least one lesson text associated with this lesson'} />)
        }

        return errors;
    }

    _createModel()
    {
        const model = {
            name: this.state.model.name,
            tips: this.state.model.tips,
            learningOrder: this.state.model.learningOrder,
            lessonTexts: this.state.model.lessonTexts,
            description: this.state.model.description
        };

        if (this.lessonId !== null) {
            model.id = this.lessonId;
        }

        if (this.lessonUuid !== null) {
            model.lessonUuid = this.lessonUuid;
        }

        return model;
    }

    render() {
        const name = this.state.model.name;
        const learningOrder = this.state.model.learningOrder;
        const tips = this.state.model.tips;
        const lessonTexts = this.state.model.lessonTexts;
        const description = this.state.model.description;
        const internalError = this.state.form.internalError;
        const errors = this.state.form.errors;
        const success = this.state.form.success;
        const buttonText = (this.formType === 'create') ? 'Create lesson': 'Edit lesson';
        const actionText = (this.formType === 'create') ? 'Lesson created successfully': 'Lesson updated successfully';

        return <div>
                    {internalError === true &&
                        <Notification className={'alert alert-danger'} message={"An internal error occurred"} />
                    }

                    {success === true &&
                        <Notification className={'alert alert-success'} message={actionText} />
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

                    <div className={"margin-top-20"}>
                        <Label labelText={"Description:"}/>
                        <InputTextarea
                            labelText={"Description:"}
                            inputClass={"form-control"}
                            dataCollector={this.setDescription}
                            inputValue={description}
                        />
                    </div>

                    <div className={"margin-top-20"}>
                        <Label labelText={"Learning order:"}/>
                        <InputText
                            labelText={"Learning order:"}
                            inputClass={"form-control"}
                            dataCollector={this.setLearningOrder}
                            inputValue={learningOrder}
                        />
                    </div>

                    <SubmitButton
                        wrapperClass={"col-xs-12 no-padding margin-top-30"}
                        buttonClass={"btn btn-success move-right"}
                        buttonText={buttonText}
                        dataCollector={this.submit}
                    />
               </div>
    }
}