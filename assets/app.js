import * as bootstrap from 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.scss';
import './styles/app.scss';
import './bootstrap';

const toastElList = document.querySelectorAll('.toast');
const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
toastList.forEach(toast => toast.show());
