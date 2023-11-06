( () => {
    
    var copy2clipboard = (text) => {
        navigator.clipboard.writeText(text);
    };

    document.querySelectorAll(".copy-content").forEach((el,i) => {
        el.addEventListener('click', () => {
            console.log(document.querySelector('.content').value);
            copy2clipboard(document.querySelector('.content').value);
        });
    });
    

    document.querySelectorAll(".copy-link").forEach((el,i)=> {
        el.addEventListener('click', () => {
            console.log(document.querySelector('.link').innerHTML);
            copy2clipboard(document.querySelector('.link').innerHTML);
        });
    });

})();