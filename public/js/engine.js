(function() {

    document.querySelectorAll('input[data-error]').forEach((item) => {

        let dimmerActive = false;

        const fields = {
            dimmer: null,
            errorMessage: ''
        };

        var events = {
            onTransitionEnd: function() {

                fields.dimmer.removeEventListener('transitionend', events.onTransitionEnd);
                document.body.removeChild(fields.dimmer);

                document.querySelectorAll('.error-message-dynamic').forEach(function(item) {
                    item.parentNode.removeChild(item);
                });

            }
        };

        item.addEventListener('mouseenter', function() {

            dimmerActive = true;

            const message = `
                <div class="error-message-dynamic"><span>${item.dataset.error}</span></div>
            `;

            const domMessage = new DOMParser();
            const errorMessage = domMessage.parseFromString(message, 'text/html').querySelector('div');

            fields.errorMessage = errorMessage;

            errorMessage.style.top = item.offsetTop + item.offsetHeight + 1 + 'px';

            const dimmer = `
                <div id="dimmer"></div>
            `;

            const domDimmer = new DOMParser();
            const dimmerOverlay = domDimmer.parseFromString(dimmer, 'text/html').getElementById('dimmer');

            fields.dimmer = dimmerOverlay;

            document.body.appendChild(dimmerOverlay);

            setTimeout(function() {
                dimmerOverlay.classList.add('active');
            }, 1);

            this.parentNode.appendChild(errorMessage);

            this.classList.add('focus');
            this.style.cursor = 'help';

            const errorMessageHeight = errorMessage.getBoundingClientRect().height;
            errorMessage.style.height = '0';

            setTimeout(function() {
                errorMessage.style.height = errorMessageHeight + 'px';
            },1);

        });

        item.addEventListener('mouseleave', function() {

            dimmerActive = false;

            let self = this;
            self.classList.remove('focus');

            setTimeout(function() {

                if (!dimmerActive) {

                    fields.dimmer.classList.remove('active');
                    self.parentNode.querySelector('.error-message-dynamic').style.height = '0';

                    fields.dimmer.addEventListener('transitionend', events.onTransitionEnd.bind(self));

                }

            }, 100);

        });

    });

})();