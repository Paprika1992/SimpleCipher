const backgroundEl = document.getElementById('page-background');

import {preloader__show, preloader__hide} from "./preloader.js";

preloader__show()

setTimeout(function() {
    preloader__hide()
}, 500)

//Дешифровка текста
document.getElementById('content__decrypt-block__input__decrypt').addEventListener('click', async function () {
    let decryptText = document.getElementById('decryptText').value,
        decryptSalt = document.getElementById('cipherSalt_decrypt').value ?? null;

    if (!decryptText.length) {
        alert('пусто')
        return;
    }
    setTimeout(() => {
        preloader__show()
    }, 300);
    
    let decryptResultBlock = document.getElementById('content__decrypt-block__result')
    // decryptResultBlock.textContent = '';

    clearDecryptText();

    let response = await fetch('./api/getDecryptText', {
        method: "POST",
        body: JSON.stringify({
            text: decryptText,
            cipherSalt: decryptSalt,
            // action: 'decrypt'
        }),
        headers: {
            'content-type': 'application/json'
        }
    });
    // let decryptResponse = await response.json()

    
    /**
     * Массив с ошибками запроса дешифрования
     * @type array
     */
    let decryptErrMsgArr = []
    let decryptResponse = await response.json()
    .then( result => {
        return result;
    }).catch( errMsg => {
        decryptErrMsgArr.push('Ошибка парсинга: ' + errMsg)
    }).finally( () => {
        setTimeout( () => {
            preloader__hide()
        }, 1000)
    })
    
    //ОШИБКА РОУТИНГА
    // if (decryptResponse.errorMsg) {
    //     decryptErrMsgArr.push('Ошибка обращения к серверу: ' + decryptResponse.errorMsg)

    //     return; 
    // }
    //ВОЗВРАЩЕННЫЙ КОД ОШИБКИ
    if (!response.ok) {
        // if (decryptResponse.errMsg) {
        //     decryptErrMsgArr.push(decryptResponse.errMsg)
        // } else {
            //Если код ошибки не успешный, при этом передается кастомный текст ошибки
            if (response.headers.get('X-Error-Msg')) {
                decryptErrMsgArr.push(response.headers.get('X-Error-Msg'))
            } else {
                decryptErrMsgArr.push('Код ошибки: ' + decryptResponse.status)
            }
        //}
            
            //decryptErrMsgArr.push('Код ошибки: ' + decryptResponse.status)
            console.log(decryptErrMsgArr)

            return;
    } else {
       if (response.headers.get('X-Error-Msg')) {
            decryptErrMsgArr.push(response.headers.get('X-Error-Msg'))
        } 
    }

    console.log(decryptErrMsgArr)
    
    //#Гаврилов
    //ПРОВЕРЯЙ ВОЗВРАЩЕНИЕ ТЕКСТА ОШИБКИ ПОСЛЕ КАСТОМНОЙ ПРОВЕКИ НА ШИФРОВАНИИ И НА ДЕШИФРОВАНИИ, ПОКА ЧТО НА ШИФРОВАНИИ ТЫ ПРОВЕРЯЕШЬ ТОЛЬКО СТАТУСЫ 400 500, ОШИБКИ ПАРСИНГА JSON И ОШИБКИ ОБРАЩЕНИЯ К СЕРВЕРУ  

    //ГАВРИЛОВ
    //ПОДУМАТЬ НА КАКОЙ КОД ОРИЕНТИРОВАТЬСЯ, ЧТОБЫ ПРОДОЛЖАТЬ ИСПОЛНЕНИЕ СКРИПТА (В ОСТАЛЬНЫХ СЛУЧАЯХ ДОЛЖНЫ ЧТО-ТО ПОКАЗЫВАТЬ,Я ОШИБКУ КАКУЮ-ТО)
    // if (response.status !== 404) {
        // console.log(decryptResponse);
        setTimeout( () => {
            let childEncryptBlock = document.createElement('div'),
                    encryptTextBlock = document.createElement('div');
                childEncryptBlock.classList.add('result-text-block');
                childEncryptBlock.setAttribute('id', 'content__decrypt-block__result__parent-block');
                encryptTextBlock.classList.add('content__block__result__text')
                encryptTextBlock.setAttribute('id', 'decrypt-result-text');
                encryptTextBlock.textContent = decryptResponse.decryptText
                childEncryptBlock.appendChild(encryptTextBlock);
                decryptResultBlock.appendChild(childEncryptBlock);
        }, 1100)
    //} 
    // else {

    // }
})


