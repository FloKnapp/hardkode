(function() {

    let uploaderInitialized = false;

    /* Events */
    const onTriggerClick = () => {
        window.upload.show();
    };

    const uploadTrigger = document.querySelectorAll('data-upload');

    for (let trigger of uploadTrigger) {
        trigger.addEventListener('click', onTriggerClick);
    }

    const _private = {

    };

    const _events = {

        onDragEnter: (e) => {

            e.preventDefault();

            console.log('drag enter');

            if (e.target.classList && !e.target.classList.contains('active')) {
                e.target.classList.add('active');
            }

        },

        onDragLeave: (e) => {

            e.preventDefault();

            console.log("drag leave");

            if (e.target.classList && e.target.classList.contains('active')) {
                e.target.classList.remove('active');
            }

        },

        onDragOver: (e) => {
            e.preventDefault();
        },

        onDragDrop: (e) => {

            const dataTransfer = e.dataTransfer;

            e.preventDefault();

            const file = dataTransfer.items[0].getAsFile();

            window.uploader.showPreviewImage(file);

        },

        onUploaderClose: () => {

            uploader.close();
            console.log('Closing uploader.');

        },

        onUploadAreaClick: (e) => {

            console.log("click");

            const input = document.createElement('input');
            input.type = 'file';
            input.style.visibility = 'hidden';

            document.body.appendChild(input);

            input.click();

            input.addEventListener('change', (e) => {
                return uploader.showPreviewImage(input.files[0]);
            });

        }

    };

    /* Public scope */
    window.uploader = {

        showPreviewImage: (file) => {

            const uploadArea = document.getElementById('uploader').querySelector('.upload-area');
            uploadArea.style.display = 'none';

            const fileReader = new FileReader();
            const img        = document.createElement('img');

            fileReader.onload = (e) => {
                img.src = e.target.result.toString();
                uploadArea.parentNode.insertBefore(img, uploadArea);
            }

            fileReader.readAsDataURL(file);

        },

        toggleUploadButtonStatus: () => {



        },

        close: () => {

            document.body.classList.remove('blur');

            const uploader = document.getElementById('uploader');

            if (null !== uploader) {
                uploader.parentNode.removeChild(uploader);
            }

            window.removeEventListener('click', onOutsideClick);

        },

        show: () => {

            const uploaderExists = document.getElementById('uploader');

            if (uploaderExists) {
                console.log('Uploader is already shown. Do nothing...');
                return;
            }

            console.log("Showing uploader...");

            const css = `
                body > *:not(#uploader) {
                    transition: filter 0.3s;
                }
                
                body.blur > *:not(#uploader) {
                    filter: blur(2px);
                    pointer-events: none;
                    user-select: none;
                }
                #uploader {
                    min-width: 460px;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: #202020;
                    padding: 30px 40px;
                    box-sizing: border-box;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.6);
                    filter: none;
                }
                
                #uploader .title {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin: 0;
                    padding: 0;
                }
                
                #uploader .close {
                    position: relative;
                    cursor: pointer;
                    display: block;
                    padding: 5px;
                    padding-right: 0;
                }
                
                #uploader .close:hover:before {
                    color: #53aa78;
                }
                
                #uploader .close:before {
                    content: '\\ea0f';
                    font-family: 'icomoon', sans-serif;
                    font-size: 0.9rem;
                    position: relative;
                }
                
                #uploader h3 {
                    margin: 0;
                    font-size: 1.4rem;
                }
                
                #uploader img {
                    width: 100%;
                    max-width: 420px;
                }
                
                .upload-area {
                    border: 1px dashed #505050;
                    position: relative;
                    width: 100%;
                    height: 120px;
                }
                
                .upload-area.active {
                    background-color: #101010;
                }
                
                .upload-area:before {
                    content: "\\e9c8";
                    color: #505050;
                    font-size: 1rem;
                    font-family: "icomoon", sans-serif;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
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

            document.body.classList.add('blur');

            const template = `
                <div id="uploader">
                    <div class="title">
                        <h3>Upload</h3>
                        <span class="close"></span>
                    </div>
                    <hr />
                    <div class="upload-area"></div>
                    <hr />
                    <button>Upload</button>
                </div>
            `;

            const templateElem = (new DOMParser()).parseFromString(template, 'text/html').getElementById('uploader');

            const uploadArea = templateElem.querySelector('.upload-area');
            const closeButton = templateElem.querySelector('.close');

            uploadArea.addEventListener('click', _events.onUploadAreaClick);

            uploadArea.addEventListener('dragover', _events.onDragOver);
            uploadArea.addEventListener('dragenter', _events.onDragEnter);
            uploadArea.addEventListener('dragleave', _events.onDragLeave);
            uploadArea.addEventListener('drop', _events.onDragDrop);
            closeButton.addEventListener('click', _events.onUploaderClose);
            //document.body.addEventListener('click', onOutsideClick);

            document.body.appendChild(templateElem);

            setTimeout(function() {
                uploaderInitialized = true;
            }, 300);
        }

    };

    const onOutsideClick = (e) => {

        if (uploaderInitialized && !e.target.closest('#uploader')) {
            console.log('Closing uploader.');
            //window.uploader.close();
        }

    };

})();