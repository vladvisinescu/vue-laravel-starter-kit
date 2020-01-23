import axios from 'axios'
import store from '../../store'

const getClient = ({ baseURL = null, baseVersion }) => {

    const options = {
        baseURL: baseURL
    }

    if (store.getters['auth/isAuthenticated']) {
        options.headers = {
            Authorization: `Bearer ${store.getters['auth/accessToken']}`,
            'Accept-Version': baseVersion ? baseVersion : process.env.MIX_API_VERSION
        };
    }

    const client = axios.create(options)

    return client
}

class BaseApiClient {

    /**
     * Construct and supply a baseURL (version number?) if any
     *
     * @param baseURL
     */
    constructor(baseURL, baseVersion) {
        this.client = getClient({baseURL, baseVersion})
    }

    post(path, params, options = {}) {
        return new Promise((resolve, reject) => {
            this.client.post(path, params, options).then(response => resolve(response)).catch(error => reject(error))
        })
    }

    get(path, params, options = {}) {
        return new Promise((resolve, reject) => {
            this.client.get(path).then(response => resolve(response)).catch(error => reject(error))
        })
    }
}

export default BaseApiClient