document.getElementById('page-background').addEventListener('click', function() {
    this.classList.remove('visible')
    document.querySelector('.navigation__block.show').classList.remove('show')
})


//Получение соли к шифру
document.getElementById('getSalt').addEventListener('click', async function (event) {
    if (event.target.classList.contains('getSalt')) {
        return;
    }
    let cipherSalt = null,
        response = await fetch('./api/createCipherSalt', {
        method: "GET",
        // body: JSON.stringify({
        //     action: 'getSalt'
        // }),
        // headers: {
        //     'content-type': 'application/json'
        // }
    });
    let cipherSaltRqst = await response.json().catch(function(err){
        console.log(err)
    })
        cipherSalt = cipherSaltRqst.cipherSalt
    
    console.log(cipherSaltRqst)

    if (response.status !== 200) {
        if (cipherSaltRqst.errMsg) {
            alert(cipherSaltRqst.errMsg)
        } else {
            alert('Непредвиденная ошибка');
        }
        
        //ГАВРИЛОВ
        //ОТПРАВЛЯЙ ЕНДПОИНТ НА ЛОГИРОВАНИЕ ИНФОРМАЦИИ ОБ ОШИБКЕ
        return;
    }

    event.target.classList.add('getSalt');

    //Счетчик удаление соли к шифру
    let cipherSaltCount = 5;

    // document.getElementById('GetCipherSalt').innerHTML = 'Ваш секретный ключ - ' + cipherSalt
    document.getElementById('GetCipherSalt').classList.add('visible')
    document.getElementById('cipher-salt__text-block').innerHTML = cipherSalt;
    document.getElementById('salt-timer-block__text').innerHTML = 'Секретный ключ будет удален через <span>' + cipherSaltCount + '<span>';
    document.getElementById('salt-timer-block__timer').classList.add('saltCounterStart')

    let cipherSaltInterval = setInterval(function(){
        cipherSaltCount--;
        document.getElementById('salt-timer-block__text').innerHTML = 'Секретный ключ будет удален через <span>' + cipherSaltCount + '</span>';
    }, 1000)
    //ГАВРИЛОВ  
    //ПОКА ИДЕТ ОТСЧЕТ ПО УДАЛЕНИЮ СГЕНЕРИРОВАННОГО СЕКРЕТНОГО КЛЮЧА НАЖАТИЕ ПО КНОПКИ "ПОЛУЧИТЬ КЛЮЧ" НИ К ЧЕМУ НЕ ПРИВОДИТ

    setTimeout(function(){
        // document.getElementById('GetCipherSalt').innerHTML = "";
        document.getElementById('GetCipherSalt').classList.remove('visible');
        document.getElementById('cipher-salt__text-block').innerHTML = "";
        document.getElementById('salt-timer-block__text').innerHTML = "";
        event.target.classList.remove('getSalt');
        document.getElementById('salt-timer-block__timer').classList.remove('saltCounterStart')
        clearInterval(cipherSaltInterval);
    }, 5000)
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

        clearDecryptText();

  }
    //prevResulst = document.querySelector('.content__decrypt-block__result__parent');
});


function clearDecryptText()
{
    let decryptTextBlock = document.getElementById('content__decrypt-block__result__parent-block');
    if (decryptTextBlock) {
        decryptTextBlock.classList.add('hide')
    
        setTimeout(() => {  
            decryptTextBlock.remove()
        }, 200);
    }   
}


