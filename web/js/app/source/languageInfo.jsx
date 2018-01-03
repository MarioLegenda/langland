import React from 'react';
import {factory} from "./repository/factory.js";

class Item extends React.Component {
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
            <div className="animated fadeInDown language-info-item">
                <h1 className="animated animated-field">{name}</h1>

                <p className="animated animated-field text">
                    {text}
                </p>

                <div className="button-wrapper">
                    {this.props.firstItem !== true && <a onClick={this.prev} className="previous-button"><i className="fa fa-arrow-left"></i></a>}

                    <a onClick={this.next}><i className="fa fa-thumbs-o-up fa-2x"></i></a>
                </div>
            </div>
        )
    }
}

export class LanguageInfo extends React.Component {
    constructor(props) {
        super(props);

        this.languageRepository = factory('language');
        this.learningUserRepository = factory('learning-user');
        this.languageId = this.props.languageId;

        this.state = {
            texts: null,
            counter: 0
        };

        this.inNextClick = false;
        this.inPrevClick = false;

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }

    _fetchLanguageInfo() {
        this.languageRepository.getLanguageInfo(this.languageId, $.proxy(function(data) {
            this.setState(function(prevState) {
                prevState.texts = data.texts;
            });
        }, this));
    }

    _markLanguageInfoLooked() {
        this.learningUserRepository.markLanguageInfoLooked();
    }

    _moveSlide(clickType) {
        if (this.state.counter === this.state.texts.length - 1) {
            this._markLanguageInfoLooked();
        }

        this[clickType] = true;

        const infoElem = jQuery('.language-info-item');

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

                jQuery('.language-info-item').removeClass('fadeOutUp').addClass('fadeInDown');

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
        this._fetchLanguageInfo();
    }

    render() {
        if (this.state.texts === null) {
            return null;
        }

        const counter = this.state.counter;
        const item = this.state.texts[counter];
        const firstItem = counter === 0;

        return (
            <div className="language-info-wrapper">
                <Item
                    item={item}
                    nextItem={this.next}
                    prevItem={this.prev}
                    firstItem={firstItem}
                />
            </div>
        )
    }
}