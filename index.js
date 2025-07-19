import {preloader__show, preloader__hide} from "./preloader.js";

/**
 * Массив с рандомными символами, которые будут проскакивать в анимации
 */
const AppDescriptionRandomSymbols = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'z', 'y', 'x', 'w', 'v', 'u', 't', 's', 'r', 'p', 'q', 'o', 'n', 'm', 'l', 'k', 'j', 'i','h', 'g', 'f', 'e', 'd', 'c', 'b', 'a', '*', '=', '№', '⇔', '{', '}', '^', '-', '=', '~', '@', '#', '_', ' '],
    /**
     * Массив оригинальной фразы, которая должна отображаться в результате
     */
    AppDescriptionOriginSymbols = ['п', 'р', 'о', 'с', 'т', 'о', 'й с', 'п', 'о', 'с', 'о', 'б з', 'а', 'ш', 'и', 'ф', 'р', 'о', 'в', 'а', 'т', 'ь т', 'е', 'к', 'с', 'т'],
    /**
     * Коллекция span элементов с буквами в блоке описания приложения
     */
    AppDescriptionSpanCollection = Array.from(document.querySelectorAll('#app_description span')),
    /**
     * Элемент фонового затемнения страницы
     */
    PageBackgroundElement = document.getElementById('page-background'),
    PageElem__navigationBlock = document.querySelectorAll('.navigation__block__title'),
    PageElem__encrpytSliderCollection = document.querySelectorAll("#cipher-content__encrypt-block .content__input-block__block-slide"),
    PageElem__decryptSliderCollection = document.querySelectorAll("#cipher-content__decrypt-block .content__input-block__block-slide"),
    PageElem__decryptTextareaCollection = document.querySelectorAll("#cipher-content__decrypt-block .input-block--minor textarea"),
    PageElem__cipherSaltCollection = document.querySelectorAll('.cipher-salt-input'),
    PageElem__cipherKeyCollection = document.querySelectorAll('.cipher-key-input');



    
var pageElem__decryptText = document.getElementById('decryptText');

preloader__show()
setTimeout(function() {
    preloader__hide()
}, 300)


//Показываем название приложения
setTimeout( () => {
    document.getElementById('app-name').classList.add('shift');    
}, 700)
setTimeout( () => {
    //Трансформируем название приложения CipherSapphire => Cipphire
    document.getElementById('app-name--cipher').children[1].classList.add('hide');
    document.getElementById('app-name--sapphire').children[0].classList.add('hide');
    //Показываем строку с текстом описания приложения и заполнявем ее рандомными символами
    document.getElementById('app_description').classList.add('shift');
    descriptionRandSymbols()
}, 1800)


/**
 * Очищение рандомных символов в описании приложения и замена их на буквы оригинального сообщения
 */  
function clearRandomSymb() 
{
    AppDescriptionSpanCollection.forEach( (el, index) => {
        setTimeout ( () => {
            el.textContent = AppDescriptionOriginSymbols[index]
            el.classList.remove('shine')
        }, 50 * index)
    })
}


let randomSymbInterval;
/**
 * Отображение рандомных символов в описании приложения и последующая замена на оригинальное описание
 */
function descriptionRandSymbols()
{
        randomSymbInterval = setInterval ( () => {
            let randSymb = Math.floor(Math.random() * AppDescriptionSpanCollection.length);
            AppDescriptionSpanCollection[randSymb].classList.add('shine');
            AppDescriptionSpanCollection[randSymb].textContent = AppDescriptionRandomSymbols[Math.floor(Math.random() * AppDescriptionRandomSymbols.length)];
        }, 50)

        /**
         * Через некоторое время очищаем рандомные символы и заменем их символами оригинального сообщения 
         */
        setTimeout( () => {
            clearRandomSymb();
            //Очищаем все накопившиеся интервалы заполнения описания рандомными символами
            clearInterval(randomSymbInterval);
        }, 2000);
}


//Наведение мышки на блок с названием приложения отображает полное название, а не объединенное
document.getElementById('app-name').addEventListener('mouseover', () => {
    document.getElementById('app-name--cipher').children[1].classList.remove('hide')
    document.getElementById('app-name--sapphire').children[0].classList.remove('hide')
})
document.getElementById('app-name').addEventListener('mouseout', () => {
    document.getElementById('app-name--cipher').children[1].classList.add('hide')
    document.getElementById('app-name--sapphire').children[0].classList.add('hide')
})