//Шифрование текста
document.getElementById('content__encrypt-block__input__encrypt').addEventListener('click', async function () {
    let encryptText = document.getElementById('encryptText').value,
        encryptFakeLength = document.getElementById('cipherLength').value ?? 50,
        resultCipherCount = document.getElementById('cipherCount').value ?? 1,
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

    preloader__show()

    let encryptResponseArr = [];
    let response;

    // new Promise(function(resolve, reject){
        for (let index = 1; index <= resultCipherCount; index++) {
            encryptResponseArr.push(fetch('./api/getEncryptText', {
                method: "POST",
                body: JSON.stringify({
                    text: encryptText,
                    fakeLength: encryptFakeLength,
                    cipherSalt: encryptSalt,
                    //action: 'encrypt'
                }),
                headers: {
                    'content-type': 'application/json'
                }
            }))
        }
    // }) 

    let encryptPromisesArr = [];
    let encryptErrArr = [];
    encryptPromisesArr = await Promise.allSettled(encryptResponseArr).then(encryptPromises => {
        let interimArr = [];
        encryptPromises.forEach(function(encryptPromise) {
            //Ошибка получения данных по результатам работы fetch
            if (encryptPromise.status == 'fulfilled') {
                //Проверяем код ответа
                if (encryptPromise.value.ok) {
                        let jsonResponse = encryptPromise.value.json()
                        .then(encryptResult => {
                            return encryptResult
                        })
                        //Ловим ошибки парсинга JSON
                        .catch(jsonErr => {
                            encryptErrArr.push('Ошибка парсинга: ' + jsonErr)

                            return null;
                        })
                        interimArr.push(jsonResponse)
                } else {
                    //Если код ошибки не успешный, при этом передается кастомный текст ошибки
                    if (encryptPromise.value.headers.get('X-Error-Msg')) {
                        encryptErrArr.push(encryptPromise.value.headers.get('X-Error-Msg'))
                    } else {
                        encryptErrArr.push('Код ошибки: ' + encryptPromise.value.status)
                    }
                }
            } else if (encryptPromise.status == 'rejected') {
                encryptErrArr.push('Ошибка получения данных: ' + encryptPromise.reason)
            }
        })
        return Promise.all(interimArr).then(resultes => {return resultes;})
    }).finally( () => {
        setTimeout( () => {
            preloader__hide()
        }, 500)
    })

    //ЕСЛИ МАССИВ С ОШИБКАМИ НЕ ПУСТОЙ ВЫВОДИ СООБЩЕНИЕ ПОЛЬЗОВАТЕЛЮ, А ТАКЖЕ ОБРАЙЩАЙСЯ К ЕНДПОИНТУ С ЗАПИСЬЮ ИНФОРМАЦИИ ОБ ОШИБКЕ
    let encryptResultBlock = document.getElementById('content__encrypt-block__result')
    // encryptPromisesArr.forEach(elem => {
        setTimeout(() => {
            // console.log(encryptResponse)
            // console.log(encryptResponse.encryptText);
            encryptResultBlock.textContent = '';
            encryptPromisesArr.forEach((encryptText, index) => {
                //console.log(encryptText)
                let childEncryptBlock = document.createElement('div'),
                    encryptTextBlock = document.createElement('div'),
                    callDecryptButton = document.createElement('button');
                childEncryptBlock.classList.add('content__encrypt-block__result__parent', 'result-text-block')
                // childEncryptBlock.classList.add('result-text-field')
                encryptTextBlock.classList.add('content__block__result__text')
                callDecryptButton.classList.add('content__encrypt-block__result__parent__call-decrypt')
                callDecryptButton.setAttribute('type', 'button');
                encryptTextBlock.textContent = encryptText.encryptText
                callDecryptButton.textContent = "Расшифровать"
                childEncryptBlock.appendChild(callDecryptButton);
                childEncryptBlock.appendChild(encryptTextBlock);
                
                //console.log(childEncryptBlock)
                
                setTimeout(() => {
                    encryptResultBlock.appendChild(childEncryptBlock)
                }, 100 * index);
            });
        }, 500);
    // })

        encryptErrArr = [...new Set(encryptErrArr)];
        console.log(encryptErrArr)
        // console.log(encryptPromisesArr)
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

document.getElementById('decryptText').addEventListener('input', function(el){
    clearDecryptText()
})
