import axios from 'axios';
import debounce from 'debounce';

const SlugChecker = class SlugChecker {
  removeExistingValidationErrors = (element) => {
    const errors = element.parentElement.querySelector('.form__validation-error--slug');
    if (errors) {
      element.parentNode.removeChild(errors);
      element.parentElement.classList.remove('input-error');
    }
  };

  hasEnoughCharactersToLookup = (e) => {
    return e.target.value.length > 3;
  }

  init = () => {
    const element = document.getElementById('url-slug');
    if (element !== null) {
      element.addEventListener('keyup', debounce((e) => {
        this.removeExistingValidationErrors(element);

        if (!this.hasEnoughCharactersToLookup(e)) {
          return;
        }

        axios
          .get(`/validate-slug/${e.target.value}`)
          .then((response) => {
            if (response.data.exists === true) {
              element.parentElement.classList.add('input-error');
              const error = document.createElement('p');
              error.innerText = 'The slug is already taken.';
              error.classList.add('form__validation-error');
              error.classList.add('form__validation-error--slug');
              element.parentElement.insertBefore(error, element);
            }
          });
      }, 600));
    }
  };
};

export default SlugChecker;
