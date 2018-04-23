import React from "react";
import {Util} from "./util";
const pathToRegexp = require('path-to-regexp');

class RouteConfig {
    constructor(props) {
        this._validate(props);

        this.name = props.config.name;
        this.component = props.config.component;
        this.path = props.config.path;
    }

    _validate(props) {
        if (!props.hasOwnProperty('config')) {
            throw new Error('\'config\' not found for route definitions');
        }

        const config = props.config;

        if (!config.hasOwnProperty('name')) {
            throw new Error('\'name\' is not present in route config');
        }

        if (!Util.isString(config.name)) {
            throw new Error('\'name\' is not a string in route config');
        }

        if (!config.hasOwnProperty('component')) {
            throw new Error('\'component\' is not present in route config');
        }

        if (!Util.isObject(config.component)) {
            throw new Error('\'component\' has to be an object');
        }

        if (!config.hasOwnProperty('path')) {
            throw new Error('\'path\' is not present in route config');
        }

        if (!Util.isString(config.path)) {
            throw new Error('\'path\' has to be a string');
        }
    }
}

class Base extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        // this code should never be called
        return <div></div>
    }
}

Base.routes = [];

class LinkConfig {
    constructor(props) {
        this._validate(props);

        this.to = props.to;
    }

    _validate(props) {
        if (!props.hasOwnProperty('to')) {
            throw new Error('\'to\' has to be an array of route names');
        }

        if (!Util.isArray(props.to)) {
            throw new Error('\'to\' has to be an array of route names');
        }
    }
}

export class Link extends Base {
    constructor(props) {
        super(props);

        this.config = new LinkConfig(props);
    }

    render() {
        const text = (this.config.hasOwnProperty('text')) ? this.config.text : '';

        return <a {this.config.to}>{text}</a>
    }
}

export class Route extends Base {
    constructor(props = {}) {
        super(props);

        this.config = new RouteConfig(this.props);
    }

    _matchRegex() {
        const re = pathToRegexp(this.config.path);

        return re.exec(location.pathname);
    }

    render() {
        const match = this._matchRegex();

        if (match !== null) {
            if (Util.isFunction(this.config.component)) {
                return this.config.component(match);
            }

            return React.createElement(this.config.component, match);
        }

        return <div></div>
    }
}