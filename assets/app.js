/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.scss';
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

let scrollButton = document.querySelector('#scroll-button');
window.addEventListener('scroll', ev => {
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
