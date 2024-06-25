






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


//slide

class Slide{
    constructor(slide, wrapper){
        this.slide = document.querySelector(slide);
        this.wrapper = document.querySelector(wrapper);

    }
    onStart(event){
        event.preventDefault();
        this.wrapper.addEventListener('mousemove', this.onMove);

        console.log(this)
    }
    onMove(event){
        console.log('moveu')
    }

    onEnd(event){
        
        this.wrapper.removeEventListener('mousemove', this.onMove);

    }

    addSlideEvents(){
        this.wrapper.addEventListener('mousedown', this.onStart);
        this.wrapper.addEventListener('mouseup', this.onEnd);
    }
    bindEvents(){
        this.onStart = this.onStart.bind(this);
        this.onMove = this.onMove.bind(this);
        this.onEnd = this.onEnd.bind(this);
    }



    init(){
        this.bindEvents();
        this.addSlideEvents();
        return this;
    }
}
const slide = new Slide('.slide', '.slide-wrapper');
slide.init();



