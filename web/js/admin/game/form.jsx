import React from 'react';

class FieldTemplate extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            selectValue: this.props.value,
            value: this.props.value
        };

        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(e) {
        if (this.props.type === 'select') {
            this.props.handleChange(e);

            this.setState({
                selectValue: e.currentTarget.value
            });
        } else {
            this.props.handleChange(e);

            this.setState({
                value: e.currentTarget.value
            });
        }
    }

    render() {
        return (
            <div className="full-width align-left form-field field-break relative">
                <label>{this.props.labelName}</label>

                {this.props.type === 'text' &&
                <input value={this.state.value} type="text" name={this.props.name} onChange={this.handleChange}/>
                }

                {this.props.type === 'textarea' &&
                <textarea value={this.state.value} rows="6" cols="50" onChange={this.handleChange} name={this.props.name}></textarea>
                }

                {this.props.type === 'select' &&
                    <select value={this.state.selectValue} name={this.props.name} onChange={this.handleChange}>
                        <option value="defaultVal">Select option</option>
                        {this.props.options}
                    </select>
                }

                <div className="form-error full-width align-right">
                    {this.props.error}
                </div>

                <i className="description">
                    <span className="highlight">*</span> {this.props.description}
                </i>
            </div>
        )
    }
}

class Field extends React.Component {
    constructor(props) {
        super(props);

        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(e) {
        const name = e.currentTarget.getAttribute('name');
        const value = e.currentTarget.value;

        this.props.setField(name, value);
    }
}

export class TextField extends Field {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <FieldTemplate
                type="text"
                labelName={this.props.labelName}
                name={this.props.name}
                handleChange={this.handleChange}
                description={this.props.description}
                value={this.props.value}
            />
        )
    }
}

export class TextareaField extends Field {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <FieldTemplate
                type="textarea"
                labelName={this.props.labelName}
                name={this.props.name}
                handleChange={this.handleChange}
                description={this.props.description}
                value={this.props.value}
            />
        )
    }
}

export class CheckboxField extends Field {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <FieldTemplate
                type="checkbox"
                labelName={this.props.labelName}
                name={this.props.name}
                handleChange={this.handleChange}
                description={this.props.description}
                value={defaultValue}
                options={options}
            />
        )
    }
}

export class SelectField extends Field {
    constructor(props) {
        super(props);
    }

    render() {
        let options = this.props.options.map((item, index) =>
            <option key={index} value={item.value}>{item.label}</option>
        );

        const defaultValue = (this.props.value.length > 0) ? this.props.value : 'defaultVal';

        return (
            <FieldTemplate
                type="select"
                labelName={this.props.labelName}
                name={this.props.name}
                handleChange={this.handleChange}
                description={this.props.description}
                value={defaultValue}
                options={options}
            />
        )
    }
}

export class SubmitButton extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="button-wrapper align-right relative">
                <button onClick={this.props.onClick} type="submit" className="align-right">Save</button>
            </div>
        )
    }
}