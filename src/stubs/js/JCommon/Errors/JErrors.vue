<template>
    <div class="row">
        <div class="col">
            <div v-if="submitting" class="card alert-info">
                <div class="card-body">
                    Submitting...
                </div>
            </div>
            <div v-if="!submitting && Object.keys(err).length !== 0" class="card alert-danger">
                <div class="card-body">
                    <ul>
                        <li v-for="message in err" :key="message" class="">
                            {{ message }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'

    export default {
        name: "JErrors",
        props: {
            submitting: {
                type: Boolean,
                default: false
            },
            errors: {
                default: () => {
                    return {}
                }
            }
        },
        data() {
            return {}
        },
        computed: {
            err() {
                return this.getErrors(this.errors)
            }
        },
        methods: {
            getErrors(e) {
                if (e.response && e.response.status !== 422) {
                    switch(e.response.status) {
                        case 400:
                            return [ [e.response.errorMsg] ];
                        case 401:
                            return ['You are not authenticated'];
                        case 403:
                            return ['You are not authorized to perform this action'];
                        case 419:
                            return ['This page has expired. Please refresh.'];
                        case 413:
                            return ['File too large'];
                        case 404:
                            return ['Not found'];
                        case 405:
                            return ['That action is not permitted'];
                        case 500:
                            return ['Server error'];
                        default:
                            return [`Error ${e.response.status} has occurred.`]
                    }
                }

                const validationErrors = _.get(e, ['response', 'data', 'errors'])

                if (validationErrors) {
                    return Array.isArray(validationErrors) ? validationErrors : Object.values(validationErrors).flat()
                }
                return []
            }
        }
    }
</script>
