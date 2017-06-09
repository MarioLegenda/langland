import React from 'react';

class AbstractRoute {
    constructor(props) {
        this.history = window.history;
    }
}

export class Router {
    constructor(props) {

    }
}

export class Route extends AbstractRoute {
    constructor(props) {
        super(props);
    }


}

export class Redirect {

}