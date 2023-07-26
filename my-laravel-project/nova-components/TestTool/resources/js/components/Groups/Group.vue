<template>
<div 
  class="group"
  draggable="true"
  @dragstart="handleDragStart"
  @dragover="handleDragOver"
  @drop="handleDrop($event)"

>
    <div class="group-header">
      <Label :value="group.label" @input="updateLabel"></Label>
    </div>

    <div class="subgroups">
      <Subgroup 
  v-for="(subgroup, subIndex) in group.subgroups"
  :key="'subgroup-' + subgroup.id"
  :subgroup="subgroup"
  :root="group" 
  @updateLabel="updateSubgroupLabel(subIndex, $event)"
  @subgroup-drop="handleSubgroupDrop"
/>


    </div>
    <Button @click="addSubgroup($event)">Add Bundle</Button>
  </div>
</template>

<script>
import Subgroup from './Subgroup.vue'
import Button from '../Buttons/Button.vue'
import Label from '../Buttons/Label.vue'
import Draggable from '../Draggable/Draggable.vue'

export default {
  components: {
    Subgroup,
    Button,
    Label,
    Draggable
  },
  props: {
    group: Object
  },
  methods: {
    handleSubgroupDrop({ droppedId, targetId }) {
    const oldIndex = this.group.subgroups.findIndex(sg => sg.id === droppedId);
    const newIndex = this.group.subgroups.findIndex(sg => sg.id === targetId);
    if (oldIndex < 0 || newIndex < 0) {
      console.error('Invalid subgroup drop');
      return;
    }

    const [subgroup] = this.group.subgroups.splice(oldIndex, 1);
    this.group.subgroups.splice(newIndex, 0, subgroup);
  },

    updateLabel(newLabel) {
      if (this.group) {
        this.group.label = newLabel;
      }
    },
    handleNewButtonClick() {
      console.log("New button clicked");
      if (this.group && this.group.subgroups) {
      this.group.subgroups.push({
        id: '',
        label: '',
        payload: '',
        subgroups: [],
      });
    }
    },
    updateSubgroupLabel(subIndex, newLabel) {
      this.group.subgroups[subIndex].label = newLabel;
    },
    addSubgroup(event) {
      console.log("pp3");
    if (this.group && this.group.subgroups) {
      this.group.subgroups.push({
        id: `${Date.now()}-${Math.round(Math.random() * 1000)}`, // generate a unique id
        label: '',
        payload: '',
        subgroups: [],
      });
    }
  },

  handleMoveSubgroup({ droppedId, targetId }) {
  const oldParentAndIndex = this.findSubgroupAndParent(this.root, droppedId);
  const newParentAndIndex = this.findSubgroupAndParent(this.root, targetId);

  if (!oldParentAndIndex || !newParentAndIndex) {
    console.error('Invalid move operation');
    return;
  }

  const [removed] = oldParentAndIndex.parent.subgroups.splice(oldParentAndIndex.index, 1);
  newParentAndIndex.parent.subgroups.push(removed);
},

  handleDragStart(event) {
  console.log(`Dragging started for group: ${this.group.id}`);
  event.dataTransfer.setData('text/plain', this.group.id);
},



    handleDragOver(event) {
      event.preventDefault()
    },
    handleDrop(event) {
    event.preventDefault();
    this.$emit('group-drop', event);
},



  },
}
</script>

<style scoped>
.active-group {
  background-color: #333;
  color: #fff;
}

.group {
  margin-bottom: 16px;
  border: 1px solid #ccc;
  padding: 12px;
  background-color: #ddd;
}

.group-header {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}

.add-subgroup-container {
  display: flex;
  align-items: center;
  margin-top: 8px;
}

.subgroups {
  margin-left: 24px;
}

.subgroup {
  margin-bottom: 8px;
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
