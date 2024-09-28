export default class Header{
    
    constructor() {
        this._header = document.querySelector('.js-header');
        this._burger = document.querySelector('.js-header-burger');
        
        this.bindEvent();
    }
    
    bindEvent(){
        this._burger.addEventListener('click', () => {
            this._header.classList.toggle('is-open');
        })
    }
    
}