//Отправка обратной связи
document.getElementById('feedback-submit').addEventListener('click', function () {
    let feedbackText = document.getElementById('feedback-text').value.trim(),
        feedbackSender = document.getElementById('feedback-sender-contact').value.trim()
    if (!feedbackText.length) {
        alert('Пустое сообщение');

        return;
    }
    //Отправляем синхронным запросом данные с фидбеком. Аснихронность ни к чему, так как этот функционал никак не влияет на процесс, ошибки не должны возникнуть и в любом случае не планирую их обрабатывать
    fetch('./api/postFeedback', {
        method: 'POST',
        body: JSON.stringify({
            feedback_text: feedbackText,
            feedback_sender_contact: feedbackSender,
        }),
    });
    //Очищаем поля формы
    Array.from(document.querySelectorAll('#feedback-fields-block .feedback-fields-block__field')).forEach( (el) => {
        el.value = '';  
    })
    document.getElementById('page-background').click()
    alert('Спасибо за твою обратную связь!');
})


//Клик по области затемнения при открытом разделе меню закрывает его
document.getElementById('page-background').addEventListener('click', function() {
    this.classList.remove('visible')
    document.querySelector('.navigation__block.visible').classList.remove('visible')
})


/**
 * Метод получения персональных приватных данных для шифрования
 * @param {string} privateData генерируемое значение 
 */
function getPrivateData_rqst (privateData) {
    return fetch((privateData == 'salt' ? './api/getCipherSalt' : './api/getCipherKey'), {
        method: "GET",
    }).then( 
        promise => {
            if (promise.status !== 200) {
                let errMsg = 'Error'
                return {err: errMsg + ": " + promise.headers.get('X-Error-Msg')} || true
            } else {
                return promise.json()
            }
        }   
    )
}


/**
 * Метод очистки элемента с текстом дешифрования
 */
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


/**
 * Метод сверки значений между инпутами блока Шифрования и Дешифрования. Если значения различаются, инпут в блоке Дешифрования подсвечивается
 * @param {array} collection коллекция элементов, в которых проверяется разница значений
 */
function checkInputValueDiff (collection)
{
    if (collection[0].value !== collection[1].value) {
        collection[1].classList.add('input-value-diff')
    } else {
        collection[1].classList.remove('input-value-diff')
    }
}


/**
 * Вставка скопированного значения из поля в блоке шифрования в поле дешифровки
 * @param {int} index порядковый номер иконки, значение блок которого копируется
 */
function insertValue(index) 
{
    //Возвращаем замыкание, в котором получаем параметр события клика (event) и, ориентируясь на него, раскрываем или скрываем такой же блок, но в соседнем разделе (если слайдер нажат в блоке шифрования, раскрываем тот же блок в разделе дешифрования и наоборот) 
    return function(event) {
        let encryptFieldVal = event.currentTarget.closest('.input-block__field-row').children[0].value;
        if (!encryptFieldVal) {
            return;
        }
        PageElem__decryptTextareaCollection[index].value = event.currentTarget.closest('.input-block__field-row').children[0].value
        checkInputValueDiff(event.currentTarget.closest('#content__encrypt-block__salt') ? PageElem__cipherSaltCollection : PageElem__cipherKeyCollection)
    }
}


/**
 * Работа со слайдером
 * @param {int} index порядковый номер блока, в котором взаимодействуем со слайдером 
 */
