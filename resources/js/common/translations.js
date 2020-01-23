const translations = {

    methods: {
        /**
         * Translate a given key and replace text placeholders, if any
         * @param key
         * @param replace
         */
        __(key, replace = {}) {
            let language = document.documentElement.lang
            let translation = window.translations[key]

            if (!translation) {

                // Console log the missing translation key, only when running in debug mode... magic
                this.$console(`%c[Translation] %cValue for key '${key}' is missing for language '${language}'`, 'color: red; font-weight: bold', 'color: orangered')

                // Just return the key name if the corresponding value doesn't exist
                return '__' + key + '__'
            }

            // Return the translation value if there are no placeholders to replace
            if (_.isEmpty(replace)) return translation

            // Replace placeholder values with given data
            _.forEach(replace, (value, key) => translation = translation.replace(':' + key, value))

            return translation
        }
    }
}

export default translations
