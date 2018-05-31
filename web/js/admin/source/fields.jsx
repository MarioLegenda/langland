import React from 'react';

export const ActionButton = (props) => {
    return <button className={props.class} onClick={props.action}>{props.buttonText}</button>;
};

export const Label = (props) => {
    return <label className={props.labelClass}>{props.labelText}</label>
};

export const SubmitButton = (props) => {
    return <div className={props.wrapperClass}>
        <button className={props.buttonClass} onClick={props.dataCollector}>{props.buttonText}</button>
    </div>
};

export const Notification = (props) => {
    return <div className={props.className}>
        <p>{props.message}</p>
    </div>
};

class BaseInput extends React.Component {
    constructor(props) {
        super(props);

        this.dataCollector = this.dataCollector.bind(this);

        this.state = {};
        this.state.value = props.inputValue;
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.inputValue.length === 0 && this.state.value !== nextProps.inputValue) {
            this.setState({
                value: ""
            });
        } else {
            this.setState({
                value: nextProps.inputValue
            });
        }
    }

    dataCollector(e) {
        const value = e.target.value;
        this.setState(() => {
            this.props.dataCollector(value);

            return { value: value };
        });
    }
}

export class InputText extends BaseInput {
    constructor(props) {
        super(props);
    }

    render() {
        return <input
            type="text"
            required="required"
            className={this.props.inputClass}
            onChange={this.dataCollector}
            value={this.state.value}
        />;
    }
}

export class InputTextarea extends BaseInput {
    constructor(props) {
        super(props);
    }

    render() {
        return <textarea
            type="text"
            required="required"
            className={this.props.inputClass}
            onChange={this.dataCollector}
            value={this.state.value}
        />
    }
}

export class RemovableInputText extends React.Component {
    constructor(props) {
        super(props);

        this.remove = this.remove.bind(this);
        this.dataCollector = this.dataCollector.bind(this);
    }

    remove() {
        this.props.remove(this.props.controlKey);
    }

    dataCollector(value) {
        this.props.dataCollector(this.props.controlKey, value);
    }

    render() {
        const inputClass = (this.props.hasOwnProperty('className')) ? this.props.className : 'form-control';
        const buttonText = (this.props.hasOwnProperty('buttonText')) ? this.props.buttonText : 'Remove';
        const buttonClass = (this.props.hasOwnProperty('buttonClass')) ? this.props.buttonClass : 'btn btn-sm';
        const inputValue = (this.props.hasOwnProperty('inputValue')) ? this.props.inputValue : '';

        return <div className="margin-top-20">
            <InputText
                inputClass={inputClass}
                dataCollector={this.dataCollector}
                inputValue={inputValue}
            />

            <ActionButton
                class={buttonClass}
                action={this.remove}
                buttonText={buttonText}
            />
        </div>
    }
}

export class RemovableTextarea extends React.Component {
    constructor(props) {
        super(props);

        this.remove = this.remove.bind(this);
        this.dataCollector = this.dataCollector.bind(this);
    }

    remove() {
        this.props.remove(this.props.controlKey);
    }

    dataCollector(value) {
        this.props.dataCollector(this.props.controlKey, value);
    }

    render() {
        const inputClass = (this.props.hasOwnProperty('className')) ? this.props.className : 'form-control';
        const buttonText = (this.props.hasOwnProperty('buttonText')) ? this.props.buttonText : 'Remove';
        const buttonClass = (this.props.hasOwnProperty('buttonClass')) ? this.props.buttonClass : 'btn btn-sm';
        const inputValue = (this.props.hasOwnProperty('inputValue')) ? this.props.inputValue : '';

        return <div className="margin-top-20">
            <InputTextarea
                inputClass={inputClass}
                dataCollector={this.dataCollector}
                inputValue={inputValue}
            />

            <ActionButton
                class={buttonClass}
                action={this.remove}
                buttonText={buttonText}
            />
        </div>
    }
}