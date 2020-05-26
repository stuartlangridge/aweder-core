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
        this.addShowClassToDeliveryElements();
      } else {
        this.removeShowClassToDeliveryElements();
      }
    }
  }

  addShowClassToDeliveryElements = () => {
    this.deliveryFieldWrapper.forEach((choiceItem) => {
      choiceItem.classList.add('show');
    });
  }

  removeShowClassToDeliveryElements = () => {
    this.deliveryFieldWrapper.forEach((choiceItem) => {
      choiceItem.classList.remove('show');
    });
  }

  init = () => {
    this.collectionChoice = document.querySelectorAll('.collection--type');
    if (this.collectionChoice !== null) {
      this.deliveryFieldWrapper = document.querySelectorAll('.delivery');
      this.attachChangeEvent();
    }
  };
};

export default Delivery;
