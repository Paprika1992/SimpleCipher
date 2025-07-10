//УБЕРИ ШИРОКИЕ СИМВОЛЫ И БУКВЫ И ЗАМЕНИ ИХ УЗКИМИ И ТТОГДА НЕ ПОНАДОБИТСЯ СДВИГАТЬ БУКВЫ ОТНОСИТЕЛЬНО ЦЕНТРА ЭЛЕМЕНТА
let symbolsArr = '§rzC?I~К)чМSyJiТО<}÷g€мP0НbNУ≠x9#ц^Ёn?AЦfRj`и]©qlD+в1еаубsПvi-ЛF%HЭ3X_K>ha&тфоГ:π$сЗL!С5kVd(*EQWY'.split('');

//sapphire
//cipher
//sappher, ciphire

let triangleBlocks = document.querySelectorAll('.triangle')

const preloaderBlock = document.getElementById('preloader-container'),
        triangles = document.querySelectorAll('.triangle');
let sapphireShine,
    prevSapphireColor = null;
function preloader__show()
{
    preloaderBlock.classList.add('visible')
    sapphireShine = setInterval(function()
    {   
        let activeBlocks = document.querySelectorAll('.triangle.triangle-colored');
        if (activeBlocks.length) {
            activeBlocks[0].classList.remove('triangle-colored')
            //triangleBlocks[0].firstElementChild.classList.remove('visible')
            //activeBlocks[0].firstElementChild.innerHTML = "";
        }
        let coloredBLockNum = Math.floor(Math.random() * 8);
        if (!prevSapphireColor) {
            prevSapphireColor = coloredBLockNum;
        } else if (prevSapphireColor == coloredBLockNum) {
            coloredBLockNum = (coloredBLockNum == triangleBlocks.length -1 ? coloredBLockNum - 1 : coloredBLockNum + 1);
        }
        
        triangleBlocks[coloredBLockNum].firstElementChild.classList.add('visible')
        triangleBlocks[coloredBLockNum].firstElementChild.innerHTML = symbolsArr[Math.floor(Math.random() * symbolsArr.length)];
        // triangleBlocks[coloredBLockNum].innerHTML = "<div class='triangle-text'>" + symbolsArr[Math.floor(Math.random() * symbolsArr.length)] + "</div>";
        triangleBlocks[coloredBLockNum].classList.add('triangle-colored')
        prevSapphireColor = coloredBLockNum;
    }, 200)
}

function preloader__hide()
{
    clearInterval(sapphireShine);
    for (let index = 0; index < triangleBlocks.length; index++) {
        triangles[index].classList.remove('triangle-colored')
        triangles[index].firstElementChild.classList.remove('visible')
        triangles[index].firstElementChild.innerHTML = ''
    }
    preloaderBlock.classList.remove('visible')
}

export {preloader__show, preloader__hide};

//Гаврилов
//ВЕРХНИЕ ТРЕУГОЛЬНИКИ НЕ "СВЕРКАЮТ" ТОЛЬКО НИЖНИЕ ПОЧЕМУ-ТО

//#Гаврилов
//В ПРЕЛОАДЕРЕ НЕ ДОЛЖНЫ ПОВТОРЯТЬСЯ КРИСТАЛЫ, КОТОРЫЕ СВЕРКАЮТ. КАЖДУ ИТЕРАЦИЮ "СВЕРКАНИЯ" ОНИ ДОЛЖНЫ ОТЛИЧАТЬСЯ



