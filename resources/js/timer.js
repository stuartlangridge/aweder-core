const Timer = class Timer {
  elements = document.getElementsByClassName('hidden-time')

  startTimer = (total_seconds, element) => {
    setInterval(() => {
      const minute = Math.floor((total_seconds) / 60);
      total_seconds++;
      if (minute >= 20) {
        element.parentElement.classList.replace('order__status--new',
          'order__status--old');
      }
      element.parentElement.querySelector('.timer').innerHTML = this.pad(minute)
    }, 1000);
  }

  calculateTime = (element) => {
    let total_seconds = null;
    const time = element.value;
    const split_time = time.split(':');
    if (split_time[0] !== '00') {
      total_seconds += Math.floor(split_time[0] * 60);
    }
    if (split_time[1] !== '00') {
      total_seconds += Math.floor(split_time[1]);
    }
    this.startTimer(total_seconds, element);
  }

  pad = (val) => {
    const valString = val + '';
    if (valString.length < 2) {
      return `0${valString}`;
    }
    return valString;
  }

  init = () => {
    if (this.elements !== null && this.elements.length > 0) {
      for (const element of this.elements) {
        this.calculateTime(element);
      }
    }
  }
};

export default Timer;
