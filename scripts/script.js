






// Menu mobile

const menuButton = document.querySelector('[data-menu="button"]');
const menuList = document.querySelector('[data-menu="List"]');


function openMenu(event){
    menuList.classList.toggle('active');
    menuButton.classList.toggle('active');
}
menuButton.addEventListener('click', openMenu);




// dropdown
// const dropdown = document.querySelectorAll('[data-dropdown]');

// dropdown.forEach(menu => {
//     ['touchstart', 'click'].forEach(userEvent => {
//         menu.addEventListener(userEvent, handleClick);
//     });
// });

// function handleClick(event) {
//     event.preventDefault();
//     const element = this; // Captura o elemento correto
//     element.classList.add('active');
//     outsideClick(element, () => {
//         element.classList.remove('active');
//     });
// }

// function outsideClick(element, callback) {
//     const html = document.documentElement;
//     function handleOutsideClick(event) {
//         if (!element.contains(event.target)) {
//             html.removeEventListener('click', handleOutsideClick);
//             callback();
//         }
//     }
//     html.addEventListener('click', handleOutsideClick);
// }



//slide

class Slide{
    constructor(slide, wrapper){
        this.slide = document.querySelector(slide);
        this.wrapper = document.querySelector(wrapper);
        this.dist = {finalPosition: 0, startX: 0, movement: 0}

    };


    transition(active){
        this.slide.style.transition = active ? 'transform .3s' : '';
    }


    moveSlide(distX){
        this.dist.movePosition = distX;
        this.slide.style.transform = `translate3d(${distX}px, 0, 0)`;
    }


    updatePosition(clientX){
        this.dist.movement = (this.dist.startX - clientX) * 1.6;
        return this.dist.finalPosition - this.dist.movement;
    }

    onStart(event){
        let movetype;
        if(event.type === 'mousedown'){
            event.preventDefault();
            this.dist.startX = event.clientX;
            movetype = 'mousemove';
        }else{
            this.dist.startX = event.changedTouches[0].clientX;
            movetype = 'touchmove';
        }
        this.wrapper.addEventListener(movetype, this.onMove);
        this.transition(false);
    }


    onMove(event){
        const pointerPosition = (event.type === 'mousemove') ? event.clientX : event.changedTouches[0].clientX;;
        const finalPosition = this.updatePosition(pointerPosition);
        this.moveSlide(finalPosition);
    }

    onEnd(event){
        const movetype = (event.type === 'mouseup') ? 'mousemove' : 'touchmove';
        this.wrapper.removeEventListener(movetype, this.onMove);
        this.dist.finalPosition = this.dist.movePosition;
        this.transition(true);
        this.changeSlideOnEnd();

    }

    changeSlideOnEnd(){
        if(this.dist.movement > 120 && this.index.next !== undefined){
            this.activeNextSlide()
        }else if(this.dist.movement < -120 && this.index.prev !== undefined){
            this.activePrevSlide()
        }else{
            this.changeSlide(this.index.active);
        }
    }



    addSlideEvents(){
        this.wrapper.addEventListener('mousedown', this.onStart);
        this.wrapper.addEventListener('touchstart', this.onStart);
        this.wrapper.addEventListener('mouseup', this.onEnd);
        this.wrapper.addEventListener('touchend', this.onEnd);


    }
    bindEvents(){
        this.onStart = this.onStart.bind(this);
        this.onMove = this.onMove.bind(this);
        this.onEnd = this.onEnd.bind(this);
    }
    // slide config

    slidePosition(slide){
        const margin = (this.wrapper.offsetWidth - slide.offsetWidth) / 2;

        return -(slide.offsetLeft - margin);
    }



    slidesConfig(){
        this.slideArray = [...this.slide.children].map((element)=>{
            const position = this.slidePosition(element);
            return{ position, element }
        });
    }

    slidesIndexNav(index){
        const last = this.slideArray.length - 1;
        this.index = {
            prev: index ? index - 1 : undefined,
            active: index,
            next: index === last ? undefined : index + 1,
        }
    }

    changeSlide(index){
        const activeSlide = this.slideArray[index];
        this.moveSlide(activeSlide.position);
        this.slidesIndexNav(index);
        this.dist.finalPosition = activeSlide.position;
    }

    activePrevSlide(){
        if(this.index.prev !== undefined) this.changeSlide(this.index.prev)
    }

    activeNextSlide(){
        if(this.index.next !== undefined) this.changeSlide(this.index.next)
    }
    

    init(){
        this.bindEvents();
        this.transition(true);

        this.addSlideEvents();
        this.slidesConfig();
        return this;
    }
}
const slide = new Slide('.slide', '.slide-wrapper');
slide.init();
slide.changeSlide(1)

const slide2 = new Slide('.slide2', '.slide-wrapper2');
slide2.init();
slide2.changeSlide(1);


const slide3 = new Slide('.slide3', '.slide-wrapper3');
slide3.init();
slide3.changeSlide(1);

const slide4 = new Slide('.slide4', '.slide-wrapper4');
slide4.init();
slide4.changeSlide(1);
