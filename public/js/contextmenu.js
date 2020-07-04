(() => {

    let override = false;
    let currentClientPosition = {
        x: 0,
        y: 0
    };

    const customContextMenu = {

        show: () => {

            const contextMenuExists = document.getElementById('contextmenu');

            if (contextMenuExists) {
                customContextMenu.close();
            }

            const css = `
                #contextmenu {
                    position: absolute;
                    top: 0;
                    left: 0;
                    background-color: rgba(0,0,0,0.8);
                    min-width: 180px;
                    transform: scale(0);
                    transition: transform 0.1s;
                }
                #contextmenu a {
                    display: block;
                    padding: 10px 15px;
                    font-size: 0.8rem;
                    cursor: pointer;
                }
                #contextmenu a:hover {
                    background-color: rgba(0,0,0,0.6);
                }
                #contextmenu .title {
                    padding: 10px;
                    background-color: rgba(255,255,255,0.1);
                    font-size: 0.7rem;
                    font-weight: 300;
                }
                #contextmenu.active {
                    transform: scale(1);
                }
            `;

            const rules = css.split("}\n").filter((item) => {
                return item.trim().length;
            });

            const stylesheetElem = document.createElement('style');
            document.head.appendChild(stylesheetElem);

            const stylesheet = stylesheetElem.sheet;

            rules.forEach((rule) => {
                stylesheet.insertRule(rule);
            });

            const items = customContextMenu.getItems();

            const renderedItems = items.map((item) => {

                let link = document.createElement('a');

                if (null !== item.href) {
                    link.href = item.href;
                }

                if (null !== item.action) {
                    link.addEventListener('click', item.action);
                    link.addEventListener('click', (e) => {
                        customContextMenu.close();
                    });
                }

                link.innerText = item.title;

                return link;

            });

            const template = `
                <div id="contextmenu">
                    <div class="title">Hardkontext</div>
                </div>
            `;

            const tpl = (new DOMParser()).parseFromString(template, 'text/html').getElementById('contextmenu');

            renderedItems.forEach((item) => {
                tpl.appendChild(item);
            });

            if (currentClientPosition.x === 0 && currentClientPosition.y === 0) {
                console.log('Try to correct invalid position.');
                return setTimeout(customContextMenu.show, 50);
            }

            tpl.style.left = currentClientPosition.x + 'px';
            tpl.style.top = currentClientPosition.y + 'px';

            document.body.appendChild(tpl);

            const contextWidth = tpl.offsetWidth;
            const contextHeight = tpl.offsetHeight;

            if (currentClientPosition.x + contextWidth > window.innerWidth) {
                tpl.style.left = currentClientPosition.x - contextWidth + 'px';
            }

            if (currentClientPosition.y + contextHeight > window.innerHeight) {
                tpl.style.top = currentClientPosition.y - contextHeight + 'px';
            }

            setTimeout(() => { tpl.classList.add('active');}, 50);

        },

        close: () => {

            const contextmenu = document.getElementById('contextmenu');

            if (null !== contextmenu) {

                contextmenu.classList.remove('active');

                function removeChild() {
                    contextmenu.parentNode.removeChild(contextmenu);
                    contextmenu.removeEventListener('transitionend', removeChild);
                }

                contextmenu.addEventListener('transitionend', removeChild);

                console.log('Closing contextmenu.');

            }
        },

        getItems: () => {

            return [
                {
                    name: 'image-upload',
                    title: 'Bild hochladen',
                    href: null,
                    action: window.uploader.show
                },
                {
                    name: 'show-native-debugger',
                    title: 'Seite untersuchen',
                    href: null,
                    action: () => {
                        console.log("Seite untersuchen");
                    }
                }
            ];

        }

    };

    window.addEventListener('keydown', (e) => {

        if (e.ctrlKey) {
            override = true;
            console.log('Override activated...');
        }

    });

    window.addEventListener('keyup', (e) => {

        if (e.key === 'Control' && override) {
            override = false;
            console.log("Override deactivated.");
        }

    });

    window.addEventListener('mousemove', (e) => {
        currentClientPosition.x = e.clientX;
        currentClientPosition.y = e.clientY;
    });

    window.addEventListener('contextmenu', (e) => {

        if (override) {
            console.log('Native contextmenu activated...');
            override = false;
            console.log('Override deactivated.')
            return false;
        }

        e.preventDefault();
        e.stopPropagation();

        if (e.target.id === 'contextmenu' || e.target.closest('#contextmenu')) {
            return false;
        }

        customContextMenu.show();

        console.log("Showing custom contextmenu...");

    });

    window.addEventListener('click', (e) => {

        if (e.target.id === 'contextmenu' || e.target.closest('#contextmenu')) {
            return false;
        }

        customContextMenu.close();

    });

    const initialMouseEvent = (e) => {
        currentClientPosition.x = e.clientX;
        currentClientPosition.y = e.clientY;
        window.removeEventListener('mouseover', initialMouseEvent);
        window.removeEventListener('mouseenter', initialMouseEvent);
    };

    window.addEventListener('mouseenter', initialMouseEvent);
    window.addEventListener('mouseover', initialMouseEvent);

})();