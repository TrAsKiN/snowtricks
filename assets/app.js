import * as bootstrap from 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.scss';
import './styles/app.scss';
import './bootstrap';

let scrollButton = document.querySelector('#scroll-button');
window.addEventListener('scroll', () => {
    if (window.scrollY >= 0 && window.scrollY < window.outerHeight / 2) {
        scrollButton.querySelector('.bi-arrow-down').classList.remove('d-none');
        scrollButton.querySelector('.bi-arrow-up').classList.add('d-none');
        scrollButton.href = '#tricks';
    } else if (window.scrollY >= window.outerHeight / 2) {
        scrollButton.querySelector('.bi-arrow-down').classList.add('d-none');
        scrollButton.querySelector('.bi-arrow-up').classList.remove('d-none');
        scrollButton.href = '#';
    }
});

const toastElList = document.querySelectorAll('.toast');
const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
toastList.forEach(toast => toast.show());
