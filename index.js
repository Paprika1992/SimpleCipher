const backgroundEl = document.getElementById('page-background');

document.getElementById('content__decrypt-block__input__decrypt').addEventListener('click', async function () {
    let decryptText = document.getElementById('decryptText').value,
        decryptSalt = document.getElementById('cipherSalt_decrypt').value ?? null;

    if (!decryptText.length) {
        alert('пусто')
        return;
    }

    let decryptResultBlock = document.getElementById('content__decrypt-block__result')
    decryptResultBlock.textContent = '';

    let response = await fetch('./CipherController.php', {
        method: "POST",
        body: JSON.stringify({
            decryptText: decryptText,
            cipherSalt: decryptSalt,
            action: 'decrypt'
        }),
        headers: {
            'content-type': 'application/json'
        }
    });
    let decryptResponse = await response.json()

    if (response.status === 200) {
        console.log(decryptResponse);
        let childDecryptBlock = document.createElement('div');
            childDecryptBlock.classList.add('content__block__result__text')
            childDecryptBlock.textContent = decryptResponse.decryptText;
            decryptResultBlock.appendChild(childDecryptBlock)
    }
})


//Получение соли к шифру
document.getElementById('getSalt').addEventListener('click', async function () {
    let cipherSalt = null;

    let response = await fetch('./CipherController.php', {
        method: "POST",
        body: JSON.stringify({
            action: 'getSalt'
        }),
        headers: {
            'content-type': 'application/json'
        }
    });
    let cipherSaltRqst = await response.json()
        cipherSalt = cipherSaltRqst.cipherSalt

    document.getElementById('GetCipherSalt').innerHTML = 'Ваш секретный ключ - ' + cipherSalt
})


//ПРИ КЛИКЕ ПО КНОПКЕ "РАСШИФРОВАТЬ" НЕМНОГО ВЫДВИГАТЬ И ПОДСВЕЧИВАТЬ ВЫБРАННЫЙ ЭЛЕМЕНТ  

//Клик по кнопке расшифровать напротив каждого результата шифрования
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('content__encrypt-block__result__parent__call-decrypt')) {
    let encryptText = event.target.nextSibling.textContent,
        //ВЫНЕСИ В ВЕРХ СКРИПТА ПЕРЕМЕННА VAR
        decryptTextInput = document.getElementById('decryptText');
    decryptTextInput.value = encryptText;
    //Не запускаем сразу дешифрование, так как возможно нужно ввести соль
    //document.getElementById('content__decrypt-block__input__decrypt').click()
  }
});


//Шифрование текста
document.getElementById('content__encrypt-block__input__encrypt').addEventListener('click', async function () {
    let encryptText = document.getElementById('encryptText').value,
        encryptFakeLength = document.getElementById('cipherLength').value,
        resultCipherCount = document.getElementById('cipherCount').value,
        encryptSalt = document.getElementById('cipherSalt').value ?? null,
        prevResulst = document.querySelectorAll('.content__encrypt-block__result__parent');

    //Если есть предыдущие результаты шифрования - очищаем их
    if (prevResulst.length) {
        for (let encryptIndex = 0; encryptIndex < prevResulst.length; encryptIndex++){
            prevResulst[encryptIndex].classList.add('hide')
        }
    }

    if (!encryptText.length) {
        alert('пусто')
        return;
    }
    
    let response = await fetch('./CipherController.php', {
        method: "POST",
        body: JSON.stringify({
            encryptText: encryptText,
            fakeLength: encryptFakeLength,
            cipherCount: resultCipherCount,
            cipherSalt: encryptSalt,
            action: 'encrypt'
        }),
        headers: {
            'content-type': 'application/json'
        }
    });
    let encryptResponse = await response.json(),
        encryptResultBlock = document.getElementById('content__encrypt-block__result')
    if (response.status === 200) {
        setTimeout(() => {
            // console.log(encryptResponse);
            encryptResultBlock.textContent = '';
            encryptResponse.cipherArr.forEach((encryptText, index) => {
                //console.log(encryptText)
                let childEncryptBlock = document.createElement('div'),
                    encryptTextBlock = document.createElement('div'),
                    callDecryptButton = document.createElement('button');
                childEncryptBlock.classList.add('content__encrypt-block__result__parent')
                encryptTextBlock.classList.add('content__block__result__text')
                callDecryptButton.classList.add('content__encrypt-block__result__parent__call-decrypt')
                callDecryptButton.setAttribute('type', 'button');
                encryptTextBlock.textContent = encryptText
                callDecryptButton.textContent = "Расшифровать"
                childEncryptBlock.appendChild(callDecryptButton);
                childEncryptBlock.appendChild(encryptTextBlock);
                
                //console.log(childEncryptBlock)
                
                setTimeout(() => {
                    encryptResultBlock.appendChild(childEncryptBlock)
                }, 100 * index);
            });
        }, 500);
        
 
    }
})

const navigationElem = document.querySelectorAll('.navigation__block__title');

navigationElem.forEach((elem) => {
    elem.addEventListener('click', () => {
        let showClass = elem.parentElement.classList.contains('show');
        navigationElem.forEach((allElem) => {
            allElem.parentElement.classList.remove('show')
        })

        if (!showClass) {
            elem.parentElement.classList.add('show')
            backgroundEl.classList.add('visible')
        } else {
            backgroundEl.classList.remove('visible')
        }
        console.log(elem.parentElement.classList.contains('click'))
       // elem.parentElement.nextElementSibling.classList.remove('click')
        //elem.parentElement.previousElementSibling.classList.remove('click')
        
    })
})
