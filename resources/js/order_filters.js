const OrderFilters = class OrderFilters {
  init = () => {
    const statusFilter = document.getElementById('dashboard-status-filter-select');
    if (statusFilter) {
      this.attachChangeListener(statusFilter);
    }
  };

  attachChangeListener = (statusFilter) => {
    statusFilter.addEventListener('change', (e) => {
      e.target.parentElement.submit();
    });
  }
};

export default OrderFilters;
