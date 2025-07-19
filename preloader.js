    //Перечень символов, которые будут отображаться в кристалле
const SymbolsArr = '§rzC?I~К)чМSyJiТО<}÷g€мP0НbNУ≠x9#ц^Ёn?AЦfRj`и]©qlD+в1еаубsПvi-ЛF%HЭ3X_K>ha&тфоГ:π$сЗL!С5kVd(*EQWY'.split(''),
    //Контейнер с прелоадером
    PreloaderBlock = document.getElementById('preloader-container'),
    //Коллекция треугольников в кристалле
    TriangleBlocks = document.querySelectorAll('.triangle')


let sapphireShineInterval,  //Интервал сияния кристалла
    prevSapphireColor = null;
function preloader__show()
{
    PreloaderBlock.classList.add('visible')
    sapphireShineInterval = setInterval( () => {   
        let activeBlocks = document.querySelectorAll('.triangle.triangle-colored');
        //Каждую итерацию интервала убираем эффект сияния с треугольника кристалла
        if (activeBlocks.length) {
            activeBlocks[0].classList.remove('triangle-colored')
        }
        //Определяем порядковый номер треугольника для сияния
        let coloredBLockNum = Math.floor(Math.random() * 8);
        //Фиксируем порядковый номер кристалла для синия для следующей итерации анимации. Ниже описано зачем
        if (!prevSapphireColor) {
            prevSapphireColor = coloredBLockNum;
        //Если следующий треугольник кристалла, который должен сиять тот же, что и в предыдущей итерации, принудительно меняем его, чтобы не было такого, что несколько итераций анимации сияют одни и те же треугольники кристалла. Это выглядит некрасиво
        } else if (prevSapphireColor == coloredBLockNum) {
            coloredBLockNum = (coloredBLockNum == TriangleBlocks.length -1 ? coloredBLockNum - 1 : coloredBLockNum + 1);
        }
        //Анимация плавного исчезновения текст в треугольнике кристалла
        TriangleBlocks[coloredBLockNum].firstElementChild.classList.add('visible')
        //Отрисовываем в треугольнике случайный символ
        TriangleBlocks[coloredBLockNum].firstElementChild.innerHTML = SymbolsArr[Math.floor(Math.random() * SymbolsArr.length)];
        //Класс с анимацией сияния треугольников кристалла
        TriangleBlocks[coloredBLockNum].classList.add('triangle-colored')
        prevSapphireColor = coloredBLockNum;
    }, 200)
}


//Скрываем прелоадер и чистим его от анимации сияния, символов, убираем класс сияния 
function preloader__hide()
{
    clearInterval(sapphireShineInterval);
    for (let index = 0; index < TriangleBlocks.length; index++) {
        TriangleBlocks[index].classList.remove('triangle-colored')
        TriangleBlocks[index].firstElementChild.classList.remove('visible')
        TriangleBlocks[index].firstElementChild.innerHTML = ''
    }
    PreloaderBlock.classList.remove('visible')
}

export {preloader__show, preloader__hide};
