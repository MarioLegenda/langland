export class Cache {
    constructor() {
        this.cache = {};
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