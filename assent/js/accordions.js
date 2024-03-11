// Obtén todos los elementos del acordeón
const accordionItems = document.querySelectorAll('.accordion-item');

// Agrega un evento clic a cada elemento del acordeón
accordionItems.forEach(item => {
    const title = item.querySelector('.accordion-title');
    const content = item.querySelector('.accordion-content');

    title.addEventListener('click', () => {
        // Alternar la visibilidad del contenido al hacer clic en el título
        content.style.display = (content.style.display === 'block' || content.style.display === '') ? 'none' : 'block';
    });
});
