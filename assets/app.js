/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import AOS from 'aos';

import 'aos/dist/aos.css';

AOS.init();

const toggleSearchButton = document.getElementById('toggleSearch');
const toggleSearchIcon = document.getElementById('toggleSearchIcon');
const searchField = document.getElementById('searchField');

function handleToggleClick(e) {
    e.preventDefault();
    searchField.classList.toggle('hidden');
    if (searchField.classList.contains('hidden')) {
        toggleSearchIcon.innerHTML = '&dtrif;';
    } else {
        toggleSearchIcon.innerHTML = '&utrif;';
    }
}

toggleSearchButton.addEventListener('click', handleToggleClick);
