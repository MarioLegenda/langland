import {
    store
} from "../events/events";

export class Cache {
    constructor() {
        this.cache = {};

        this._eventListener();
    }

    _eventListener() {
        const cacheEvents = store.getState().cacheInvalidation;

        if (this.has(cacheEvents['cacheName'])) {
            this.remove(cacheEvents['cacheName']);
        }
    }

    has(key) {
        return this.cache.hasOwnProperty(key);
    }

    get(key) {
        if (this.has(key)) {
            return this.cache[key];
        }

        return null;
    }

    add(key, value) {
        this.cache[key] = value;
    }

    remove(key) {
        if (!this.has(key)) {
            throw new Error('Cannot remove cache key. Key ' + key + ' does not exist');
        }

        delete this.cache[key];
    }

    clear() {
        this.cache = {};
    }
}
