export default function initDropdown(){



}

const dropdown = document.querySelectorAll('[data-dropdown]');

dropdown.forEach(menu =>{
    ['touchstart', 'click'].forEach(userEvent =>{
        menu.addEventListener(userEvent, handleClick);
    })
});

function handleClick(event){
    event.preventDefault();
    this.classList.toggle('Active');
}