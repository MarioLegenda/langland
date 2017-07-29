import React from 'react';

import { Form, TextField, TextareaField, SubmitField, SelectField, CheckboxField } from 'react-components-form';
import Schema from 'form-schema-validation';

function QuestionField (props) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <TextareaField name="question" label="Game name:" type="text" errorStyles={props.errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game name. It has to be unique
            </i>
        </div>
    )
}

function TipField (props) {
    return (
        <div className="full-width align-left form-field field-break relative">
            <TextField name="tip" label="Game name:" type="text" errorStyles={props.errorStyles}/>

            <i className="description margin-top-10">
                <span className="highlight">*</span> game name. It has to be unique
            </i>
        </div>
    )
}

class GameForm extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div>
                <Form
                    schema={this.props.schema}
                    onSubmit={this.props.onNext}
                >

                    <QuestionField/>
                    <TipField/>

                </Form>
            </div>
        )
    }
}

class TraversalComponent extends React.Component {
    constructor(props) {
        super(props);

        this.help = {
            errorMessages: {
                validateRequired(key) { return `This field is required`; },
                validateString(key) { return `This field has to be a string`; }
            },
            errorStyles: {
                className: 'form-error'
            },
            schema: null
        };

        this.schema = {
            question: {
                type: String,
                required: true
            },
            tip: {
                type: String,
                required: true
            }
        };

        this.onNext = this.onNext.bind(this);
        this.onPrev = this.onPrev.bind(this);
        this.newGame = this.newGame.bind(this);
    }

    onNext() {

    }

    onPrev() {

    }

    newGame() {

    }

    createSchema() {
        return new Schema(this.schema, this.help.errorMessages);
    }

    render() {
        return (
            <GameForm schema={this.createSchema()} onNext={this.onNext} onPrev={this.onPrev} newGame={this.newGame} />
        )
    }
}

export class GameDataContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div>
                <TraversalComponent/>
            </div>
        )
    }
}