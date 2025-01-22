document.addEventListener('DOMContentLoaded', function() {
    initializeAccordions();
    initializeSubButtons();
    initializeLoadButton();
});

function initializeAccordions() {
    document.querySelectorAll('.accordion').forEach(addAccordionClickListener);

    document.querySelectorAll('.accordion-content').forEach(function(content) {
        content.style.display = 'none';
    });

    function addAccordionClickListener(accordion) {
        accordion.addEventListener('click', function() {
            const isActive = this.classList.toggle('active');
            const content = this.nextElementSibling;
            toggleAccordion(content, isActive);
        });
    }
}

function toggleAccordion(content, isActive) {
    if (isActive) {
        content.style.display = 'block';
    } else {
        content.style.display = 'none';
        const loadedDataContainer = document.getElementById('data-container');
        if (loadedDataContainer) {
            loadedDataContainer.innerHTML = '';
        }
    }
}

function loadPhpFile(phpFile, container) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                container.innerHTML = xhr.responseText;
                container.style.display = 'block';
            } else {
                console.error('An error occurred while fetching PHP file:', xhr.status);
            }
        }
    };
    xhr.open('GET', phpFile, true);
    xhr.send();
}

function initializeSubButtons() {
    document.querySelectorAll('.sub-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const phpFile = this.getAttribute('data-action');
            const container = document.getElementById('data-container');
            loadPhpFile(phpFile, container);
        });
    });
}

function initializeLoadButton() {
    document.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'loadBtn') {
            const tableElement = document.getElementById('table');
            const tableName = tableElement ? tableElement.getAttribute('data-value') : '';
            const adminId = document.getElementById('Id').value;
            console.log(`Table: ${tableName}, ID: ${adminId}`);  // デバッグ用
            loadData(tableName, adminId);
        }
    });
}


function loadData(tableName, id) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // レスポンスのJSONデータをパースしてオブジェクトに変換
                const response = JSON.parse(xhr.responseText);
                console.log(`Response: ${JSON.stringify(response)}`);  // デバッグ用
                
                // 各カラム名を使って、対応する要素に値を代入する
                Object.keys(response).forEach(column => {
                    const value = response[column];
                    const element = document.getElementById(column);
                    if (element) {
                        // インプット要素に値を設定する場合
                        if (element.tagName.toLowerCase() === 'input' || element.tagName.toLowerCase() === 'textarea') {
                            if (element.type === 'file') {
                                // ファイル入力フィールドの場合はリセット
                                const newFileInput = document.createElement('input');
                                newFileInput.type = 'file';
                                newFileInput.id = element.id;
                                newFileInput.name = element.name;
                                newFileInput.accept = element.accept;
                                newFileInput.required = element.required;
                                element.parentNode.replaceChild(newFileInput, element);
                            } else {
                                element.value = value;
                            }
                        } else {
                            // それ以外の要素に値を設定する場合（例：div要素など）
                            element.textContent = value;
                        }
                    }
                });
            } else {
                console.error('An error occurred while fetching data:', xhr.status);
            }
        }
    };
    xhr.open('POST', 'load.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    const data = `tableName=${tableName}&id=${id}`;
    console.log(`Sending data: ${data}`);  // デバッグ用
    xhr.send(data);
}
