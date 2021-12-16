const searchButton = document.getElementById('search');
const resultList = document.getElementById('resultList');

searchButton.addEventListener('click', () => {
    const height = document.getElementById('height').value || 0;
    const width = document.getElementById('width').value || 0;
    const depth = document.getElementById('depth').value || 0;

    fetch(`search/dimensions/${height}/${width}/${depth}`)
        .then((response) => response.json())
        .then((products) => {
            resultList.innerHTML = '';
            Object.values(products).forEach((product) => {
                const li = document.createElement('li');
                li.innerHTML = product.name;

                resultList.append(li);
            });
        });
});
