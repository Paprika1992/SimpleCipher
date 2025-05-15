document.getElementById('content__decrypt-block__input__decrypt').addEventListener('click', async function () {
    let decryptText = document.getElementById('decryptText').value,
        decryptSalt = null

    if (!decryptText.length) {
        alert('пусто')
        return;
    }

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
    let decryptResponse = await response.json(),
        decryptResultBlock = document.getElementById('content__decrypt-block__result')

    if (response.status === 200) {
        console.log(decryptResponse);
        decryptResultBlock.innerHTML = '';
        let childDecryptBlock = document.createElement('div');
            childDecryptBlock.classList.add('content__block__result__text')
            childDecryptBlock.innerHTML = decryptResponse.decryptText;
            decryptResultBlock.appendChild(childDecryptBlock)
 
    }

})

// document.querySelectorAll('content__encrypt-block__result__parent__call-decrypt').addEventListener('click', function(){
//     console.log(this.previousSibling.textContent)
// })

//Клик по кнопке расшифровать напротив каждого результата шифрования
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('content__encrypt-block__result__parent__call-decrypt')) {
    let encryptText = event.target.previousSibling.textContent,
        //ВЫНЕСИ В ВЕРХ СКРИПТА ПЕРЕМЕННА VAR
        decryptTextInput = document.getElementById('decryptText');
    decryptTextInput.value = encryptText;
    document.getElementById('content__decrypt-block__input__decrypt').click()
  }
});

document.getElementById('content__encrypt-block__input__encrypt').addEventListener('click', async function () {
    let encryptText = document.getElementById('encryptText').value,
        encryptFakeLength = document.getElementById('cipherLength').value,
        resultCipherCount = document.getElementById('cipherCount').value,
        encryptSalt = null
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
        console.log(encryptResponse);
        encryptResultBlock.innerHTML = '';
        encryptResponse.cipherArr.forEach(encryptText => {
            let childEncryptBlock = document.createElement('div'),
                encryptTextBlock = document.createElement('div'),
                callDecryptButton = document.createElement('button');
            childEncryptBlock.classList.add('content__encrypt-block__result__parent')
            encryptTextBlock.classList.add('content__block__result__text')
            callDecryptButton.classList.add('content__encrypt-block__result__parent__call-decrypt')
            callDecryptButton.setAttribute('type', 'button');
            encryptTextBlock.innerHTML = encryptText
            callDecryptButton.innerHTML = "Расшифровать"
            childEncryptBlock.appendChild(encryptTextBlock);
            childEncryptBlock.appendChild(callDecryptButton);
            console.log(childEncryptBlock)
            encryptResultBlock.appendChild(childEncryptBlock)
        });
 
    }
})