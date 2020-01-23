const debug = {

    methods: {

        /**
         * Console Logging only if in development
         */
        $console() {
            if (process.env.MIX_APP_DEBUG) {
                console.warn(...arguments)
            }
        }
    }
}

export default debug
