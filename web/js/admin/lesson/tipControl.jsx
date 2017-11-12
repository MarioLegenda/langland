import React from 'react';

import {RemovableInputText, Label} from './../source/fields.jsx';

export class TipControl extends React.Component {
    constructor(props) {
        super(props);

        this.addTip = this.addTip.bind(this);
        this.removeTip = this.removeTip.bind(this);
        this.dataCollector = this.dataCollector.bind(this);
        this._createTips = this._createTips.bind(this);

        this.state = {};

        this.state.tips = (props.tips.length > 0) ? this._createTips(props.tips) : [];
        this.state.tipValues = props.tips;
    }

    removeTip(controlKey) {
        this.setState(function(prevState) {
            prevState.tips.splice(controlKey, 1);
            prevState.tipValues.splice(controlKey, 1);

            this.props.tipCollector(prevState.tipValues);

            return prevState;
        });
    }

    dataCollector(key, value) {
        this.setState(function(prevState) {
            prevState.tipValues[key] = value;

            this.props.tipCollector(prevState.tipValues);

            return prevState;
        });
    }

    addTip() {
        this.setState(function(prevState) {
            const len = prevState.tips.length;

            return prevState.tips.push(<RemovableInputText
                key={len}
                controlKey={len}
                remove={this.removeTip}
                inputClass={"form-control tip"}
                buttonClass={'btn btn-sm btn-danger margin-top-20'}
                buttonText={"Remove tip"}
                dataCollector={this.dataCollector}
            />);
        });
    }

    _createTips(tips) {
        let tipObjects = [];
        for (let [key, tip] of tips) {
            tipObjects.push(<RemovableInputText
                key={key}
                controlKey={key}
                remove={this.removeTip}
                inputValue={tip}
                inputClass={"form-control tip"}
                buttonClass={'btn btn-sm btn-danger margin-top-20'}
                buttonText={"Remove tip"}
                dataCollector={this.dataCollector}
            />)
        }
    }

    render() {
        const tips = this.state.tips;

        return <div className="col-xs-12 no-padding margin-top-10">
            <div className={"col-xs-12 no-padding bottom-line margin-bottom-10"}>
                <Label labelText={"Tips: "}/>
            </div>

            {tips}

            <div className="col-xs-12 no-padding">
                <button className="btn btn-sm btn-info margin-top-20" onClick={this.addTip}>Add tip</button>
            </div>
        </div>
    }
}