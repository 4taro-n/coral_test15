// console.log("test");

window.addEventListener("scroll", function() {

    let scroll = document.documentElement.scrollTop;

    //数値を画面に入力する
    document.getElementById("scrollValue").textContent = scroll;
    
    // スクロール量が800以上の場合
    // if (scroll > 800)
    // {
    //     // firstタグに対してtestを加える
    //     document.querySelector("header").classList.add("change");
    // }else {
    //     document.querySelector("header").classList.remove("change");
    // }
    
    // index.htmlのheader
    if (scroll > 800)
    {
        // firstタグに対してtestを加える
        document.querySelector(".index_header").classList.add("change");
    } 
    else if (scroll > 450){
        document.querySelector(".recruit_header").classList.add("change");
    }
    else {
        document.querySelector("header").classList.remove("change");
    }
});




// html内のクラスで定義したanimationTargetを配列で読み込む
const targetElement = document.querySelectorAll(".animationTarget");


// スクロールするたびに中のfor文を走らせる
document.addEventListener("scroll", function() 
{
    for(let i = 0; i < targetElement.length; i++)
    {
        // animationTargetと今見えているページの一番上までの距離 + アイテムの６割ほどの距離
        const getElementDistance = targetElement[i].getBoundingClientRect().top + targetElement[i].clientHeight * 0.05
        // console.log(getElementDistance)

        //画面の高さが特定のアイテムと画面の丈夫との距離より大きくなった時特定のアイテムが画面に移り始めたタイミングになる
        if(window.innerHeight > getElementDistance)
        {
            //show がクラスに追加表示される
            targetElement[i].classList.add('show');
        }
    }

})

