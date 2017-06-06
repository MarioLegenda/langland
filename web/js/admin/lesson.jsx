import React from 'react';
import {envr} from './env.js';
import Select from 'react-select';

class GenericSelect extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            originalName: null,
            fullPath: null
        };

        this.onChange = this.onChange.bind(this);
    }

    onChange(selectedValue) {
        const selectedItem = this.props.items[selectedValue.value];

        this.setState({
            originalName: selectedItem.originalName,
            fullPath: selectedItem.fullPath
        });
    }

    render() {
        if (this.props.items === null) {
            return null;
        }

        const items = this.props.items.map(function(item, index) {
            return {
                label: item.originalName,
                value: index
            };
        });

        const originalName = this.state.originalName;
        const fullPath = this.state.fullPath;

        return (
            <div className="full-width margin-top-20">
                <Select
                    options={items}
                    onChange={this.onChange}
                />

                <p className="full-width margin-top-20 margin-bottom-20">{originalName}</p>
                <p className="full-width margin-bottom-20">{fullPath}</p>
            </div>
        )
    }
}

class GenericSelectContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: null
        };

        this.soundsFound = false;
        this.imagesFound = false;

        this.findImages = this.findImages.bind(this);
        this.findSounds = this.findSounds.bind(this);
    }

    findSounds(e) {
        e.preventDefault();

        if (this.soundsFound === true) {
            return;
        }

        this.soundsFound = true;

        jQuery.ajax({
            url: envr + 'admin/sound/find-sounds',
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            this.setState({
                items: data.data
            });
        }, this));
    }

    findImages(e) {
        e.preventDefault();

        if (this.imagesFound === true) {
            return;
        }

        this.imagesFound = true;
    }

    render() {
        const items = this.state.items;

        return (
            <div>
                <div className="align-left relative margin-top-50 relative button-wrapper relative">
                    <button className="align-right" onClick={this.findSounds}>Find sound</button>
                </div>

                <div className="align-left relative margin-top-50 relative button-wrapper relative">
                    <button className="align-right" onClick={this.findImages}>Find image</button>
                </div>

                <div className="full-width align-left">
                    <GenericSelect items={items}/>
                </div>
            </div>
        )
    }
}

export function LessonApp() {
    return <GenericSelectContainer/>
}