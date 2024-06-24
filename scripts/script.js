import initDropdown from './dropdown.js';
import initMenuMobile from './menuMobile.js';






initDropdown();
initMenuMobile();


// Menu mobile

const menuButton = document.querySelector('#btn');
const menuList = document.querySelector('#menu');


function openMenu(event){

    menuList.classList.toggle('active');
    menuButton.classList.add('active');


}
menuButton.addEventListener('click', openMenu);










// dropdown

const dropdown = document.querySelectorAll('[data-dropdown]');

dropdown.forEach(menu =>{
    ['touchstart', 'click'].forEach(userEvent =>{
        menu.addEventListener(userEvent, handleClick);
    })
});

function handleClick(event){
    event.preventDefault();
    this.classList.add('Active');
    outsideClick(this, () =>{
        this.classList.remove('active');
    });
};
function outsideClick(callback){
    const html = document.documentElement;
    html.addEventListener('click', handleOutsideClick);
    function handleOutsideClick(event){
        if(!element.contains(event.target)){
            html.removeEventListener('click', handleOutsideClick);
            callback();
        }
       
    }
}