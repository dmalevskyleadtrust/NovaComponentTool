<template>
    <div 
      draggable="true"
      @dragstart="handleDragStart"
      @dragover.prevent
      @drop="handleDrop"
    >
      <slot></slot>
    </div>
</template>
  
<script>
export default {
    props: {
      value: [String, Number]
    },
    methods: {
      handleDragStart(event) {
        event.dataTransfer.setData('text/plain', this.value);
      },
      handleDrop(event) {
        event.preventDefault();
        const groupId = event.dataTransfer.getData('text/plain');
        this.$emit('drop', groupId);  // Emit the groupId
      }
    }
};
</script>
