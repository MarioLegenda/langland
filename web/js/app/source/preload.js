class Preload {
    loadImages(images) {
        for (let i = 0; i < images.length; i++) {
            images[i] = new Image();
            images[i].src = images[i];
        }
    }
}

export function factory() {
    return factory = {
        preload: function() {
            return new Preload();
        }
    };
}