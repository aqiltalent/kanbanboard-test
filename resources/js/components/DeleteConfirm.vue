<template>
  <modal :name="modalName" :width="300" :height="120">
    <div class="kanban__modal__header">
      <div class="kanban__modal__header__title">
        Are you sure?
      </div>
    </div>

    <div class="kanban__modal__footer">
      <k-button
          type="button"
          class="kanban__button--red"
          v-on:button-click="okDelete"
      >
        {{ OkLabel }}
      </k-button>

      <k-button
          type="button"
          class="kanban__button--orange ml-auto"
          v-on:button-click="cancel"
      >
        {{ CancelLabel }}
      </k-button>
    </div>
  </modal>
</template>

<script>
import KButton from './KButton';

export default {
  name: 'DeleteConfirm',
  components: {KButton},
  props: {
    modalName: {
      type: String,
      default: 'delete-confirm'
    },
    OkLabel: {
      type: String,
      default: 'Delete'
    },
    CancelLabel: {
      type: String,
      default: 'Cancel'
    }
  },
  methods: {
    okDelete() {
      this.$emit('ok-sure');
    },
    cancel() {
      this.$emit('cancel');
    }
  }
}
</script>