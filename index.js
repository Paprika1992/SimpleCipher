import {preloader__show, preloader__hide} from "./preloader.js";
    /**
     * Коллекция span элементов с буквами в блоке описания приложения
     */
const AppDescriptionSpanCollection = document.querySelectorAll('#app_description span'),
    /**
     * Коллекция слайдеров в блоке Шифрования
     */
    PageElem__encrpytSliderCollection = document.querySelectorAll("#cipher-content__encrypt-block .content__input-block__block-slide"),
    /**
     * Коллекция слайдеров в блоке Дешифрования
     */
    PageElem__decryptSliderCollection = document.querySelectorAll("#cipher-content__decrypt-block .content__input-block__block-slide"),
    /**
     * Коллекция текстовых инпутов для ввода соли в блоках Шифрования и Дешифрования
     */
    PageElem__cipherSaltCollection = document.querySelectorAll('.cipher-salt-input'),
    /**
     * Коллекция текстовых инпутов для ввода ключа в блоках Шифрования и Дешифрования
     */
    PageElem__cipherKeyCollection = document.querySelectorAll('.cipher-key-input'),
    /**
     * Кусок названия приложения, отображающее Cipher
     */
    PageElem__appName_cipher = document.getElementById('app-name--cipher'),
    /**
     * Кусок названия приложения, отображающее Sapphire
     */
    PageElem__appName_cipphie = document.getElementById('app-name--cipphire'),
    /**
     * Блок с сегментами названия приложения Cipher и Sapphire
     */
    PageElem__appName = document.getElementById('app-name'),
    /**
     * Инпут для ввода текста для дешифрования
     */    
    PageElem__decryptText = document.getElementById('decryptText'),
    /**
     * Инпут для ввода соли для шифрования
     */ 
    PageElem__cipherSalt = document.getElementById('cipherSalt'),
    /**
     * Фон затемнения страницы
     */
    PageElem__background = document.getElementById('page-background');


// preloader__show()
// setTimeout( () => {
//     preloader__hide()
// }, 300)

//Показываем название приложения
setTimeout( () => {
    PageElem__appName.classList.add('shift');    
}, 700)

setTimeout( () => {
    //Трансформируем название приложения CipherSapphire => Cipphire
    PageElem__appName_cipher.children[1].classList.add('hide');
    PageElem__appName_cipphie.children[0].classList.add('hide');
    //Показываем строку с текстом описания приложения и заполнявем ее рандомными символами
    document.getElementById('app_description').classList.add('shift');
    descriptionRandSymbols()
}, 1800)


//Наведение курсора мышки на блок с названием приложения отображает полное название, а не объединенное
PageElem__appName.addEventListener('mouseover', () => {
    PageElem__appName_cipher.children[1].classList.remove('hide')
    PageElem__appName_cipphie.children[0].classList.remove('hide')
})
PageElem__appName.addEventListener('mouseout', () => {
    PageElem__appName_cipher.children[1].classList.add('hide')
    PageElem__appName_cipphie.children[0].classList.add('hide')
})

/**
 * Обработчик закрытия всплывающего окна
 */
const closePopup = (event) =>{
    event.currentTarget.closest('.popup-container__block').remove()
}


/**
 * Метод генерации всплывающего окна
 * @param {string} popupText текст для отображения во всплывающем окне 
 */
function addPopup (popupText)
{
    let popupBlock = document.createElement('div'),
        popupTextBlock = document.createElement('div'),
        popupBtn = document.createElement('button')
    popupBlock.classList.add('popup-container__block')
    popupTextBlock.classList.add('popup-container__block__text')
    popupTextBlock.textContent = popupText
    popupBtn.textContent = 'ОК'
    popupBtn.addEventListener('click', closePopup)
    popupBlock.appendChild(popupTextBlock);
    popupBlock.appendChild(popupBtn);

    document.getElementById('popup-container').appendChild(popupBlock)
    setTimeout( () => {
        popupBlock.classList.add('animate')
    }, 100)
    document.getElementById('popup-container').classList.add('visible')
}


/**
 * Очищение рандомных символов в описании приложения и замена их на буквы оригинального сообщения
 */  
