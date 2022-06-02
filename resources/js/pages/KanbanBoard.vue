<template>
  <div class="kanban__main">
    <div
        v-for="(column, index) in columns"
        class="kanban__column"
    >
      <div class="kanban__column__header">
        <span>{{ column.title }}</span>
        <span
            class="ml-auto kanban__column__header__close"
            v-on:click="confirmDelete(index)"
        >
          &times;
        </span>
      </div>

      <div class="kanban__column__wrapper">
        <div
            v-for="(card, cardInd) in column.cards"
            class="kanban__column__wrapper__card"
        >

        </div>
      </div>

      <div
          class="kanban__column__footer kanban__text"
          v-on:click="showEditCardModal(0, column)"
      >
        <i class="fas fa-square-plus"></i>
        <span class="kanban__column__new__add__text">
          Add a card
        </span>
      </div>
    </div>

    <div class="kanban__column__new">
      <div
          v-if="addNewColumn"
          class="kanban__column__new__form"
      >
        <label class="kanban__text">
          Column Title:
          <input
              type="text"
              class="kanban__input"
              v-model="columnTitle"
          />
        </label>

        <span
            v-if="validations.addColumn"
            class="kanban__text kanban__text--error"
        >
          Title is required.
        </span>

        <div class="kanban__column__new__form__control">
          <k-button
              type="button"
              class="kanban__button--green"
              :disabled="columnTitle === ''"
              v-on:button-click="addColumn"
          >
            Add Column
          </k-button>

          <k-button
              type="button"
              class="kanban__button--red"
              v-on:button-click="toggleAddNewColumn"
          >
            Cancel
          </k-button>
        </div>
      </div>
      <div
          v-else
          class="kanban__column__new__add"
          v-on:click="toggleAddNewColumn"
      >
        <i class="fas fa-square-plus"></i>
        <span
            class="kanban__column__new__add__text"
        >
          Add a column
        </span>
      </div>
    </div>

    <div class="kanban__float">
      <k-button
          type="button"
          class="kanban__button"
      >
        Export DB
      </k-button>
    </div>

    <delete-confirm
        modal-name="delete-confirm"
        ok-label="Delete Column"
        cancel-label="Cancel"
        v-on:ok-sure="deleteColumn"
        v-on:cancel="cancelDelete"
    />

    <k-modal
        modal-name="edit-card"
        :width="350"
        :height="300"
    >
      <template v-slot:header>
        <div class="kanban__modal__header__title">
          Card Information
        </div>
      </template>

      <div class="kanban__modal__body">

      </div>

      <template v-slot:footer>
        <div class="kanban__modal__footer">
          <k-button
              type="button"
              class="kanban__button--green"
          >
            Save
          </k-button>

          <k-button
              type="button"
              class="kanban__button--orange ml-auto"
              v-on:button-click="closeEditCardModal"
          >
            Cancel
          </k-button>
        </div>
      </template>
    </k-modal>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import KButton from '../components/KButton';
import DeleteConfirm from '../components/DeleteConfirm';
import KModal from '../components/KModal';

export default {
  name: 'kanban-board',
  components: {
    KModal,
    DeleteConfirm,
    KButton,
    draggable
  },
  props: ['columns_data'],
  data: () => ({
    columns: null,
    addNewColumn: false,
    columnTitle: '',
    showEditCard: false,
    selectedColumn: -1,
    selectedCard: null,
    validations: {
      addColumn: false
    }
  }),
  mounted() {
    this.columns = JSON.parse(this.columns_data).data;
  },
  methods: {
    toggleAddNewColumn() {
      this.addNewColumn = !this.addNewColumn;

      if (!this.addNewColumn) {
        this.columnTitle = '';
      }
    },
    addColumn() {
      this.validations.addColumn = false;

      if (!this.columnTitle) {
        this.validations.addColumn = true;

        return false;
      }

      axios.post('/columns', {
        column_title: this.columnTitle
      }).then(r => {
        this.addNewColumn = false;
        this.validations.addColumn = false;
        this.columnTitle = '';
        this.columns = r.data.columns.data
      });
    },
    confirmDelete(ind) {
      this.selectedColumn = ind;

      this.$modal.show('delete-confirm');
    },
    cancelDelete() {
      this.selectedColumn = -1;

      this.$modal.hide('delete-confirm');
    },
    deleteColumn() {
      if (this.selectedColumn === -1) {
        this.cancelDelete();

        return false;
      }

      axios.delete(`/columns/${this.columns[this.selectedColumn].id}`).then(r => {
        this.columns = r.data.columns.data

        this.cancelDelete();
      });
    },
    showEditCardModal(ind, column) {
      if (ind === 0) {
        this.selectedCard = Object.assign({}, {
          id: 0,
          column_id: column.id,
          card_title: '',
          card_description: '',
          card_order: -1,
          comments: null
        });
      } else {
        this.selectedCard = Object.assign({}, column.cards[ind]);
      }

      this.$modal.show('edit-card');
    },
    closeEditCardModal() {
      this.selectedCard = null;

      this.$modal.hide('edit-card');
    }
  }
}
</script>