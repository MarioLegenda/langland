import React from 'react';

import { Form, TextField, TextareaField, SubmitField, SelectField, CheckboxField } from 'react-components-form';
import Schema from 'form-schema-validation';

import {url} from './../url.js';

function NameField (props) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <TextField name="name" label="Game name:" type="text" errorStyles={props.errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game name. It has to be unique
            </i>
        </div>
    )
}

function DescriptionField(props) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <TextareaField name="description" label="Game description: " errorStyles={props.errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game description. Describe what benefits this game will have for the user. This description will show on frontned
            </i>
        </div>
    )
}

function LessonSelect(props) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <SelectField name="lessons" options={props.options} label="Select a lesson: " errorStyles={props.errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game lesson. Choose which lesson will be associated to this game
            </i>
        </div>
    );
}

export class GameCreateInit extends React.Component {
    constructor(props) {
        super(props);

        this.help = {
            errorMessages: {
                validateRequired(key) { return `This field is required`; },
                validateString(key) { return `This field has to be a string`; }
            },
            validators: {
                isValidLesson: () => ({
                    validator: (value) => {
                        return value !== 'default';
                    },
                    errorMessage: 'Please, select a lesson'
                })
            },
            errorStyles: {
                className: 'form-error'
            },
            schema: null,
            lessonsLoaded: false,
            lessons: []
        };

        this.state = {
            lessonsLoaded: false,
            lessons: [{label: 'Select lesson', value: 'default'}]
        };

        this.schema = {
            name: {
                type: String,
                required: true
            },
            description: {
                type: String,
                required: true
            },
            lessons: {
                type: String,
                required: true,
                validators: [this.help.validators.isValidLesson()]
            }
        };

        this.onSubmit = this.onSubmit.bind(this);
    }

    componentWillMount() {
        this._fetchLessons();

        this.help.schema = new Schema(this.schema, this.help.errorMessages);
    }

    onSubmit() {

    }

    _fetchLessons() {
        this.props.dataSource.fetchAutocompleteLessons(url)
            .done(jQuery.proxy(function(data, content, response) {
                if (response.status === 200) {
                    let copiedData = [{label: 'Select lesson', value: 'default'}];

                    for (let i = 0; i < data.length; i++) {
                        copiedData.push(data[i]);
                    }

                    this.setState({
                        lessonsLoaded: true,
                        lessons: copiedData
                    });
                }
            }, this));
    }

    render() {
        if (this.state.lessonsLoaded === false) {
            return null;
        }

        return (
            <div className="full-width align-left margin-top-30 page-content form">
                <div className="margin-top-40 align-left full-width">

                    <div className="full-width align-left">
                    </div>

                    <Form
                        schema={this.help.schema}
                        onSubmit={this.onSubmit}
                    >

                        <NameField errorStyles={this.help.errorStyles}/>
                        <DescriptionField errorStyles={this.help.errorStyles}/>
                        <LessonSelect errorStyles={this.help.errorStyles} options={this.state.lessons}/>

                        <div className="button-wrapper align-right relative">
                            <SubmitField value="Submit" />
                        </div>

                    </Form>
                </div>
            </div>
        )
    }
}