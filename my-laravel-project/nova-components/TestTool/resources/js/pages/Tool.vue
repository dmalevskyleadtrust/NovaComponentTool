<template>
  <div class="drag-drop-ui">
    <h1 class="page-title">Drag and Drop UI</h1>

    <div class="add-group-container">
      <input v-model="newGroupLabel" placeholder="New Group Label" />
      <Button class="add-group-button" @click="addGroup">Add State</Button>
    </div>
    <div class="group-container">
      <Group
    v-for="(group, index) in groups"
    :key="index"
    :group="group"
    :active-group="activeGroupIndex === index"
    @group-drop="handleDrop($event, group.id)"
/>

    </div>
  </div>
</template>

<script>
import Group from '../components/Groups/Group.vue'
import Button from '../components/Buttons/Button.vue'

export default {
  components: {
    Group,
    Button
  },
  data() {
    return {
      groups: [],
      newGroupLabel: '',
      newGroupId: '',
      newGroupPayload: '',
    };
  },
  watch: {
    groups(newValue, oldValue) {
      console.log('Groups changed: ', newValue);
    },
  },
  methods: {
    addGroup() {
    if (this.newGroupLabel.trim() === '') {
        return;
    }

    const newGroupId = this.groups.length > 0 ? Math.max(...this.groups.map(group => group.id)) + 1 : 1;

    this.groups.push({
        id: newGroupId,
        label: this.newGroupLabel,
        payload: this.newGroupPayload,
        subgroups: [],  // Initialize 'subgroups' as an empty array
    });

    this.newGroupLabel = '';
    this.newGroupId = '';
    this.newGroupPayload = '';
},



handleDrop(event, droppedGroupId) {
  if (event.dataTransfer.types.includes('text/plain')) {
    // Convert the draggedGroupId to a number
    const draggedGroupId = Number(event.dataTransfer.getData('text/plain'));
    
    if (!draggedGroupId) {
        console.error('Unable to find group id in drag event data');
        return;
    }

    // Use the converted draggedGroupId here
    const draggedGroupIndex = this.groups.findIndex(group => group.id === draggedGroupId);
    console.log('Dragged group index:', draggedGroupIndex);
    if (draggedGroupIndex < 0) {
        console.error('Unable to find group with id:', draggedGroupId);
        return;
    }

    // Convert the droppedGroupId to a number
    droppedGroupId = Number(droppedGroupId);
    
    // Use the converted droppedGroupId here
    const droppedGroupIndex = this.groups.findIndex(group => group.id === droppedGroupId);
    console.log('Dropped group index:', droppedGroupIndex);
    if (droppedGroupIndex < 0) {
        console.error('Unable to find group with id:', droppedGroupId);
        return;
    }

    const draggedGroup = this.groups.splice(draggedGroupIndex, 1)[0];
    this.groups.splice(droppedGroupIndex, 0, draggedGroup);
  }
},


  },
};
</script>

<style>
.drag-drop-ui {
  margin: 20px;
  font-family: Arial, sans-serif;
}
.active-group {
  background-color: #333;
  color: #fff;
}

.page-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 16px;
}

.group-container {
  display: flex;
  flex-direction: column;
}

.add-group-container {
  margin-top: 16px;
  margin-bottom: 16px;
}

input {
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 8px;
}

input::placeholder {
  color: #999;
}

input:focus {
  outline: none;
  border-color: #4285f4;
}
</style>
