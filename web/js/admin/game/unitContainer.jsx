import React from 'react';
import {WordUnitContainer} from './wordUnit.jsx';

export class UnitContainer extends React.Component {
    constructor(props) {
        super(props);

        this.setField = this.setField.bind(this);
    }

    setField(name, value) {
        this.props.setField(name, value);
    }

    render() {
        return (
            <div>
                <div className="full-width align-left form-field relative">
                    <WordUnitContainer
                        setField={this.setField}
                    />
                </div>
            </div>
        )
    }
}