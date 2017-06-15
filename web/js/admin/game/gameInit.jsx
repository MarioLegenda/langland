import React from 'react';
//import {Form} from './gameForm.jsx';
import { Form, TextField, TextareaField, SubmitField, SelectField, CheckboxField } from 'react-components-form';
import Schema from 'form-schema-validation';
import {UnitContainer} from './unitContainer.jsx';

import {envr} from './../env.js';
import {url} from './../url.js';

export class GameInit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            errors: []
        };

        this.serverData = {
            name: '',
            description: '',
            lesson: '',
            words: []
        };

        this.gameSaving = false;

        this.data = {
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
            lessons: [
                {label: 'Select lesson', value:'default'},
                {label: 'Lesson 1', value: 1},
                {label: 'Lesson 2', value: 2},
                {label: 'Lesson 3', value: 3}
            ],
            words: []
        };

        this.data.schema = new Schema({
            name: {
                type: String,
                required: true
            },
            description: {
                type: String,
                required: true
            },
            lesson: {
                type: String,
                required: true,
                validators: [this.data.validators.isValidLesson()]
            },
            imageMaster: {
                type: Boolean,
            },
            timeTrial: {
                type: Boolean,
            },
            freestyle: {
                type: Boolean,
            }
        }, this.data.errorMessages);

        this.setField = this.setField.bind(this);
        this.onSubmit = this.onSubmit.bind(this);
    }

    _saveGame() {
        this.gameSaving = true;
        const serverData = this.serverData;

        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/create-game',
            method: 'POST',
            data: {
                game: serverData
            }
        }).done(jQuery.proxy(function(data) {
            if (data.status === 'success') {
                window.location.href = envr + 'admin/course/manage/' + url.getParsed()[3] + '/game';
            }

            if (data.status === 'error') {
                this.setState({
                    errors: data.data
                });

                this.gameSaving = false;

                $("html, body").animate({ scrollTop: "0px" });
            }
        }, this));
    }

    setField(name, value) {
        this.serverData.words = value;
    }

    onSubmit(data) {

        this.serverData.name = data.name;
        this.serverData.description = data.description;
        this.serverData.lesson = data.lesson;

        if (this.gameSaving === false) {
            this._saveGame();
        }
    }

    render() {
        const errors = this.state.errors.map((item, index) =>
            <p key={index} className="error">* {item}</p>
        );

        return (
            <div className="full-width align-left margin-top-30 page-content form">
                <div className="margin-top-40 align-left full-width">

                    <div className="full-width align-left">
                        {errors}
                    </div>

                    <Form
                        schema={this.data.schema}
                        onSubmit={this.onSubmit}
                    >

                        <div className="full-width align-left form-field field-break relative">
                            <TextField name="name" label="Game name:" type="text" errorStyles={this.data.errorStyles}/>

                            <i className="description margin-top-10">
                                <span className="highlight">*</span> game name. It has to be unique
                            </i>
                        </div>

                        <div className="full-width align-left form-field field-break relative">
                            <TextareaField name="description" label="Game description: " errorStyles={this.data.errorStyles}/>

                            <i className="description margin-top-10">
                                <span className="highlight">*</span> game description. Describe what benefits this game will have for the user. This description will show on frontned
                            </i>
                        </div>

                        <div className="full-width align-left form-field field-break relative">
                            <SelectField name="lesson" options={this.data.lessons} label="Select a lesson: " errorStyles={this.data.errorStyles}/>

                            <i className="description margin-top-10">
                                <span className="highlight">*</span> game description. Describe what benefits this game will have for the user. This description will show on frontned
                            </i>
                        </div>

                        <div className="full-width align-left form-field field-break relative">
                            <label>Game type</label>

                            <div className="horizontal-checkbox">
                                <p>Image master</p>
                                <CheckboxField name="imageMaster"/>
                            </div>

                            <div className="horizontal-checkbox">
                                <p>Time trial</p>
                                <CheckboxField name="timeTrial"/>
                            </div>

                            <div className="horizontal-checkbox">
                                <p>Freestyle</p>
                                <CheckboxField name="freestyle"/>
                            </div>

                            <i className="description margin-top-10">
                                <span className="highlight">*</span> game description. Describe what benefits this game will have for the user. This description will show on frontned
                            </i>
                        </div>

                        <UnitContainer
                            setField={this.setField}
                            items={this.data.words}
                        />

                        <div className="button-wrapper align-right relative">
                            <SubmitField value="Submit" />
                        </div>
                    </Form>
                </div>
            </div>
        )
    }
}