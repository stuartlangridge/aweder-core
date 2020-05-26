const NotificationBanner = class Notification {
  fixedElement = null;

  timeout = null

  addClearDownTimeOut = () => {
    this.timeout = setTimeout(() => {
      this.fixedElement.remove();
      this.removeTimeout();
    }, 6000);
  }

  removeTimeout = () => {
    clearTimeout(this.timeout);
  }


  init = () => {
    this.fixedElement = document.querySelector('.notification__message');
    if (this.fixedElement !== null) {
      this.addClearDownTimeOut();
    }
  };
};

export default NotificationBanner;
