<template>
  <div 
     class="subgroup"
     :style="subgroupStyles"
     draggable="true"
     @dragstart="handleDragStart"
     @dragover.prevent
     @drop="handleSubgroupDrop"
   >
     <div class="subgroup-header">
       <label-component>Subgroup Label:</label-component>
       <input-component v-model="subgroup.label"></input-component>
       <button-component class="expand-collapse-button" @click="isExpanded = !isExpanded">
         {{ isExpanded ? 'Collapse' : 'Expand' }}
       </button-component>
     </div>
 
     <div v-show="isExpanded">
       <button-component @click="addSubSubgroup($event)">
         Add SubSubgroup
       </button-component>
       <button-component class="delete-button" @click="deleteSubgroup">Delete Subgroup</button-component>
       <subgroup-component
  @move-subgroup="handleMoveSubgroup"
  v-for="(subSubgroup) in subgroup.subgroups"
  :key="subSubgroup.id" 
  :subgroup="subSubgroup" 
  :depth="depth + 1" 
  @subgroup-drop="handleSubgroupDropEvent($event)"
  :root="root"
  @update-root="$emit('update-root', $event)"
/>

     </div>
   </div>
 </template>
 <script>
 import ButtonComponent from '../Buttons/Button.vue'
 import LabelComponent from '../Buttons/Label.vue'
 import InputComponent from '../Buttons/Input.vue'
 
 export default {
   errorCaptured(err, vm, info) {
     console.error(`Error captured in ${vm.$options.name}: ${info}`, err);
     return false;  // prevent the error from propagating further
   },
   data() {
   return {
     isExpanded: true,
   };
 },
 
   name: 'subgroup-component',  // This is important for recursion
   components: {
     ButtonComponent,
     LabelComponent,
     InputComponent
   },
   props: {
   subgroup: {
     type: Object,
     required: true,
   },
   depth: {  // moved inside props
     type: Number,
     default: 0,
   },
   root: {
     type: Object,
     required: true,
   },
 },
 
   computed: {
   subgroupStyles() {
     const color = this.depth % 2 === 0 ? 'blue' : 'red';  // Change colors as per your need
     return {
       borderLeft: `${this.depth * 10}px solid ${color}`,
       backgroundColor: this.depth % 2 === 0 ? '#f0f8ff' : '#e6e6fa',
     };
   },
 },
 
   methods: {
    handleUpdateRoot(newRoot) {
      this.$emit('update-root', JSON.parse(JSON.stringify(this.root)));
  },
      //...
  deleteSubgroup() {
    // Find the parent of this subgroup and its index
    const { parent, index } = this.findSubgroupAndParent(this.root, this.subgroup.id);

    if (!parent) {
      console.error('Invalid delete operation. Subgroup not found.');
      return;
    }

    // Remove the subgroup from the parent's subgroups array
    parent.subgroups.splice(index, 1);

    // Trigger Vue's reactivity system
    parent.subgroups = [...parent.subgroups];
  },
  handleMoveSubgroup({ droppedId, targetId }) {
  const oldParentAndIndex = this.findSubgroupAndParent(this.root, droppedId);
  const newParentAndIndex = this.findSubgroupAndParent(this.root, targetId);

  if (!oldParentAndIndex || !newParentAndIndex) {
    console.error('Invalid move operation');
    return;
  }

  // Ensure that subgroups are moved within the same depth
  if (oldParentAndIndex.depth !== newParentAndIndex.depth) {
    console.error('Cannot move subgroups across different depths');
    return;
  }

  const removed = oldParentAndIndex.parent.subgroups.splice(oldParentAndIndex.index, 1)[0];
  newParentAndIndex.parent.subgroups.splice(newParentAndIndex.index, 0, removed);

  // Force Vue to re-render the list by replacing it with a shallow copy
  newParentAndIndex.parent.subgroups = [...newParentAndIndex.parent.subgroups];
},


handleSubgroupDropEvent({ droppedId, targetId, depth }) {
  console.log(`handleSubgroupDropEvent called with droppedId: ${droppedId}, targetId: ${targetId}, depth: ${depth}`);
      
  const oldParentAndIndex = this.findSubgroupAndParent(this.root, droppedId);
  const targetParentAndIndex = this.findSubgroupAndParent(this.root, targetId);

  if (!oldParentAndIndex || !targetParentAndIndex) {
    console.error('Invalid move operation');
    return;
  }

  // Ensure that subgroups are moved within the same depth
  if (oldParentAndIndex.depth !== depth) {
    console.error('Cannot move subgroups across different depths');
    return;
  }

  const removed = oldParentAndIndex.parent.subgroups.splice(oldParentAndIndex.index, 1)[0];
      
  // Insert the removed subgroup at the new position
  targetParentAndIndex.parent.subgroups.splice(targetParentAndIndex.index, 0, removed);

  // Ask the parent component to update the root
  this.$emit('update-root', JSON.parse(JSON.stringify(this.root)));
},



    findSubgroupAndParent(root, targetId, depth = 0) {
      console.log(`findSubgroupAndParent called with root: ${JSON.stringify(root)} and targetId: ${targetId}`);
      
      if (root) {
        for (let i = 0; i < root.subgroups.length; i++) {
          if (root.subgroups[i].id === targetId) {
            return { parent: root, index: i, depth: depth };
          } else if (root.subgroups[i].subgroups.length > 0) {
            const result = this.findSubgroupAndParent(root.subgroups[i], targetId, depth + 1);
            if (result) {
              return result;
            }
          }
        }
      }
      return null;
    },
 
 moveSubgroup({ droppedId, targetId }) {
   console.log(`moveSubgroup called with droppedId: ${droppedId} and targetId: ${targetId}`);
   const oldParentAndIndex = this.findSubgroupAndParent(this.root, droppedId);
   console.log(`oldParentAndIndex:`, oldParentAndIndex);
   const newParent = this.findSubgroupAndParent(this.root, targetId).parent;
   console.log(`newParent:`, newParent);
 
   if (!oldParentAndIndex || !newParent) {
     console.error('Invalid drop operation');
     return;
   }
 
   const [removed] = oldParentAndIndex.parent.subgroups.splice(oldParentAndIndex.index, 1);
   console.log(`Removed subgroup:`, removed);
   newParent.subgroups.push(removed);
   console.log(`New parent's subgroups after move:`, newParent.subgroups);
 },
 
 
 handleDragStart(event) {
  if (!this.subgroup || !this.subgroup.id) {
    console.error('Subgroup or subgroup id is undefined');
    return;
  }
  console.log(`Dragging started for subgroup: ${this.subgroup.id}`);
  event.dataTransfer.setData('text/subgroup', this.subgroup.id.toString());
  event.stopPropagation();  // Stops the event from bubbling up to parent elements
},

findSubgroupParent(root, targetId) {
  if (root) {
    for (let i = 0; i < root.subgroups.length; i++) {
      if (root.subgroups[i].id === targetId) {
        return root;
      } else if (root.subgroups[i].subgroups.length > 0) {
        const result = this.findSubgroupParent(root.subgroups[i], targetId);
        if (result) {
          return result;
        }
      }
    }
  }
  return null;
},
 
 handleDragOver(event) {
   event.preventDefault();
   console.log(`Drag over subgroup: ${this.subgroup.id}`);
 },
 handleSubgroupDrop(event) {
      event.preventDefault();
      event.stopPropagation();

      const draggedSubgroupId = event.dataTransfer.getData('text/subgroup');

      if (!draggedSubgroupId) {
        console.error('Unable to find subgroup id in drag event data');
        return;
      }

      const targetId = this.subgroup.id;
      const targetParentAndIndex = this.findSubgroupAndParent(this.root, targetId);

      this.$emit('subgroup-drop', { 
        droppedId: draggedSubgroupId, 
        targetId: targetId,
        depth: targetParentAndIndex.depth,
      });
    },

 addSubSubgroup(event) {
   event.stopPropagation();
   this.subgroup.subgroups.push({
     id: `${Date.now()}-${Math.round(Math.random() * 1000)}`,  // generate a unique id
     label: '',
     payload: '',
     subgroups: [],
   });
 },
 
   },
 }
 </script>
  
 
 <style scoped>
 .delete-button {
  background-color: #dc3545;  /* red background */
  color: white; /* white text */
  padding: 5px;
  margin: 5px;
  border-radius: 5px;
  font-weight: bold;
}

.delete-button:hover {
  background-color: #c82333; /* darker red */
}

 .subgroup {
   margin-bottom: 16px;  /* increased margin */
   display: flex;
   flex-direction: column;
   padding-left: 30px; /* increased padding */
 }
 
 .subgroup-container {
   margin-top: 10px; /* added margin for nested subgroups */
 }
 
 .subgroup-label {
   font-weight: bold;
   margin-right: 8px;
   color: #000;
 }
 
 .add-subgroup-container {
   display: flex;
   align-items: center;
   margin-top: 8px;
 }
 
 input {
   padding: 6px 12px;
   border: 1px solid #ccc;
   border-radius: 4px;
   margin-right: 8px;
   width: 200px; /* set a width for the input fields */
 }
 
 input::placeholder {
   color: #999;
 }
 
 input:focus {
   outline: none;
   border-color: #4285f4;
 }
 .expand-collapse-button {
   background-color: #28a745;  /* green background */
   color: white; /* white text */
   padding: 5px;
   margin: 5px;
   border-radius: 5px;
   font-weight: bold;
 }
 .expand-collapse-button:hover {
   background-color: #1e7e34; /* darker green */
 }
 
 </style>