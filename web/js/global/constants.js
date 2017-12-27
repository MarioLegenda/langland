export const env = {
    current: getServerEnvironment()
};

export const user = {
    current: null
};

export const local_env = {
    current: 'dev'
};

export const global = {
    base_url: getBaseUrl() + env.current
};

function getBaseUrl() {
    const protocol = 'http://';
    const devDomain = '33.33.33.10';
    const prodDomain = '';

    switch (local_env.current) {
        case 'dev':
            return protocol + devDomain;
        case 'prod':
            return protocol + prodDomain;
    }
}

function getServerEnvironment() {
    const path = window.location.pathname;
    let env = '';

    if (/app_dev.php/.test(path)) {
        env = '/app_dev.php/';
    } else {
        env = '/';
    }

    return env;
}