let triangleNum = Math.floor(Math.random() * 8);

//УБЕРИ ШИРОКИЕ СИМВОЛЫ И БУКВЫ И ЗАМЕНИ ИХ УЗКИМИ И ТТОГДА НЕ ПОНАДОБИТСЯ СДВИГАТЬ БУКВЫ ОТНОСИТЕЛЬНО ЦЕНТРА ЭЛЕМЕНТА
let symbolsArr = '§rMC?I~К)чМSmJ=ТО<}÷g€мP0НbNУ≠x9#ц⇔^ЁШщAЦfRj`и]©ql№D+в1еаубsПvi@wЛF%HЭ3X_K>ha&тфоГ:π$сЗL!С5kVd(*EQWY'.split('');

//sapphire
//cipher
//sappher, ciphire

let triangleBlocks = document.querySelectorAll('.triangle')

//Гаврилов
//ВЕРХНИЕ ТРЕУГОЛЬНИКИ НЕ "СВЕРКАЮТ" ТОЛЬКО НИЖНИЕ ПОЧЕМУ-ТО

setInterval(function()
{
    let activeBlocks = document.querySelectorAll('.triangle.triangle-colored');
    if (activeBlocks.length) {
        activeBlocks[0].classList.remove('triangle-colored')
        //triangleBlocks[0].firstElementChild.classList.remove('visible')
        //activeBlocks[0].firstElementChild.innerHTML = "";
    }
    let coloredBLockNum = Math.floor(Math.random() * 8);
    triangleBlocks[coloredBLockNum].firstElementChild.classList.add('visible')
    triangleBlocks[coloredBLockNum].firstElementChild.innerHTML = symbolsArr[Math.floor(Math.random() * symbolsArr.length)];
    // triangleBlocks[coloredBLockNum].innerHTML = "<div class='triangle-text'>" + symbolsArr[Math.floor(Math.random() * symbolsArr.length)] + "</div>";
    triangleBlocks[coloredBLockNum].classList.add('triangle-colored')
    
}, 300)

