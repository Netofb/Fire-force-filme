






// Menu mobile

const menuButton = document.querySelector('[data-menu="button"]');
const menuList = document.querySelector('[data-menu="List"]');


function openMenu(event){
    menuList.classList.toggle('active');
    menuButton.classList.toggle('active');
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



