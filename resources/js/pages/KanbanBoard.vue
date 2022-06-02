<template>
  <div class="kanban__main">
    <div
        v-for="(column, index) in columns"
        :key="`column-${index}`"
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

      <draggable
          v-model="column.cards"
          v-bind="dragOptions"
          v-bind:move="onMoveCard"
          v-on:start="isDragging=true"
          v-on:end="onMoveEnd"
          class="kanban__column__wrapper"
          :id="`column-${index}`"
          group="column"
      >
        <div
            v-if="column.cards.length"
            v-for="(card, cardInd) in column.cards"
            :key="`card-${cardInd}`"
            class="kanban__column__wrapper__card"
        >
          <div
              class="kanban__column__wrapper__card__title"
          >
            {{ card.card_title }}
          </div>
          <div class="kanban__column__wrapper__card__control">
            <k-button
                type="button"
                class="p-1 kanban__button--blue"
                v-on:button-click="showEditCardModal(cardInd, column, index)"
            >
              <i class="far fa-edit"/>
            </k-button>
            <k-button
                type="button"
                class="p-1 kanban__button--red ml-auto"
                v-on:button-click="confirmDeleteCard(card)"
            >
              <i class="far fa-trash-alt"/>
            </k-button>
          </div>
        </div>
      </draggable>

      <div
          class="kanban__column__footer kanban__text"
          v-on:click="showEditCardModal(-1, column, index)"
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
          v-on:button-click="dumpDatabase"
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

    <delete-confirm
        modal-name="delete-card"
        ok-label="Delete"
        cancel-label="Cancel"
        v-on:ok-sure="deleteCardData"
        v-on:cancel="cancelDeleteCard"
    />

    <k-modal
        modal-name="edit-card"
    >
      <template v-slot:header>
        <div class="kanban__modal__header__title">
          Card Information
        </div>
      </template>

      <div class="kanban__modal__body" v-if="showEditCard">
        <div class="kanban__form__group">
          <label class="kanban__form__label">
            Card Title:
          </label>
          <input type="text" class="kanban__input" v-model="selectedCard.card_title"/>
        </div>

        <div class="kanban__form__group">
          <label class="kanban__form__label">
            Card Description:
          </label>
          <textarea class="kanban__input" rows="3" v-model="selectedCard.card_description"/>
        </div>
      </div>

      <template v-slot:footer>
        <div v-if="showEditCard" class="kanban__modal__footer">
          <k-button
              type="button"
              class="kanban__button--green"
              v-on:button-click="saveCardData"
              :disabled="!selectedCard.card_title || !selectedCard.card_description"
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
    deleteCard: null,
    selectedCard: null,
    validations: {
      addColumn: false
    },
    dragOptions: {
      animation: 200
    },
    isDragging: false,
    delayedDragging: false,
    oldItem: null
  }),
  mounted() {
    this.columns = JSON.parse(this.columns_data).data;
  },
  methods: {
    /**
     * Toggle add new column
     */
    toggleAddNewColumn() {
      this.addNewColumn = !this.addNewColumn;

      if (!this.addNewColumn) {
        this.columnTitle = '';
      }
    },
    /**
     * Add column data to database
     * @returns {boolean}
     */
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
    /**
     * confirm to delete column
     * @param ind
     */
    confirmDelete(ind) {
      this.selectedColumn = ind;

      this.$modal.show('delete-confirm');
    },
    /**
     * cancel to delete column
     */
    cancelDelete() {
      this.selectedColumn = -1;

      this.$modal.hide('delete-confirm');
    },
    /**
     * delete column from database
     *
     * @returns {boolean}
     */
    deleteColumn() {
      if (this.selectedColumn === -1) {
        this.cancelDelete();

        return false;
      }

      axios.delete(`/columns/${this.columns[this.selectedColumn].id}`).then(r => {
        this.columns = r.data.columns.data;

        this.cancelDelete();
      });
    },
    /**
     * show edit card modal
     * @param ind
     * @param column
     * @param colInd
     */
    showEditCardModal(ind, column, colInd) {
      this.showEditCard = true;

      if (ind < 0) {
        this.selectedCard = Object.assign({}, {
          id: 0,
          column_id: column.id,
          card_title: '',
          card_description: '',
          card_order: ind,
          comments: null,
          columnInd: colInd
        });
      } else {
        this.selectedCard = Object.assign({}, column.cards[ind]);

        this.selectedCard.columnInd = colInd;
        this.selectedCard.card_order = ind;
      }

      this.$modal.show('edit-card');
    },
    /**
     * close edit card modal
     */
    closeEditCardModal() {
      this.showEditCard = false;

      this.$modal.hide('edit-card');
    },
    /**
     * Get column index
     * @param id
     * @returns {*}
     */
    getColIndex(id) {
      return this.columns.findIndex((item) => item.id === id);
    },
    /**
     * save card data to database
     */
    saveCardData() {
      const columnInd = this.getColIndex(this.selectedCard.column_id);

      if (this.selectedCard.card_order < 0 || this.selectedCard.id === 0) {
        const saveCard = Object.assign({}, {
          ...this.selectedCard,
          card_order: this.columns[columnInd].cards.length
        });

        this.columns[columnInd].cards.push(saveCard);

        this.createCard(saveCard);
      } else {
        this.columns[columnInd].cards[this.selectedCard.card_order] = Object.assign({}, this.selectedCard);
        this.updateCard(this.columns[columnInd].cards[this.selectedCard.card_order], columnInd);
      }
    },
    onMoveCard(e) {
      this.oldItem = Object.assign({}, e.draggedContext.element);

      return true;
    },
    onMoveEnd(e) {
      const oldColumnIndex = this.getColIndex(this.oldItem.column_id);
      const columnId = e.to.getAttribute('id');
      const columnIndex = parseInt(columnId.split('-')[1]);
      const oldColumn = Object.assign({}, this.columns[oldColumnIndex]);
      const newColumn = Object.assign({}, this.columns[columnIndex]);

      this.columns[oldColumnIndex].cards.map((el, ind) => {
        el.card_order = ind;
        el.column_id = oldColumn.id;
        el.columnInd = oldColumnIndex;

        return el;
      });

      this.$nextTick(() => {
        this.columns[columnIndex].cards.map((el, ind) => {
          el.card_order = ind;
          el.columnInd = columnIndex;
          el.column_id = newColumn.id;

          return el;
        });

        this.$nextTick(() => {
          const cardIndex = this.columns[columnIndex].cards.findIndex((item) => item.id === this.oldItem.id);

          this.updateCard(this.columns[columnIndex].cards[cardIndex], columnIndex);
        });
      });
    },
    /**
     * Create Card
     *
     * @param card
     */
    createCard(card) {
      axios.post('/cards', {
        column_id: card.column_id,
        card_title: card.card_title,
        card_description: card.card_description,
        card_order: card.card_order,
      }).then(r => {
        this.columns = r.data.columns.data;

        if (this.showEditCard) {
          this.closeEditCardModal();
        }
      });
    },
    /**
     * Update card data
     *
     * @param card
     * @param columnInd
     */
    updateCard(card, columnInd) {
      axios.put(`/cards/${card.id}`, {
        column_id: card.column_id,
        card_title: card.card_title,
        card_description: card.card_description,
        card_order: card.card_order,
      }).then(r => {
        if (this.showEditCard) {
          this.closeEditCardModal();
        }

        this.updateCardOrders(columnInd);
      });
    },

    /**
     * Update cards order numbers
     *
     * @param columnInd
     */
    updateCardOrders(columnInd) {
      const column = this.columns[columnInd];
      axios.put(`/columns/${column.id}`, {
        cards: column.cards
      }).then(r => {

      });
    },
    /**
     * confirm to delete column
     * @param card
     */
    confirmDeleteCard(card) {
      this.deleteCard = card;

      this.$modal.show('delete-card');
    },
    /**
     * cancel to delete column
     */
    cancelDeleteCard() {
      this.deleteCard = null;

      this.$modal.hide('delete-card');
    },
    /**
     * delete column from database
     *
     * @returns {boolean}
     */
    deleteCardData() {
      if (!this.deleteCard) {
        this.cancelDeleteCard();

        return false;
      }

      axios.delete(`/cards/${this.deleteCard.id}`).then(r => {
        this.columns = r.data.columns.data;

        this.cancelDeleteCard();
      });
    },

    dumpDatabase() {
      window.open('/dump-db', '_blank');
    }
  },
  watch: {
    isDragging(newValue) {
      if (newValue) {
        this.delayedDragging = true;
        return;
      }
      this.$nextTick(() => {
        this.delayedDragging = false;
      });
    }
  }
}
</script>