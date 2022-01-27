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

function getQueryParameters(page) {
    // create string of paramaters for the Ajax function
    let searchParams = `/${page}`;

    const select = document.getElementById('search_product_category');
    searchParams += `/${select.value}`;

    const inputs = document.querySelectorAll('input');
    inputs.forEach((input) => {
        if (input.value.length < 10 && input.type !== 'hidden') {
            searchParams += `/${(input.value === '' ? '0' : input.value)}`;
        }
    });

    return searchParams;
}

function calcNumberOfPage() {
    const numberOfProducts = document.getElementById('numberOfProducts');
    const productNumber = numberOfProducts.innerText.split(' ')[0];
    const numberOfPages = Math.ceil(productNumber / 20);
    return numberOfPages;
}

function previousPage(currentPage) {
    getProducts(currentPage - 1);
}

function nextPage(currentPage) {
    getProducts(currentPage + 1);
}

function handlePagination(value, currentPage) {
    if (value === '<') {
        previousPage(currentPage);
        return;
    }
    if (value === '>') {
        nextPage(currentPage);
        return;
    }
    getProducts(value);
}

function generatePagination() {
    const numberOfPages = calcNumberOfPage();
    const currentPage = parseInt(document.getElementById('page').value, 10);
    const pagination = document.getElementById('paginationBottom');
    const paginationTop = document.getElementById('paginationTop');

    pagination.innerHTML = '';

    if (currentPage > 1) {
        const previousButton = document.createElement('button');
        previousButton.innerHTML = '<';
        previousButton.className = 'paginationBtn btn';
        pagination.appendChild(previousButton);
    }

    const firstPageButton = document.createElement('button');
    firstPageButton.innerHTML = '1';
    firstPageButton.className = 'paginationBtn btn';
    pagination.appendChild(firstPageButton);

    let startPagination = Math.max(Math.min(currentPage - 3, numberOfPages - 5), 2);

    if (Math.max(currentPage - 2, 2) > 2 && numberOfPages > 5) {
        const separator = document.createElement('div');
        separator.innerHTML = '...';
        pagination.appendChild(separator);
        startPagination += 2;
    }

    for (
        let i = startPagination;
        i <= Math.min(Math.max(currentPage + 3, 5), numberOfPages);
        i += 1
    ) {
        const button = document.createElement('button');
        button.innerHTML = i;
        button.className = 'paginationBtn btn';
        pagination.appendChild(button);
    }

    if (currentPage < numberOfPages) {
        const nextButton = document.createElement('button');
        nextButton.innerHTML = '>';
        nextButton.className = 'paginationBtn btn';
        pagination.appendChild(nextButton);
    }

    const buttons = document.querySelectorAll('.paginationBtn');
    buttons.forEach((button) => {
        button.classList.add('border-2', 'rounded-md');
        if (button.className.includes('active')) {
            button.classList.remove('active');
        }
        if (parseInt(button.innerText, 10) === parseInt(currentPage, 10)) {
            button.classList.add('active');
        }
    });

    const paginationTopContent = document.createElement('div');
    paginationTopContent.innerHTML = pagination.innerHTML;
    paginationTopContent.classList.add('mx-auto', 'w-5/6', 'md:w-3/6', 'flex', 'justify-around', 'mb-5');
    paginationTop.appendChild(paginationTopContent);

    const allButtons = document.querySelectorAll('.paginationBtn');
    allButtons.forEach((button) => {
        button.addEventListener('click', (e) => handlePagination(e.target.innerText, currentPage));
    });
}

function getHtml(html) {
    const productContainer = document.getElementById('productContainer');
    const productsHtml = html.split('GMT')[1];
    productContainer.innerHTML = productsHtml;
}

function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

async function fetchProduct(searchParams) {
    await fetch(`http://localhost:8000/ajax${searchParams}`, {
        method: 'GET',
    })
        .then((response) => response.text())
        .then((html) => getHtml(html))
        .then(() => generatePagination())
        .then(() => scrollToTop());
}

function getProducts(page = 1) {
    // get the product container
    const productContainer = document.getElementById('productContainer');

    // check if this is the search product page
    if (productContainer !== null) {
        const searchParams = getQueryParameters(page);
        // fetch
        fetchProduct(searchParams);
    }
}

// Wait for the page content to load
window.onload = getProducts();
