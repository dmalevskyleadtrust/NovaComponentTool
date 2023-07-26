<template>
    <span class="label">
      <input :value="value" @input="updateValue" />
    </span>
  </template>
  
  <script>
  export default {
    props: ['value'],
    methods: {
      updateValue(event) {
        this.$emit('input', event.target.value);
      }
    }
  }
  </script>
  
  <style scoped>
  .label {
    font-weight: bold;
    margin-right: 8px; /* Add right margin to match the original style */
    color: #000; /* Add color to match the original style */
  }
  </style>
  