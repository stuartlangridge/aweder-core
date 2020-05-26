const Delivery = class DeliveryCost {
  /**
   *
   * @type NodeListOf
   */
  collectionChoice = null;

  /**
   *
   * @type Element
   */
  deliveryFieldWrapper = null

  attachChangeEvent = () => {
    this.collectionChoice.forEach((choiceItem) => {
      choiceItem.addEventListener('change', this.changeFunction);
    });
  }

  changeFunction = (item) => {
    if (item.target.dataset.collectionType !== undefined) {
      if (item.target.dataset.collectionType === 'delivery') {
        this.deliveryFieldWrapper.classList.add('show');
        document.querySelector('.order__total-delivery').classList.add('show');
        document.querySelector('.order__total-with-delivery').classList.add('show');
        document.querySelector('.order__total-no-delivery').classList.add('hide');
      } else {
        this.deliveryFieldWrapper.classList.remove('show');
        document.querySelector('.order__total-delivery').classList.remove('show');
        document.querySelector('.order__total-no-delivery').classList.remove('hide');
        document.querySelector('.order__total-with-delivery').classList.remove('show');
      }
    }
  }

  init = () => {
    this.collectionChoice = document.querySelectorAll('.collection-choice');
    if (this.collectionChoice !== null) {
      this.deliveryFieldWrapper = document.querySelector('.delivery--wrapper');
      this.attachChangeEvent();
    }
  };
};

export default Delivery;
