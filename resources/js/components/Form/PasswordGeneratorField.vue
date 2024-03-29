<template>
    <DefaultField
        :field="currentField"
        :errors="errors"
        :show-help-text="showHelpText"
    >
        <template #field>
            <div class="flex">
                <input
                    :id="currentField.uniqueKey"
                    :dusk="field.attribute"
                    type="password"
                    v-model="value"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    autocomplete="new-password"
                    :disabled="currentlyIsReadonly"
                />

                <div class="ml-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" v-on:click="switchVisibility();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" :id="currentField.uniqueKey.concat('VisibilityButton')"><path d="M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/></svg>
                </div>

                <input v-if="!isReadonly" type="button" class="ml-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" v-bind:value="__('Generate')" :id="currentField.uniqueKey.concat('GenerateButton')" v-on:click="generatePassword();">
                <input type="button" class="ml-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" v-bind:value="__('Copy')" :id="currentField.uniqueKey.concat('CopyButton')" :disabled="!copyEnabled()" v-on:click="copyPassword();">
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [HandlesValidationErrors, DependentFormField],

    data() {
        return {
            passwordFieldType: 'password'
        }
    },

    methods: {
        copyEnabled() {
            return window.location.protocol === 'https:' && this.value.length > 0;
        },
        generatePassword() {
            let chars = '';
            if (this.field.exclude_rules && this.field.exclude_rules.length > 0){
                for (let j = 0; j < this.field.exclude_rules.length; j++){
                    this.field.exclude_rules[j] =  this.field.exclude_rules[j].toLowerCase();
                }
                if (this.field.exclude_rules.includes('symbols')
                    && this.field.exclude_rules.includes('numbers')
                    && this.field.exclude_rules.includes('uppercase')
                    && this.field.exclude_rules.includes('lowercase')){
                    alert(this.__('Include at least one characters type! Symbols, Numbers, Lowercase, Uppercase'));
                    return false;
                }

                if (!this.field.exclude_rules.includes('symbols')){
                    chars += '!@#$%^&*()-+<>'
                }
                if (!this.field.exclude_rules.includes('numbers')){
                    chars += '1234567890'
                }
                if (!this.field.exclude_rules.includes('uppercase')){
                    chars += 'ABCDEFGHIJKLMNOP'
                }
                if (!this.field.exclude_rules.includes('lowercase')){
                    chars += 'abcdefghijklmnopqrstuvwxyz'
                }
            } else{
                chars = 'abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>1234567890ABCDEFGHIJKLMNOP';
            }

            let pass = "";
            let length = 10;
            if (this.field.length > 0){
                length = this.field.length;
            }
            for (let x = 0; x < length; x++) {
                const i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            this.value = pass;

            let button = document.getElementById(this.currentField.uniqueKey.concat('GenerateButton'));
            button.value = this.__('Generated');
            setTimeout(() => {
                button.value = this.__('Regenerate');
                this.copyPassword()
            }, 750);
        },
        copyPassword() {
            let fieldText = document.getElementById(this.currentField.uniqueKey);
            let button = document.getElementById(this.currentField.uniqueKey.concat('CopyButton'));
            if (fieldText.value.length > 0) {
                navigator.clipboard.writeText(fieldText.value);
                button.value = this.__('Copied');
                setTimeout(() => {
                    button.value = this.__('Copy');
                }, 750);
            }
        },
        switchVisibility() {
            let field = document.getElementById(this.currentField.uniqueKey);
            let button = document.getElementById(this.currentField.uniqueKey.concat('VisibilityButton'));
            this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
            field.type = this.passwordFieldType;
            button.innerHTML = this.passwordFieldType === 'password'
                ? '<path d="M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>'
                : '<path d="M11.885 14.988l3.104-3.098.011.11c0 1.654-1.346 3-3 3l-.115-.012zm8.048-8.032l-3.274 3.268c.212.554.341 1.149.341 1.776 0 2.757-2.243 5-5 5-.631 0-1.229-.13-1.785-.344l-2.377 2.372c1.276.588 2.671.972 4.177.972 7.733 0 11.985-8.449 11.985-8.449s-1.415-2.478-4.067-4.595zm1.431-3.536l-18.619 18.58-1.382-1.422 3.455-3.447c-3.022-2.45-4.818-5.58-4.818-5.58s4.446-7.551 12.015-7.551c1.825 0 3.456.426 4.886 1.075l3.081-3.075 1.382 1.42zm-13.751 10.922l1.519-1.515c-.077-.264-.132-.538-.132-.827 0-1.654 1.346-3 3-3 .291 0 .567.055.833.134l1.518-1.515c-.704-.382-1.496-.619-2.351-.619-2.757 0-5 2.243-5 5 0 .852.235 1.641.613 2.342z"/>';
        }
    }
}
</script>
