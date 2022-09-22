// String Field...
import IndexStringField from './components/Index/StringGeneratorField'
import DetailStringField from './components/Detail/StringGeneratorField'
import FormStringField from './components/Form/StringGeneratorField'

// Password Field...
import IndexPasswordField from './components/Index/PasswordGeneratorField'
import DetailPasswordField from './components/Detail/PasswordGeneratorField'
import FormPasswordField from './components/Form/PasswordGeneratorField'

Nova.booting((app, store) => {
    // String Field...
    app.component('index-generate-string', IndexStringField)
    app.component('detail-generate-string', DetailStringField)
    app.component('form-generate-string', FormStringField)

    // Password Field...
    app.component('index-generate-password', IndexPasswordField)
    app.component('detail-generate-password', DetailPasswordField)
    app.component('form-generate-password', FormPasswordField)
})

