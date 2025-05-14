const menuButton = document.querySelector('#btn');
const menuList = document.querySelector('[data-menu="List"]');
const dropdown = document.querySelector('[data-dropdown]');

if (menuButton && menuList) {
    // Abre e fecha o menu principal
    menuButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Impede de fechar ao clicar no botão
        menuList.classList.toggle('active');
    });
}

if (dropdown) {
    const dropdownLink = dropdown.querySelector('a');

    // Abre e fecha o dropdown de categorias
    dropdownLink.addEventListener('click', function(event) {
        event.preventDefault(); // Impede a navegação do link
        dropdown.classList.toggle('active'); // Adiciona/remova a classe active
    });
}

// Fechar o dropdown e o menu principal se clicar fora
document.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
    if (!menuList.contains(event.target) && event.target !== menuButton) {
        menuList.classList.remove('active');
    }
});






// Classe Slide
class Slide {
    constructor(slide, wrapper) {
        this.slide = document.querySelector(slide);
        this.wrapper = document.querySelector(wrapper);
        this.dist = { finalPosition: 0, startX: 0, movement: 0 };
    }

    transition(active) {
        this.slide.style.transition = active ? 'transform .3s' : '';
    }

    moveSlide(distX) {
        this.dist.movePosition = distX;
        this.slide.style.transform = `translate3d(${distX}px, 0, 0)`;
    }

    updatePosition(clientX) {
        this.dist.movement = (this.dist.startX - clientX) * 1.6;
        return this.dist.finalPosition - this.dist.movement;
    }

    onStart(event) {
        let movetype;
        if (event.type === 'mousedown') {
            event.preventDefault();
            this.dist.startX = event.clientX;
            movetype = 'mousemove';
        } else {
            this.dist.startX = event.changedTouches[0].clientX;
            movetype = 'touchmove';
        }
        this.wrapper.addEventListener(movetype, this.onMove);
        this.transition(false);
    }

    onMove(event) {
        const pointerPosition = (event.type === 'mousemove')
            ? event.clientX
            : event.changedTouches[0].clientX;
        const finalPosition = this.updatePosition(pointerPosition);
        this.moveSlide(finalPosition);
    }

    onEnd(event) {
        const movetype = (event.type === 'mouseup') ? 'mousemove' : 'touchmove';
        this.wrapper.removeEventListener(movetype, this.onMove);
        this.dist.finalPosition = this.dist.movePosition;
        this.transition(true);
        this.changeSlideOnEnd();
    }

    changeSlideOnEnd() {
        if (this.dist.movement > 120 && this.index.next !== undefined) {
            this.activeNextSlide();
        } else if (this.dist.movement < -120 && this.index.prev !== undefined) {
            this.activePrevSlide();
        } else {
            this.changeSlide(this.index.active);
        }
    }

    addSlideEvents() {
        this.wrapper.addEventListener('mousedown', this.onStart);
        this.wrapper.addEventListener('touchstart', this.onStart);
        this.wrapper.addEventListener('mouseup', this.onEnd);
        this.wrapper.addEventListener('touchend', this.onEnd);
    }

    bindEvents() {
        this.onStart = this.onStart.bind(this);
        this.onMove = this.onMove.bind(this);
        this.onEnd = this.onEnd.bind(this);
    }

    // Configurações dos slides
    slidePosition(slide) {
        const margin = (this.wrapper.offsetWidth - slide.offsetWidth) / 2;
        return -(slide.offsetLeft - margin);
    }

    slidesConfig() {
        this.slideArray = [...this.slide.children].map((element) => {
            const position = this.slidePosition(element);
            return { position, element };
        });
    }

    slidesIndexNav(index) {
        const last = this.slideArray.length - 1;
        this.index = {
            prev: index ? index - 1 : undefined,
            active: index,
            next: index === last ? undefined : index + 1,
        };
    }

    changeSlide(index) {
        const activeSlide = this.slideArray[index];
        if (!activeSlide) {
            console.warn(`Slide com índice ${index} não encontrado.`);
            return;
        }
        this.moveSlide(activeSlide.position);
        this.slidesIndexNav(index);
        this.dist.finalPosition = activeSlide.position;
    }

    activePrevSlide() {
        if (this.index.prev !== undefined) this.changeSlide(this.index.prev);
    }

    activeNextSlide() {
        if (this.index.next !== undefined) this.changeSlide(this.index.next);
    }

    init() {
        if (!this.slide || !this.wrapper) return null;
        this.bindEvents();
        this.transition(true);
        this.addSlideEvents();
        this.slidesConfig();
        this.changeSlide(0); // inicia no primeiro slide
        return this;
    }
}

// Função para iniciar slides com segurança
function initSlide(classeSlide, classeWrapper, index = 0) {
    const slideElement = document.querySelector(classeSlide);
    const wrapperElement = document.querySelector(classeWrapper);

    if (slideElement && wrapperElement) {
        const slide = new Slide(classeSlide, classeWrapper);
        slide.init();
        slide.changeSlide(index);
    } else {
        console.warn(`Slide ${classeSlide} ou Wrapper ${classeWrapper} não encontrado.`);
    }
}

// Iniciar os slides (só se existirem)
initSlide('.slide', '.slide-wrapper', 0);
// initSlide('.slide2', '.slide-wrapper2', 0);
// initSlide('.slide3', '.slide-wrapper3', 0);
// initSlide('.slide4', '.slide-wrapper4', 0);