function slideBlock(index) 
{
    //Возвращаем замыкание, в котором получаем параметр события клика (event) и, ориентируясь на него, раскрываем или скрываем такой же блок, но в соседнем разделе (если слайдер нажат в блоке шифровании - раскрываем тот же блок в разделе дешифрования и наоборот) 
    return function(event) {
        let siblingBlock = event.currentTarget.closest('#cipher-content__encrypt-block') ? PageElem__decryptSliderCollection : PageElem__encrpytSliderCollection,      //Слайдеры в "противоположном" активному блоке Дешифрование/Шифрование
            sliderParentBlock = event.currentTarget.parentElement;  //Родительский элмент нажатого слайдера
        if (sliderParentBlock.classList.contains('visible')) {
            let sliderField = document.querySelector(`#${sliderParentBlock.id}` + " textarea"),     //Текстовое поле, находящееся в блоке слайдера
                siblingSliderField = document.querySelector(`#${siblingBlock[index].closest('.input-block--minor').id}` + " textarea")      //Текстовое поле, находящееся в "противоположном" блоке слайдера
            //Если при клике на слайдер в поле его родительского блока или в поле "противоположного" блока есть значение, слайдер не сворачивается, при этом проигрывается анимация
            if (sliderField.value.length) {
                sliderField.classList.add('shake')
                setTimeout( () => {
                    sliderField.classList.remove('shake')
                }, 1000)

                return;
            } 
            if (siblingSliderField.value.length) {
                siblingSliderField.classList.add('shake')
                setTimeout( () => {
                    siblingSliderField.classList.remove('shake')
                }, 1000)

                return;
            }
            sliderParentBlock.classList.remove('visible')
            siblingBlock[index].parentElement.classList.remove('visible')
        } else {
            sliderParentBlock.classList.add('visible')
            siblingBlock[index].parentElement.classList.add('visible')
        }  
    }    
}


/**
 * Метод получения приватных данных для шифрования: ключ/соль 
 * @param {string} data название генерируемого значения соль/ключ
 */
function getPrivateData (privateData) 
{
    return async function (event, privateDataAction = privateData) {
        //Если клик происходит во время отсчета до исчезновения соли/ключа
        if (event.target.classList.contains('get-data')) {
            return;
        }
        let parentBlock = event.currentTarget.closest('.section-private-data').id,
            privateDataRqst = (privateDataAction == 'cipherSalt' ? await getPrivateData_rqst('salt') : await getPrivateData_rqst('key')),
            userPrivateData
        if (privateDataRqst.err) {
            alert(privateDataRqst.err)
            
            return;
        }
        userPrivateData = (privateDataAction == 'cipherSalt' ? privateDataRqst.cipherSalt : privateDataRqst.cipherKey)
        //Пока идет счетчик для копирования, нельзя запустить генерацию нового значения
        event.target.classList.add('get-data');
        //Счетчик удаление сгенерированного значения
        let privateDataTimerCount = 7,
            timerTextBlock = document.querySelector(`#${parentBlock} .private-data__timer-block .private-data__timer-block__text`),
            timerAnimationBlock = document.querySelector(`#${parentBlock} .private-data__timer-block .private-data__timer-block__timer`),
            privateDataResultBlock = document.querySelector(`#${parentBlock} .private-data__result`),
            privateDataResultText = document.querySelector(`#${parentBlock} .private-data__result .private-data__result__text`);
        privateDataResultBlock.classList.add('visible')
        privateDataResultText.textContent = userPrivateData
        timerTextBlock.innerHTML = (privateDataAction == 'cipherSalt' ? 'Соль будет удалена ' : 'Ключ будет удален ') + ' через <span>' + privateDataTimerCount + '<span>';
        timerAnimationBlock.classList.add('saltCounterStart')
        //Через промежуток времени удаляем сгенерированное значение, скрываем соответствующие элементы со страницы
        setTimeout(function(){
            privateDataResultBlock.classList.remove('visible')
            privateDataResultText.textContent = "";
            timerTextBlock.innerHTML = "";
            event.target.classList.remove('get-data');
            timerAnimationBlock.classList.remove('saltCounterStart')
            clearInterval(privateDataInterval);
        }, 1000 * privateDataTimerCount - 100)
        //Интвервал, отсчитывающий время таймера перед удалением сгенерированного значения
        let privateDataInterval = setInterval(function(){
            privateDataTimerCount--;
            timerTextBlock.innerHTML = (privateDataAction == 'cipherSalt' ? 'Соль будет удалена ' : 'Ключ будет удален ') + ' через <span>' + privateDataTimerCount + '<span>';
        }, 1000)
    }
}


//Генерация соли для шифра
document.getElementById('api-get-cipher-salt').addEventListener('click', async () => {
    let cipherSaltRqst = await getPrivateData_rqst('salt'),
        cipherSalt
    if (cipherSaltRqst.err) {
        alert(cipherSaltRqst.err)
        
        return;
    }
    cipherSalt = cipherSaltRqst.cipherSalt
    document.getElementById('cipherSalt').value = cipherSalt;
    checkInputValueDiff(PageElem__cipherSaltCollection)
})


