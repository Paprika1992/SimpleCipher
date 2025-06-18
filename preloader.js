let triangleNum = Math.floor(Math.random() * 8);

let symbolsArr = '§rMC?I~К)чМSmJ=ТО<}÷g€мP0НbNУ≠x9#ц⇔^ЁШщAЦfRj`и]©ql№D+в1еаубsПvi@wЛF%HЭ3X_K>ha&тфоГ:π$сЗL!С5kVd(*EQWY'.split('');

console.log(symbolsArr);

console.log(triangleNum)

let triangleBlocks = document.querySelectorAll('.triangle-block')

console.log(triangleBlocks)

//triangleBlocks[1].classList.add('triangle-colored')

// console.log(document.querySelectorAll('.triangle-block.triangle-colored'))


//triangleBlocks[0].classList.add('triangle-with-text')



setInterval(function()
{
    let activeBlocks = document.querySelectorAll('.triangle-block.triangle-colored');
    if (activeBlocks.length) {
        activeBlocks[0].classList.remove('triangle-colored')
        //triangleBlocks[0].firstElementChild.classList.remove('visible')
        //activeBlocks[0].firstElementChild.innerHTML = "";
    }
    let coloredBLockNum = Math.floor(Math.random() * 8);
    triangleBlocks[coloredBLockNum].firstElementChild.classList.add('visible')
    triangleBlocks[coloredBLockNum].firstElementChild.innerHTML = symbolsArr[Math.floor(Math.random() * symbolsArr.length)];
    // triangleBlocks[coloredBLockNum].innerHTML = "<div class='triangle-colored-text'>" + symbolsArr[Math.floor(Math.random() * symbolsArr.length)] + "</div>";
    triangleBlocks[coloredBLockNum].classList.add('triangle-colored')
    
}, 300)