function clearRandomSymb() 
{
    AppDescriptionSpanCollection.forEach( (el, index) => {
        setTimeout ( () => {
            //Массив оригинальной фразы, которая должна отображаться в результате
            el.textContent = ['п', 'р', 'о', 'с', 'т', 'о', 'й с', 'п', 'о', 'с', 'о', 'б з', 'а', 'ш', 'и', 'ф', 'р', 'о', 'в', 'а', 'т', 'ь т', 'е', 'к', 'с', 'т'][index]
            el.classList.remove('shine')
        }, 50 * index)
    })
}

/**
 * Отображение рандомных символов в описании приложения и последующая замена на оригинальное описание
 */
function descriptionRandSymbols()
{
    /**
     * Массив оригинальной фразы, которая должна отображаться в результате
     */
    let appDescriptionRandomSymbols = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'z', 'y', 'x', 'w', 'v', 'u', 't', 's', 'r', 'p', 'q', 'o', 'n', 'm', 'l', 'k', 'j', 'i','h', 'g', 'f', 'e', 'd', 'c', 'b', 'a', '*', '=', '№', '⇔', '{', '}', '^', '-', '=', '~', '@', '#', '_', ' '],
    randomSymbInterval = setInterval ( () => {
        let randSymb = Math.floor(Math.random() * AppDescriptionSpanCollection.length);
        AppDescriptionSpanCollection[randSymb].classList.add('shine');
        AppDescriptionSpanCollection[randSymb].textContent = appDescriptionRandomSymbols[Math.floor(Math.random() * appDescriptionRandomSymbols.length)];
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


//Отправка обратной связи
document.getElementById('feedback-submit').addEventListener('click', () => {
    let feedbackText = document.getElementById('feedback-text').value.trim(),
        feedbackSender = document.getElementById('feedback-sender-contact').value.trim()
    if (!feedbackText.length) {
        addPopup('Пустое сообщение');

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
    Array.from(document.querySelectorAll('#feedback-fields-block .feedback-fields-block__field')).forEach( el => {
        el.value = '';  
    })
    PageElem__background.click();
    addPopup('Спасибо за обратную связь!');
})


//Клик по области затемнения при открытом разделе меню закрывает его
PageElem__background.addEventListener('click', function() {
    this.classList.remove('visible')
    document.querySelector('.navigation__block.visible').classList.remove('visible')
    document.querySelector('body').classList.remove('overflow-hide')
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
    if (!collection[0].value.length && !collection[1].value.length) {
        collection[1].classList.remove('input-value-diff')
         
        return;
    }
    if (collection[0].value !== collection[1].value) {
        collection[1].classList.add('input-value-diff')
    } else {
        
        collection[1].classList.remove('input-value-diff')
    }
}


/**
 * Коллекция текстовых инпутов для соли и ключа шифра
 */
const PageElem__decryptTextareaCollection = document.querySelectorAll("#cipher-content__decrypt-block .input-block--minor textarea")
/**
 * Вставка скопированного значения из поля в блоке шифрования в поле дешифровки
 * @param {int} index порядковый номер иконки, значение блока которого копируется
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
    return event => {
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
                siblingSliderField.scrollIntoView({
                    behavior: 'smooth'
                });
                siblingSliderField.classList.add('shake')
                setTimeout( () => {
                    siblingSliderField.classList.remove('shake')
                }, 1000)

                return;
            }
            //Скрываем слайдер 
            sliderParentBlock.classList.remove('visible')
            siblingBlock[index].parentElement.classList.remove('visible')
        } else {
            //Раскрываем слайдер
            sliderParentBlock.classList.add('visible')
            siblingBlock[index].parentElement.classList.add('visible')
        }  
    }    
}


//Очищаем поля формы и запускаем проверку значения в инпутах для их подсветки или снятия подсветки
document.querySelectorAll('.content__encrypt-block__encrypt-clear').forEach( (elem, index) => {
    elem.addEventListener('click', () => {
        document.querySelectorAll('.cipher-content__block')[index].children[0].reset();
        checkInputValueDiff(PageElem__cipherKeyCollection)
        checkInputValueDiff(PageElem__cipherSaltCollection)
    })
})


/**
 * Метод получения приватных данных для шифрования: ключ/соль 
 * @param {string} privateData название генерируемого значения соль/ключ
 */
function getPrivateData (privateData) 
{
    return async (event, privateDataAction = privateData) => {
        //Если клик происходит во время отсчета до исчезновения соли/ключа
        if (event.target.classList.contains('get-data')) {
            return;
        }
        let parentBlock = event.currentTarget.closest('.section-private-data').id,
            privateDataRqst = (privateDataAction == 'cipherSalt' ? await getPrivateData_rqst('salt') : await getPrivateData_rqst('key')),
            userPrivateData
        if (privateDataRqst.err) {
            addPopup(privateDataRqst.err);
            
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
        setTimeout( () => {
            privateDataResultBlock.classList.remove('visible')
            privateDataResultText.textContent = "";
            timerTextBlock.innerHTML = "";
            event.target.classList.remove('get-data');
            timerAnimationBlock.classList.remove('saltCounterStart')
            clearInterval(privateDataInterval);
        }, 1000 * privateDataTimerCount - 100)
        //Интвервал, отсчитывающий время таймера перед удалением сгенерированного значения
        let privateDataInterval = setInterval( () => {
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
        addPopup(cipherSaltRqst.err);
        
        return;
    }
    cipherSalt = cipherSaltRqst.cipherSalt
    PageElem__cipherSalt.value = cipherSalt;
    checkInputValueDiff(PageElem__cipherSaltCollection)
})


//Получение ключа для шифра 
document.getElementById('api-get-cipher-key').addEventListener('click', async () => {
    let cipherKeyRqst = await getPrivateData_rqst('key'),
        cipherKey
    if (cipherKeyRqst.err) {
        addPopup(cipherKeyRqst.err);
        
        return;
    }
    cipherKey = cipherKeyRqst.cipherKey
    document.getElementById('cipherKey').value = cipherKey;
    checkInputValueDiff(PageElem__cipherKeyCollection)
})


//Копируем значение соли из раздела Шифрование в раздел Дешифрование
document.getElementById('copy-cipher-salt').addEventListener('click', () => {
    if (!PageElem__cipherSalt.value) {
        return
    }
    document.getElementById('cipherSalt_decrypt').value = PageElem__cipherSalt.value;
})


//Дешифрование текста
document.getElementById('content__decrypt-block__decrypt-btn').addEventListener('click', async () => {
    let decryptText = PageElem__decryptText.value,
        decryptSalt = document.getElementById('cipherSalt_decrypt').value ?? null,
        cipherKey = document.getElementById('cipher-key--decrypt').value
    if (!decryptText.length) {
        addPopup('Дешифруемый текст пустой')

        return;
    }
    if (cipherKey && !decryptSalt) {
        addPopup('При передаче ключа обязательно передавать соль');

        return;
    }
    clearDecryptText();
    preloader__show();
    //Блок с результатом дешифрования
    let decryptResultBlock = document.getElementById('content__decrypt-block__result')
    let getDecryptText_rqst = await fetch('./api/getDecryptText', {
        method: "POST",
        body: JSON.stringify({
            text: decryptText,
            cipherSalt: decryptSalt,
            cipherKey: cipherKey,
            demoPage: 1
        }),
        headers: {
            'content-type': 'application/json'
        }
    });

    let decryptResponse;
    try {
        //Текст с ошибкой при дешифровании
        let decryptErrMsg = null;
        decryptResponse = await getDecryptText_rqst.json()
        .then( result => {
            return result;
        }).catch( errMsg => {
            throw new Error('Возникла непредвиденная ошибка')
        }).finally( () => {
            setTimeout( () => {
                preloader__hide()
            }, 1000)
        })
        if (!getDecryptText_rqst.ok) {
            //Если код ошибки не успешный, при этом передается кастомный текст ошибки
            if (getDecryptText_rqst.headers.get('X-Error-Msg')) {
                decryptErrMsg = getDecryptText_rqst.headers.get('X-Error-Msg');
            } else {
                decryptErrMsg = 'Возникла непредвиденная ошибка. Код ошибки: ' + getDecryptText_rqst.status
            }
            throw new Error(decryptErrMsg)
        } else {
        if (getDecryptText_rqst.headers.get('X-Error-Msg')) {
                throw new Error(getDecryptText_rqst.headers.get('X-Error-Msg'));
            } 
        }
    } catch (err) {
        addPopup('Error: ' + err.message);
        preloader__hide();

        return;
    }

    //Рендерим блок с результатом дешифрования и отображаем в нем дешифрованный текст
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


//Клик по кнопке Дешифровать напротив каждого сгенерированного шифра. Не можем при рендеринге страницы навешивать обработчик, так как эти элементы не сгенерирован на этапе первичного рендеринга
document.addEventListener('click', event => {
  if (event.target.classList.contains('content__encrypt-block__result__parent__call-decrypt')) {
    PageElem__decryptText.value = event.target.nextSibling.textContent;
    clearDecryptText();
    document.getElementById('cipher-content__decrypt-block').scrollIntoView({
        behavior: 'smooth'
    });
  }
});


//Шифрование текста
document.getElementById('content__encrypt-block__encrypt-submit').addEventListener('click', async () => {
        //Очищенное от пробелов значение для шифрования
    let encryptText = document.getElementById('encryptText').value.trim(),
        //Желаемая длина итоговвых шифров
        encryptFakeLength = document.getElementById('cipherLength').value,
        //Итоговое количество шифров
        resultCipherCount = document.getElementById('cipherCount').value,
        //Пользовательская соль для шифрования
        encryptSalt = document.getElementById('cipherSalt').value || null,
        //Коллекция элементов с предыдущими результатами шифрования
        prevResulst = document.querySelectorAll('.content__encrypt-block__result__parent'),
        //Пользовательский ключ шифрования
        cipherKey = document.getElementById('cipherKey').value || null

    try {
        if (!encryptText.length) {
            throw new Error('Обязательное поле "Текст для шифрования" не заполнено');
        }
        if (!encryptFakeLength) {
            throw new Error('Обязательное поле "Желаемая длина шифра" не заполнено');
        }
        if (!resultCipherCount) {
            throw new Error('Обязательное поле "Кол-во шифров" не заполнено');
        }
        //#Гаврилов
        //ВЫНЕСИ 899 В КОНСТАНТУ
        if (encryptFakeLength > 899) {
            throw new Error('Максимальная желаемая длина шифра 899 символов');
        }
        if (resultCipherCount > 20) {
            throw new Error('Максимальная количество итоговых шифров 20');
        }
        if (cipherKey && !encryptSalt) {
            throw new Error('При передаче ключа обязательно передавать соль');
        }
        if (!encryptText.length) {
            throw new Error('Пустой шифруемый текст');
        } else if (encryptText.length > 899) {
            throw new Error('Превышена максимальная длина текста (899 символов)');
        }
    } catch (err) {
        addPopup(err.message);

        return;
    }
    //Если есть предыдущие результаты шифрования - очищаем их
    if (prevResulst.length) {
        for (let encryptIndex = 0; encryptIndex < prevResulst.length; encryptIndex++){
            prevResulst[encryptIndex].classList.add('hide');
        }
    }
    preloader__show();
    let encryptResponseArr = [];    //Результаты шифрования
    for (let index = 1; index <= resultCipherCount; index++) {
        encryptResponseArr.push(fetch('./api/getEncryptText', {
            method: "POST",
            body: JSON.stringify({
                text: encryptText,
                fakeLength: encryptFakeLength,
                cipherSalt: encryptSalt,
                cipherKey: cipherKey,
                demoPage: 1
            }),
            headers: {
                'content-type': 'application/json'
            }
        }))
    }
    let encryptPromisesArr = [];
    try {
        encryptPromisesArr = await Promise.allSettled(encryptResponseArr).then( encryptPromises => {
            let interimArr = [];
            encryptPromises.forEach( encryptPromise => {
                //Ошибка получения данных по результатам работы fetch
                if (encryptPromise.status == 'fulfilled') {
                    //Проверяем код ответа
                    if (encryptPromise.value.ok) {
                        let jsonResponse = encryptPromise.value.json()
                            .then( encryptResult => {
                                return encryptResult
                            })
                            //Ловим ошибки парсинга JSON
                            .catch( jsonErr => {
                                throw new Error('Возникла непредвиденная ошибка');
                            })
                        interimArr.push(jsonResponse)
                    } else {
                        //Если код ошибки не успешный, при этом передается кастомный текст ошибки
                        if (encryptPromise.value.headers.get('X-Error-Msg')) {
                            throw new Error(encryptPromise.value.headers.get('X-Error-Msg'));
                        } else {
                            throw new Error('Возникла непредвиденная ошибка. Код ошибки: ' + encryptPromise.value.status);
                        }
                    }
                } else if (encryptPromise.status == 'rejected') {
                    throw new Error('Ошибка получения данных: ' + encryptPromise.reason);
                }
            })
            return Promise.all(interimArr).then( resultes => {return resultes;} )
        }).finally( () => {
            setTimeout( () => {
                preloader__hide()
            }, 500)
        })
    } catch (err) {
        addPopup('Error: ' + err.message);

        return;
    }
    //Отрисовываем получившиеся шифры, если не возникло ошибок
    let encryptResultBlock = document.getElementById('content__encrypt-block__result')
    setTimeout( () => {
        encryptResultBlock.textContent = '';
        encryptPromisesArr.forEach( (encryptText, index) => {
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
            setTimeout( () => {
                encryptResultBlock.appendChild(childEncryptBlock)
            }, 100 * index);
        });
    }, 500);
    document.getElementById('content__encrypt-block__result').scrollIntoView({
        behavior: 'smooth'
    });
})


const PageElem__navigationBlock = document.querySelectorAll('.navigation__block__title');
//Обработчик клика по элементу навигации
PageElem__navigationBlock.forEach( navBlock => {
    navBlock.addEventListener('click', () => {
        let navBlock_visible = navBlock.parentElement.classList.contains('visible'),
            pageBody = document.querySelector('body');
        PageElem__navigationBlock.forEach( siblingsNav => {
            siblingsNav.parentElement.classList.remove('visible')
            pageBody.classList.remove('overflow-hide')
        })
        if (!navBlock_visible) {
            navBlock.parentElement.classList.add('visible')
            PageElem__background.classList.add('visible')
            pageBody.classList.add('overflow-hide')
        } else {
            PageElem__background.classList.remove('visible')
            pageBody.classList.remove('overflow-hide')
        }
    })
})


//Изменение значения в поле с шифром приводит к очистке блока с результатом дешифрования, чтобы у пользователя не было ошибочного представления, что результат связан с другим шифром
document.getElementById('decryptText').addEventListener('input', clearDecryptText)


//Обработчик клика по иконке для раскрытия, скрытия слайдера
document.querySelectorAll('.slider-arrow').forEach( el => {
    el.addEventListener('click', () => {
        el.parentElement.classList.toggle('visible')
    })
})
 

//Клик по кнопке скопировать значение из блока Шифрования в блок Дешифрования
document.querySelectorAll("#cipher-content__encrypt-block .input-block__icon-block__icon.icon--copy").forEach( (el, index) => {
    el.addEventListener('click', insertValue(index));
})


//Клик по слайдеру для разворачивания дополнительных блоков
PageElem__encrpytSliderCollection.forEach( (el, index) => {
    el.addEventListener('click', slideBlock(index))   
})
//Клик по слайдеру для разворачивания дополнительных блоков
PageElem__decryptSliderCollection.forEach( (el, index) => {
    el.addEventListener('click', slideBlock(index))   
})


//При изменении значений в инпутах соли или ключа шифра подсвечивается соответствующий блок в разделе Дешифрования, если значения в блоке Шифрования и Дешифрования отличаются
PageElem__cipherSaltCollection.forEach( el => {
    el.addEventListener('input', () => checkInputValueDiff(PageElem__cipherSaltCollection))
})
PageElem__cipherKeyCollection.forEach( el => {
    el.addEventListener('input', () => checkInputValueDiff(PageElem__cipherKeyCollection))
})
