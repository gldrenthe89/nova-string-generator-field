Nova.booting((Vue, router) => {
    // String Field...
    Vue.component('index-generate-string', require('./components/Index/StringGeneratorField'));
    Vue.component('detail-generate-string', require('./components/Detail/StringGeneratorField'));
    Vue.component('form-generate-string', require('./components/Form/StringGeneratorField'));

    // Password Field...
    Vue.component('index-generate-password', require('./components/Index/PasswordGeneratorField'));
    Vue.component('detail-generate-password', require('./components/Detail/PasswordGeneratorField'));
    Vue.component('form-generate-password', require('./components/Form/PasswordGeneratorField'));
})



