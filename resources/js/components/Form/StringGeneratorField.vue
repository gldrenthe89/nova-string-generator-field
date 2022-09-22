<template>
    <DefaultField
        :field="currentField"
        :errors="errors"
        :show-help-text="showHelpText"
    >
        <template #field>
            <div class="flex">
                <input
                    v-bind="extraAttributes"
                    class="w-full form-control form-input form-input-bordered"
                    @input="handleChange"
                    :value="value"
                    :id="currentField.uniqueKey"
                    :dusk="field.attribute"
                    :disabled="currentlyIsReadonly"
                />

                <input v-if="!isReadonly" type="button" class="ml-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" v-bind:value="__('Generate')" :id="currentField.uniqueKey.concat('GenerateButton')" v-on:click="generateString();">
                <input type="button" class="ml-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900" v-bind:value="__('Copy')" :id="currentField.uniqueKey.concat('CopyButton')" :disabled="!copyEnabled()" v-on:click="copyString();">
            </div>
        </template>
    </DefaultField>
</template>


<script>
import {
    DependentFormField,
    HandlesValidationErrors,
} from 'laravel-nova'


export default {
    mixins: [DependentFormField, HandlesValidationErrors],

    computed: {
        defaultAttributes() {
            return {
                type: this.currentField.type || 'text',
                placeholder: this.currentField.placeholder || this.field.name,
                class: this.errorClasses,
                min: this.currentField.min,
                max: this.currentField.max,
                step: this.currentField.step,
                pattern: this.currentField.pattern,
            }
        },

        extraAttributes() {
            const attrs = this.currentField.extraAttributes

            return {
                // Leave the default attributes even though we can now specify
                // whatever attributes we like because the old number field still
                // uses the old field attributes
                ...this.defaultAttributes,
                ...attrs,
            }
        },
    },

  methods: {
    copyEnabled() {
      return window.location.protocol === 'https:' && this.value.length > 0;
    },
    generateString() {
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

      let string = "";
      let length = 10;
      if (this.field.length > 0){
        length = this.field.length;
      }
      for (let x = 0; x < length; x++) {
        const i = Math.floor(Math.random() * chars.length);
        string += chars.charAt(i);
      }
      this.value = string;

      let button = document.getElementById(this.currentField.uniqueKey.concat('GenerateButton'));
      button.value = this.__('Generated');
      setTimeout(() => {
        button.value = this.__('Regenerate');
        this.copyString()
      }, 750);
    },
    copyString() {
      let fieldText = document.getElementById(this.currentField.uniqueKey);
      let button = document.getElementById(this.currentField.uniqueKey.concat('CopyButton'));
        if (fieldText.value.length > 0) {
        navigator.clipboard.writeText(fieldText.value);
        button.value = this.__('Copied');
        setTimeout(() => {
          button.value = this.__('Copy');
        }, 750);
      }
    }
  }
}
</script>
