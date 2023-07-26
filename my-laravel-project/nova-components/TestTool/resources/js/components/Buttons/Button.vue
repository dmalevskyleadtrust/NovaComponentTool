<template>
    <button class="button" @click="click">
      <slot></slot>
    </button>
  </template>
  
  <script>
  export default {
    methods: {
      click(event) {
        console.log("Button Works");
      }
    }
  }
  </script>
  
  <style scoped>
  .button {
    margin-left: 8px;
    background-color: #4285f4;
    color: #fff;
    border: none;
    padding: 6px 12px;  /* Adjust padding to match the original style */
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .button:hover {
    background-color: #3367d6;
  }
  </style>
  