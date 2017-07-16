import React from 'react';

import { Form, TextField, TextareaField, SubmitField, SelectField, CheckboxField } from 'react-components-form';
import Schema from 'form-schema-validation';

import {url} from './../url.js';

function NameField (errorStyles) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <TextField name="name" label="Game name:" type="text" errorStyles={errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game name. It has to be unique
            </i>
        </div>
    )
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
            lesson: {
                type: String,
                required: true,
                validators: [this.help.validators.isValidLesson()]
            },
            maxTime: {
                type: String
            }
        };

        this.onSubmit = this.onSubmit.bind(this);

        this._fetchLessons();
    }

    onSubmit() {

    }

    _fetchLessons() {
        this.props.dataSource.fetchLessons(url)
            .done(jQuery.proxy(function(data, content, response) {
                console.log('lesson fetch success', response.status);
            }, this));
    }

    render() {
        return (
            <div className="full-width align-left margin-top-30 page-content form">
                <div className="margin-top-40 align-left full-width">

                    <div className="full-width align-left">
                    </div>

                    <Form
                        schema={this.data.schema}
                        onSubmit={this.onSubmit}
                    >

                        <NameField errorStyles={this.help.errorStyles}/>

                    </Form>
                </div>
            </div>
        )
    }
}