<template>
  <modal
      :name="modalName"
      :width="width"
      :height="height"
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
      type: Number,
      default: 600
    },
    height: {
      type: Number,
      default: 300
    }
  }
}
</script>