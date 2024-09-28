import './modules/app-utils';
import Collapse from 'bootstrap/js/dist/collapse' // No need to init

import 'vidstack/player'; 
import 'vidstack/player/layouts/default'; 
import 'vidstack/player/ui';

import Header from "./modules/header.js";

window.addEventListener('load', () => {
    document.body.classList.add('css-animation-ready'); // Wait for the DOM to be fully loaded (with fonts), to run animations (wrong width if font not loaded for example)
})

app.domReady(() => {

    const els = {
        // _flexibleSlider: document.querySelectorAll('.js-flexible-slider'),
    };
    
    new Header();
    
    // if(els._flexibleSlider.length){
    //     [...els._flexibleSlider].map((el) => {
    //         new FlexibleSlider(el);
    //     });
    // }
    
});