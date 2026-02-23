if (import.meta.hot) {
    // Prevent full reload, for JS, see https://github.com/vitejs/vite/issues/5763#issuecomment-1974235806
    import.meta.hot.on('vite:beforeFullReload', (payload) => {
        payload.path = "(WORKAROUND).html";
    });
}

import './modules/app-utils';


window.addEventListener('load', () => {
    document.body.classList.add('css-animation-ready'); // Wait for the DOM to be fully loaded (with fonts), to run animations (wrong width if font not loaded for example)
})

app.domReady(() => {
    

    const componentsMap = {
    }
    

    function initComponents(){
        Object.entries(componentsMap).forEach(([selector, Component]) => {
            const elements = document.querySelectorAll(selector);
            if (elements.length) {
                [...elements].forEach(el => {
                    el.instance = new Component(el);
                });
            }
        });
    }
    
    initComponents();
    
    
});