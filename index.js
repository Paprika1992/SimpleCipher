const backgroundEl = document.getElementById('page-background');

import {preloader__show, preloader__hide} from "./preloader.js";

preloader__show()

setTimeout(function() {
    preloader__hide()
}, 500)

//Дешифровка текста
document.getElementById('content__decrypt-block__decrypt-btn').addEventListener('click', async function () {
    let decryptText = document.getElementById('decryptText').value,
        decryptSalt = document.getElementById('cipherSalt_decrypt').value ?? null,
        cipherKey = document.getElementById('cipher-key--decrypt').value

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
            cipherKey: cipherKey
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


function getCipherSalt () {
    return fetch('./api/createCipherSalt', {
        method: "GET",
    }).then( 
        promise => {
            console.log(promise.status)
            if (promise.status !== 200) {
                let errMsg = 'Произошла непредвиденная ошибка'
                return {err: errMsg + ": " + promise.headers.get('X-Error-Msg')} || true
            } else {
                return promise.json()
            }
            //Проверяем есть ли ошибка в выполнении ендпоинта
            //return {errMsg: promise.headers.get('X-Error-Msg')} || promise.json()
        }   
    )
}


//Генерация соли для шифра
document.getElementById('api-get-cipher-salt').addEventListener('click', async function(){
    let cipherSaltRqst = await getCipherSalt(),
        cipherSalt
    if (cipherSaltRqst.err) {
        alert(cipherSaltRqst.err)
        
        return;
    } else {
        cipherSalt = cipherSaltRqst.cipherSalt
    }

    document.getElementById('cipherSalt').value = cipherSalt;
})


//Получение соли к шифру
document.getElementById('getSalt').addEventListener('click', async function (event) {
    //Если клик происходит во время отсчета до исчезновения соли
    if (event.target.classList.contains('getSalt')) {
        return;
    }

    let cipherSaltRqst = await getCipherSalt(),
        cipherSalt
    if (cipherSaltRqst.err) {
        alert(cipherSaltRqst.err)
        
        return;
    } else {
        cipherSalt = cipherSaltRqst.cipherSalt
    }
   
    // console.log(cipherSalt)
    
    // let cipherSalt = null,
    //     response = await fetch('./api/createCipherSalt', {
    //     method: "GET",
    // });
    // let cipherSaltRqst = await response.json().catch(function(err){
    //     console.log(err)
    // })
    //     cipherSalt = cipherSaltRqst.cipherSalt
    
    // console.log(cipherSaltRqst)

    // if (response.status !== 200) {
    //     if (cipherSaltRqst.errMsg) {
    //         alert(cipherSaltRqst.errMsg)
    //     } else {
    //         alert('Непредвиденная ошибка');
    //     }
        
    //     //ГАВРИЛОВ
    //     //ОТПРАВЛЯЙ ЕНДПОИНТ НА ЛОГИРОВАНИЕ ИНФОРМАЦИИ ОБ ОШИБКЕ
    //     return;
    // }

    event.target.classList.add('getSalt');

    //Счетчик удаление соли к шифру
    let cipherSaltCount = 7;

    // document.getElementById('GetCipherSalt').innerHTML = 'Ваш секретный ключ - ' + cipherSalt
    document.getElementById('GetCipherSalt').classList.add('visible')
    document.getElementById('cipher-salt__text-block').innerHTML = cipherSalt;
    document.getElementById('salt-timer-block__text').innerHTML = 'Секретный ключ будет удален через <span>' + cipherSaltCount + '<span>';
    document.getElementById('salt-timer-block__timer').classList.add('saltCounterStart')

    let cipherSaltInterval = setInterval(function(){
        cipherSaltCount--;
        document.getElementById('salt-timer-block__text').innerHTML = 'Секретный ключ будет удален через <span>' + cipherSaltCount + '</span>';
    }, 1000)

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
document.getElementById('content__encrypt-block__encrypt-btn').addEventListener('click', async function () {
    let encryptText = document.getElementById('encryptText').value.trim(),
        encryptFakeLength = document.getElementById('cipherLength').value,
        resultCipherCount = document.getElementById('cipherCount').value || 1,
        encryptSalt = document.getElementById('cipherSalt').value || null,
        prevResulst = document.querySelectorAll('.content__encrypt-block__result__parent'),
        cipherKey = document.getElementById('cipherKey').value

    if (encryptFakeLength > 899) {
        alert('Максимальная желаемая длина шифра 899 символов')

        return;
    }

    if (resultCipherCount > 20) {
        alert('Максимальная количество итоговых шифров 20')
        
        return;
    }

    if (!encryptText.length) {
        alert('Пустой шифруемый текст')

        return;
    }


    console.log(encryptFakeLength);

    //Если есть предыдущие результаты шифрования - очищаем их
    if (prevResulst.length) {
        for (let encryptIndex = 0; encryptIndex < prevResulst.length; encryptIndex++){
            prevResulst[encryptIndex].classList.add('hide')
        }
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
                    cipherKey: cipherKey
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

//Навешиваем обработчик клика на иконку слайдера, "разворачивающего" соседний блок
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
    })
})

//Изменение значения в поле с шифром приводит к очистке блока с результатом, чтобы у пользователя не было ошибочного представления, что результат связан с другим шифром
document.getElementById('decryptText').addEventListener('input', function(el){
    clearDecryptText()
})


Array.from(document.querySelectorAll('.slider-arrow')).forEach( (el) => {
    el.addEventListener('click', () => {
        console.log(this)
        el.parentElement.classList.toggle('visible')
    })
})


//let getCipherKeyFields = Array.from(document.querySelectorAll('.cipher-key-field'));
/**
 * Получение ключа для шифра 
 */
document.getElementById('api-get-cipher-key').addEventListener('click', () => {
        fetch('./api/getCipherKey', {
            method: "GET"
        }).then(resultJson => {
            resultJson.json()
            .then(result => {
                console.log(result)
                document.getElementById('cipherKey').value = result.cipherKey;
                // getCipherKeyFields.forEach( field => {
                //     console.log(field)
                //     field.value = result.cipherKey
                // })
            }).catch( () => {
                console.log('net');
            })
        }).catch( () => {
            console.log('net');
        })
})

//#Гаврилов
//НАВЕСЬ ФУНКЦИИ-ОБРАБОТЧИКИ ПРЯМО В HTML ЭЛЕМЕНТЫ, НАПРИМЕР НА АТРИБУТ ONCHANGE  

const sliderCollection = Array.from(document.querySelectorAll('.content__input-block__block-slide'));

let encrpytSlidersCollection = document.querySelectorAll("#cipher-content__encrypt-block .content__input-block__block-slide"),
    decryptSlidersCollection = document.querySelectorAll("#cipher-content__decrypt-block .content__input-block__block-slide");

/**
 * Клик по слайдеру для разворачивания дополнительных блоков
 */
encrpytSlidersCollection.forEach(function(el, index) {
    el.addEventListener('click', slideBlock(index))   
})
/**
 * Клик по слайдеру для разворачивания дополнительных блоков
 */
decryptSlidersCollection.forEach(function(el, index) {
    el.addEventListener('click', slideBlock(index))   
})

//Разворачивание слайдера
function slideBlock(index) 
{
    //Возвращаем замыкание, в котором получаем параметр события клика (event) и, ориентируясь на него, раскрываем или скрываем такой же блок, но в соседнем разделе (если слайдер нашат в блоке шифровании - раскрываем тот же блок в разделе дешифрования и наоборот) 
    return function(event) {
        let siblingBlock = event.currentTarget.closest('#cipher-content__encrypt-block') ? decryptSlidersCollection : encrpytSlidersCollection
        let sliderParentBlock = event.currentTarget.parentElement;
        if (sliderParentBlock.classList.contains('visible')) {
            sliderParentBlock.classList.remove('visible')
            siblingBlock[index].parentElement.classList.remove('visible')
        } else {
            sliderParentBlock.classList.add('visible')
            siblingBlock[index].parentElement.classList.add('visible')
        }  
    }    
}
