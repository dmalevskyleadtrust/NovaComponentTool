<template>
    <input
      class="input"
      :value="value"
      @input="updateValue"
      :placeholder="placeholder"
    />
  </template>
  
  <script>
  export default {
    props: {
      value: {
        type: String,
        required: true,
      },
      placeholder: {
        type: String,
        default: '',
      },
    },
    methods: {
      updateValue(event) {
        this.$emit('input', event.target.value);
      },
    },
  };
  </script>
  
  <style scoped>
  .input {
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 8px;
  }
  
  .input::placeholder {
    color: #999;
  }
  
  .input:focus {
    outline: none;
    border-color: #4285f4;
  }
  </style>
  