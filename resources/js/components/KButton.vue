<template>
  <button class="kanban__button" v-on:click="clickButton">
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "KButton",
  props: {
    btnText: {
      type: String,
      default: 'Button'
    }
  },
  methods: {
    clickButton(){
      this.$emit('button-click');
    }
  }
}
</script>