//Получение ключа для шифра 
document.getElementById('api-get-cipher-key').addEventListener('click', async () => {
    let cipherKeyRqst = await getPrivateData_rqst('key'),
        cipherKey
    if (cipherKeyRqst.err) {
        alert(cipherKeyRqst.err)
        
        return;
    }
    cipherKey = cipherKeyRqst.cipherKey
    document.getElementById('cipherKey').value = cipherKey;
    checkInputValueDiff(PageElem__cipherKeyCollection)
})


//Копируем значение соли из раздела Шифрование в раздел Дешифрование
document.getElementById('copy-cipher-salt').addEventListener('click', function() {
    let cipherSalt = document.getElementById('cipherSalt').value;
    if (!cipherSalt) {
        return
    }
    document.getElementById('cipherSalt_decrypt').value = cipherSalt;
})


//Дешифрование текста
document.getElementById('content__decrypt-block__decrypt-btn').addEventListener('click', async function () {
    let decryptText = pageElem__decryptText.value,
        decryptSalt = document.getElementById('cipherSalt_decrypt').value ?? null,
        cipherKey = document.getElementById('cipher-key--decrypt').value

    if (!decryptText.length) {
        alert('Дешифруемый текст пустой')

        return;
    }
    if (cipherKey && !decryptSalt) {
        alert('При передаче ключа обязательно передавать соль')

        return;
    }
    setTimeout(() => {
        preloader__show()
    }, 300);
    
    let decryptResultBlock = document.getElementById('content__decrypt-block__result')

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

    //Гаврилов
    //ПОЧИСТИ PRELOADER.JS

    
    //#Гаврилов
    //ВЫЯВИ повторяющиеся DOCUMENGETELEMENTBYID в переменную на верх скрипта


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
    
    if (!response.ok) {
            //Если код ошибки не успешный, при этом передается кастомный текст ошибки
            if (response.headers.get('X-Error-Msg')) {
                decryptErrMsgArr.push(response.headers.get('X-Error-Msg'))
            } else {
                decryptErrMsgArr.push('Код ошибки: ' + decryptResponse.status)
            }
            return;
    } else {
       if (response.headers.get('X-Error-Msg')) {
            decryptErrMsgArr.push(response.headers.get('X-Error-Msg'))
        } 
    }

    
    //#Гаврилов
    //ПРОВЕРЯЙ ВОЗВРАЩЕНИЕ ТЕКСТА ОШИБКИ ПОСЛЕ КАСТОМНОЙ ПРОВЕКИ НА ШИФРОВАНИИ И НА ДЕШИФРОВАНИИ, ПОКА ЧТО НА ШИФРОВАНИИ ТЫ ПРОВЕРЯЕШЬ ТОЛЬКО СТАТУСЫ 400 500, ОШИБКИ ПАРСИНГА JSON И ОШИБКИ ОБРАЩЕНИЯ К СЕРВЕРУ  

    //ГАВРИЛОВ
    //ПОДУМАТЬ НА КАКОЙ КОД ОРИЕНТИРОВАТЬСЯ, ЧТОБЫ ПРОДОЛЖАТЬ ИСПОЛНЕНИЕ СКРИПТА (В ОСТАЛЬНЫХ СЛУЧАЯХ ДОЛЖНЫ ЧТО-ТО ПОКАЗЫВАТЬ,Я ОШИБКУ КАКУЮ-ТО)
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
})



//Получение соли и ключа к шифру
document.getElementById('getSalt').addEventListener('click', getPrivateData('cipherSalt'))
document.getElementById('getKey').addEventListener('click', getPrivateData('cipheKey'))


//Клик по кнопке Расшифровать напротив каждого сгенерированного шифра. Не можем при рендеринге страницы навешивать обработчик, так как эти элементы не сгенерирован на этапе первичного рендеринга
document.addEventListener('click', function(event) 
{
  if (event.target.classList.contains('content__encrypt-block__result__parent__call-decrypt')) {
    pageElem__decryptText.value = event.target.nextSibling.textContent;
    clearDecryptText();
    document.getElementById('cipher-content__decrypt-block').scrollIntoView({
        behavior: 'smooth'
    });
  }
});


