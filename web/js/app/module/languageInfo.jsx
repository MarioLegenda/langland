import React from 'react';
import {routes} from './routes.js';

class LanguageInfo extends React.Component {
    constructor(props) {
        super(props);

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }

    next() {
        this.props.nextItem();
    }

    prev() {
        this.props.prevItem();
    }

    render() {
        if (typeof this.props.item === 'undefined') {
            return null;
        }

        const name = this.props.item.name;
        const text = this.props.item.text;

        return (
            <div className="animated fadeInDown language-info">
                <h1 className="animated animated-field margin-bottom-20">{name}</h1>

                <p className="animated animated-field margin-bottom-20">
                    {text}
                </p>
                <div className="full-width align-left margin-bottom-20">
                    {this.props.firstItem !== true && <a onClick={this.prev}><i className="fa fa-arrow-left previous-button"></i></a>}
                    <a onClick={this.next}><i className="fa fa-thumbs-o-up fa-2x"></i></a>
                </div>
            </div>
        )
    }
}

export class LanguageInfoContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            texts: null,
            counter: 0
        };

        this.inNextClick = false;
        this.inPrevClick = false;

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }

    _fetchLanguageInfos() {
        this.props.DataSource.fetchLanguaageInfos(this.props.languageData.id)
            .done(jQuery.proxy(function(data) {
                this.setState({
                    texts: data.data.languageInfoTexts
                });
            }, this));
    }

    _moveSlide(clickType) {
        if (this.state.counter === this.state.texts.length - 1) {
            this.props.markInfoLooked();
        }

        this[clickType] = true;

        const infoElem = jQuery('.language-info');

        infoElem.removeClass('fadeInDown').addClass('fadeOutUp');

        infoElem.on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', jQuery.proxy(function(event) {
            if (event.originalEvent.animationName === 'fadeOutUp' && this[clickType] === true) {
                switch(clickType) {
                    case 'onNextClick':
                        this.setState((prevState) => ({
                            counter: ++prevState.counter
                        }));

                        break;
                    case 'onPrevClick':
                        this.setState((prevState) => ({
                            counter: --prevState.counter
                        }));

                        break;
                }

                jQuery('.language-info').removeClass('fadeOutUp').addClass('fadeInDown');

                this[clickType] = false;
            }
        }, this));
    }

    next() {
        this._moveSlide('onNextClick');
    }

    prev() {
        this._moveSlide('onPrevClick');
    }

    componentDidMount() {
        this._fetchLanguageInfos();
    }

    render() {
        if (this.state.texts === null) {
            return null;
        }

        const counter = this.state.counter;
        const item = this.state.texts[counter];
        const firstItem = counter === 0;

        return (
            <div className="component">
                <LanguageInfo
                    item={item}
                    nextItem={this.next}
                    prevItem={this.prev}
                    firstItem={firstItem}
                />
            </div>
        )
    }
}