<template>
  <modal
      :name="modalName"
      :width="width"
      :height="height"
      :adaptive="adaptive"
      :draggable="true"
      :resizable="true"
  >
    <div class="kanban__modal__header">
      <slot name="header"></slot>
    </div>

    <main>
      <slot></slot>
    </main>

    <footer>
      <slot name="footer"></slot>
    </footer>
  </modal>
</template>

<script>
export default {
  name: "KModal",
  props: {
    modalName: {
      type: String,
      default: 'Kanban'
    },
    width: {
      type: String | Number,
      default: 600
    },
    height: {
      type: String | Number,
      default: 300
    },
    adaptive: {
      type: Boolean,
      default: true
    }
  }
}
</script>