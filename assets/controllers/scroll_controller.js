import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        window.addEventListener('scroll', () => {
            if (window.scrollY >= 0 && window.scrollY < window.outerHeight / 2) {
                this.element.querySelector('.bi-arrow-down').classList.remove('d-none');
                this.element.querySelector('.bi-arrow-up').classList.add('d-none');
                this.element.href = '#tricks';
            } else if (window.scrollY >= window.outerHeight / 2) {
                this.element.querySelector('.bi-arrow-down').classList.add('d-none');
                this.element.querySelector('.bi-arrow-up').classList.remove('d-none');
                this.element.href = '#';
            }
        });
    }
}
