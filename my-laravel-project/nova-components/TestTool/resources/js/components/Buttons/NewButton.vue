<template>
    <button class="new-button" @click="handleClick">
      <slot></slot>
    </button>
  </template>
  
  <script>
  export default {
    methods: {
      handleClick(event) {
        console.log("NewButton clicked");
        this.$emit('click', event);
      }
    }
  }
  </script>
  
  <style scoped>
  .new-button {
    margin-left: 8px;
    background-color: #4285f4;
    color: #fff;
    border: none;
    padding: 6px 12px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .new-button:hover {
    background-color: #3367d6;
  }
  </style>
  