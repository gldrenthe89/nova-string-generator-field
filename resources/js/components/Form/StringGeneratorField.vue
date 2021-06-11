<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="flex">
        <input
            class="w-full form-control form-input form-input-bordered"
            @input="handleChange"
            :value="value"
            :id="field.attribute"
            :dusk="field.attribute"
            v-bind="extraAttributes"
            :disabled="isReadonly"
            :list="`${field.attribute}-list`"
        />

        <datalist
            v-if="field.suggestions && field.suggestions.length > 0"
            :id="`${field.attribute}-list`"
        >
          <option
              :key="suggestion"
              v-for="suggestion in field.suggestions"
              :value="suggestion"
          />
        </datalist>
        <input type="button" class="btn btn-default btn-primary ml-3 cursor-pointer" v-bind:value="__('Generate')" :id="field.attribute.concat('GenerateButton')" v-on:click="generateString();">
        <input type="button" class="btn btn-default btn-icon ml-3 cursor-pointer" v-bind:value="__('Copy')" :id="field.attribute.concat('CopyButton')" disabled="disabled" v-on:click="copyString();">
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [HandlesValidationErrors, FormField],

  computed: {
    defaultAttributes() {
      return {
        type: this.field.type || 'text',
        min: this.field.min,
        max: this.field.max,
        step: this.field.step,
        pattern: this.field.pattern,
        placeholder: this.field.placeholder || this.field.name,
        class: this.errorClasses,
      }
    },

    extraAttributes() {
      const attrs = this.field.extraAttributes

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

      if (window.location.protocol === 'https:') {
        document.getElementById(this.field.attribute.concat('CopyButton')).disabled = false;
      }

      let button = document.getElementById(this.field.attribute.concat('GenerateButton'));
      button.value = this.__('Generated');
      setTimeout(() => {
        button.value = this.__('Regenerate');
      }, 750);
    },
    copyString() {
      let fieldText = document.getElementById(this.field.attribute);
      let button = document.getElementById(this.field.attribute.concat('CopyButton'));
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