//Шифрование текста
document.getElementById('content__encrypt-block__encrypt-submit').addEventListener('click', async function () {
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

    if (cipherKey && !encryptSalt) {
        alert('При передаче ключа обязательно передавать соль')

        return;
    }

    if (!encryptText.length) {
        alert('Пустой шифруемый текст')

        return;
    }


    //Если есть предыдущие результаты шифрования - очищаем их
    if (prevResulst.length) {
        for (let encryptIndex = 0; encryptIndex < prevResulst.length; encryptIndex++){
            prevResulst[encryptIndex].classList.add('hide')
        }
    }

    preloader__show()

    let encryptResponseArr = [];
    let response;

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
        setTimeout(() => {
            encryptResultBlock.textContent = '';
            encryptPromisesArr.forEach((encryptText, index) => {
                let childEncryptBlock = document.createElement('div'),
                    encryptTextBlock = document.createElement('div'),
                    callDecryptButton = document.createElement('button');
                childEncryptBlock.classList.add('content__encrypt-block__result__parent', 'result-text-block')
                encryptTextBlock.classList.add('content__block__result__text')
                callDecryptButton.classList.add('content__encrypt-block__result__parent__call-decrypt')
                callDecryptButton.setAttribute('type', 'button');
                encryptTextBlock.textContent = encryptText.encryptText
                callDecryptButton.textContent = "Дешифровать"
                childEncryptBlock.appendChild(callDecryptButton);
                childEncryptBlock.appendChild(encryptTextBlock);
                setTimeout(() => {
                    encryptResultBlock.appendChild(childEncryptBlock)
                }, 100 * index);
            });
            document.getElementById('content__encrypt-block__result').scrollIntoView({
                behavior: 'smooth'
            });
        }, 500);

        encryptErrArr = [...new Set(encryptErrArr)];
        console.log(encryptErrArr)
})

//Обработчик клика по элементу навигации
PageElem__navigationBlock.forEach( (navBlock) => 
{
    navBlock.addEventListener('click', () => {
        let navBlock_visible = navBlock.parentElement.classList.contains('visible');
        PageElem__navigationBlock.forEach( (siblingsNav) => {
            siblingsNav.parentElement.classList.remove('visible')
        })
        if (!navBlock_visible) {
            navBlock.parentElement.classList.add('visible')
            PageBackgroundElement.classList.add('visible')
        } else {
            PageBackgroundElement.classList.remove('visible')
        }
    })
})

//Изменение значения в поле с шифром приводит к очистке блока с результатом дешифрования, чтобы у пользователя не было ошибочного представления, что результат связан с другим шифром
document.getElementById('decryptText').addEventListener('input', (el) => {
    clearDecryptText()
})


//Обработчик клика по иконке для раскрытия, скрытия слайдера
document.querySelectorAll('.slider-arrow').forEach( (el) => {
    el.addEventListener('click', () => {
        el.parentElement.classList.toggle('visible')
    })
})


//#Гаврилов
//НАВЕСЬ ФУНКЦИИ-ОБРАБОТЧИКИ ПРЯМО В HTML ЭЛЕМЕНТЫ, НАПРИМЕР НА АТРИБУТ ONCHANGE  




/**
 * Клик по кнопке скопировать значение из шифрования в дешифровку
 */
document.querySelectorAll("#cipher-content__encrypt-block .input-block__icon-block__icon.icon--copy").forEach( (el, index) => {
    el.addEventListener('click', insertValue(index));
})
/**
 * Клик по слайдеру для разворачивания дополнительных блоков
 */
PageElem__encrpytSliderCollection.forEach(function(el, index) {
    el.addEventListener('click', slideBlock(index))   
})
/**
 * Клик по слайдеру для разворачивания дополнительных блоков
 */
PageElem__decryptSliderCollection.forEach(function(el, index) {
    el.addEventListener('click', slideBlock(index))   
})


//При изменении значений в инпутах соли или ключа шифра подсвечивается соответствующий блок в разделе Дешифрования, если значения в блоке Шифрования и Дешифрования отличаются
PageElem__cipherSaltCollection.forEach( el => {
    el.addEventListener('input', () => checkInputValueDiff(PageElem__cipherSaltCollection))
})
PageElem__cipherKeyCollection.forEach( el => {
    el.addEventListener('input', () => checkInputValueDiff(PageElem__cipherKeyCollection))
})
