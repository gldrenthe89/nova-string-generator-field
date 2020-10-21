Nova.booting((Vue, router) => {
    Vue.component('index-generate-string', require('./components/IndexField'));
    Vue.component('detail-generate-string', require('./components/DetailField'));
    Vue.component('form-generate-string', require('./components/FormField'));
})
