<template>
    <div>
      <div v-for="(state, index) in states" :key="index" class="state-row">
        <input v-model="state.label" type="text" placeholder="State label" />
        <button @click="addSubgroup(index)">Add Bundle</button>
        <div v-for="(subgroup, subIndex) in state.subgroups" :key="subIndex" class="subgroup-row">
          <input v-model="subgroup.label" type="text" placeholder="Subgroup label" />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        states: [],
      };
    },
    methods: {
      addState() {
        this.states.push({
          label: '',
          subgroups: [],
        });
      },
      addSubgroup(stateIndex) {
        this.states[stateIndex].subgroups.push({
          label: '',
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .state-row {
    margin-bottom: 10px;
  }
  .subgroup-row {
    margin-left: 20px;
  }
  </style>
  