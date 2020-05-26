const AdminMenu = class AdminMenu {
  adminMenu = null;

  adminTrigger = null;

  attachClickEvent = () => {
    this.adminTrigger.addEventListener('click', this.toggleMenu);
  };

  toggleMenu = () => {
    this.adminMenu.classList.toggle('admin-menu--open');
  };

  init = () => {
    this.adminMenu = document.getElementById('admin-mobile-nav');
    this.adminTrigger = document.getElementById('admin-mobile-trigger');

    if (this.adminMenu !== null && this.adminTrigger !== null) {
      this.attachClickEvent();
    }
  };
};

export default AdminMenu;
