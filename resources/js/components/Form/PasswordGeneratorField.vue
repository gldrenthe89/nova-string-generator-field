<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="flex">
        <input
            :id="field.attribute"
            :dusk="field.attribute"
            :type="passwordFieldType"
            v-model="value"
            class="w-full form-control form-input form-input-bordered"
            :class="errorClasses"
            :placeholder="field.name"
            autocomplete="new-password"
            :disabled="isReadonly"
        />
        <div class="my-auto ml-3 cursor-pointer" v-on:click="switchVisibility();">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" :id="field.attribute.concat('VisibilityButton')" fill="#bacad6"><path d="M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/></svg>
        </div>
        <input type="button" class="btn btn-default btn-primary ml-3 cursor-pointer" value="Generate" :id="field.attribute.concat('GenerateButton')" v-on:click="generatePassword();">
        <input type="button" class="btn btn-default btn-icon ml-3 cursor-pointer" value="Copy" :id="field.attribute.concat('CopyButton')" disabled="disabled" v-on:click="copyPassword();">
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [HandlesValidationErrors, FormField],

  data() {
    return {
      passwordFieldType: 'password'
    }
  },

  methods: {
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
          alert('Include at least one characters type! Symbols, Numbers, Lowercase, Uppercase');
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

      if (window.location.protocol === 'https:') {
        document.getElementById(this.field.attribute.concat('CopyButton')).disabled = false;
      }

      let button = document.getElementById(this.field.attribute.concat('GenerateButton'));
      button.value = 'Generated';
      setTimeout(function () {
        button.value = 'Regenerate'
      }, 1500);
    },
    copyPassword() {
      let fieldText = document.getElementById(this.field.attribute);
      let button = document.getElementById(this.field.attribute.concat('CopyButton'));
      if (fieldText.value.length > 0) {
        navigator.clipboard.writeText(fieldText.value);
        button.value = "Copied";
        setTimeout(function () {
          button.value = 'Copy'
        }, 1500);
      }
    },
    switchVisibility() {
      let button = document.getElementById(this.field.attribute.concat('VisibilityButton'));
      this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password';
      button.innerHTML = this.passwordFieldType === 'password'
          ? '<path d="M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>'
          : '<path d="M11.885 14.988l3.104-3.098.011.11c0 1.654-1.346 3-3 3l-.115-.012zm8.048-8.032l-3.274 3.268c.212.554.341 1.149.341 1.776 0 2.757-2.243 5-5 5-.631 0-1.229-.13-1.785-.344l-2.377 2.372c1.276.588 2.671.972 4.177.972 7.733 0 11.985-8.449 11.985-8.449s-1.415-2.478-4.067-4.595zm1.431-3.536l-18.619 18.58-1.382-1.422 3.455-3.447c-3.022-2.45-4.818-5.58-4.818-5.58s4.446-7.551 12.015-7.551c1.825 0 3.456.426 4.886 1.075l3.081-3.075 1.382 1.42zm-13.751 10.922l1.519-1.515c-.077-.264-.132-.538-.132-.827 0-1.654 1.346-3 3-3 .291 0 .567.055.833.134l1.518-1.515c-.704-.382-1.496-.619-2.351-.619-2.757 0-5 2.243-5 5 0 .852.235 1.641.613 2.342z"/>';
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value
    }
  }
}
</script>
