const API_KEY = "6f83461279da4b1e89b110e0cc474ae9";
const url = "https://newsapi.org/v2/everything?q=";

let cardsContainerMain = document.getElementById("cards-container-main");
let newsCardTemplateMain = document.getElementById("template-news-card-main");

let cardsContainer_saved = document.getElementById("cards-container-saved");
let newsCardTemplate_saved = document.getElementById("template-news-card-saved");

let cardsContainer = document.getElementById("cards-container");
let newsCardTemplate = document.getElementById("template-news-card");

let cardsContainer2 = document.getElementById("cards-container-2");
let newsCardTemplate2 = document.getElementById("template-news-card-2");

let cardsContainerHealth = document.getElementById("cards-container-health");
let newsCardTemplateHealth = document.getElementById("template-news-card-health");

let cardsContainerEdu = document.getElementById("cards-container-edu");
let newsCardTemplateEdu = document.getElementById("template-news-card-edu");

let cardsContainerSports = document.getElementById("cards-container-sports");
let newsCardTemplateSports = document.getElementById("template-news-card-sports");

let trend_div = document.getElementById("trending-div");
let tech_div = document.getElementById("tech-div");
let slide_div = document.getElementById("slideshow-div");
let health_div = document.getElementById("health-div");
let edu_div = document.getElementById("edu-div");
let sports_div = document.getElementById("sports-div");

let slide_img1 = document.getElementById("slide-img1");
let slide_img2 = document.getElementById("slide-img2");
let slide_img3 = document.getElementById("slide-img3");

let caption1 = document.getElementById("caption1");
let caption2 = document.getElementById("caption2");
let caption3 = document.getElementById("caption3");

let comment_data = [];

window.addEventListener("load", () => {
     fetchNews("Trending India");
     loadComment();
}
);

function reload() {
    window.location.reload();
    document.documentElement.scrollTop = 0;
}

//load comment

function loadComment(){

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'load_comment.php', true);
    xhr.setRequestHeader('COntent-type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(xhr.status === 200){        
            
            comment_data = JSON.parse(xhr.responseText);

        }
        else{
            alert("Request failed. pls try again");
        }
    };
    xhr.send(); 

}

async function fetchNewsMain(query) {
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    slide_img1.src = data.articles[0].urlToImage;
    slide_img2.src = data.articles[1].urlToImage;
    slide_img3.src = data.articles[2].urlToImage;
        
    caption1.innerHTML = data.articles[0].title;
    caption2.innerHTML = data.articles[1].title;
    caption3.innerHTML = data.articles[2].title;

    bindDataMain(data.articles, query);
}

function bindDataMain(articles, query) {
    
    cardsContainerMain.innerHTML = "";

    articles.forEach((article, index) => {
        if (!article.urlToImage) return;
        const cardClone = newsCardTemplateMain.content.cloneNode(true);
        fillDataInCardMain(cardClone, article, index, query);
        cardsContainerMain.appendChild(cardClone);
    });
}

function fillDataInCardMain(cardClone, article, index, query) {
    let newsImgMain = cardClone.querySelector("#news-img-main");
    let newsTitleMain = cardClone.querySelector("#news-title-main");
    let newsSourceMain = cardClone.querySelector("#news-source-main");
    let newsDescMain = cardClone.querySelector("#news-desc-main");

    newsImgMain.src = article.urlToImage;
    newsTitleMain.innerHTML = article.title;
    newsDescMain.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSourceMain.innerHTML = `${article.source.name} · ${date}`;

    newsTitleMain.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDescMain.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-main");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, query));
    } else {
        console.error("Save button not found");
    }

}

//
async function fetchNews(query) {
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);
    
    slide_img1.src = data.articles[0].urlToImage;
    slide_img2.src = data.articles[1].urlToImage;
    slide_img3.src = data.articles[2].urlToImage;
        
    caption1.innerHTML = data.articles[0].title;
    caption2.innerHTML = data.articles[1].title;
    caption3.innerHTML = data.articles[2].title;

    bindData(data.articles);
}

function bindData(articles) {
    
    cardsContainer.innerHTML = "";

    articles.slice(0,3).forEach((article, index) => {
        if (!article.urlToImage) return;
        const cardClone = newsCardTemplate.content.cloneNode(true);
        fillDataInCard(cardClone, article, index);
        cardsContainer.appendChild(cardClone);
    });
}

function fillDataInCard(cardClone, article, index) {
    const newsImg = cardClone.querySelector("#news-img");
    const newsTitle = cardClone.querySelector("#news-title");
    const newsSource = cardClone.querySelector("#news-source");
    const newsDesc = cardClone.querySelector("#news-desc");

    newsImg.src = article.urlToImage;
    newsTitle.innerHTML = article.title;
    newsDesc.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource.innerHTML = `${article.source.name} · ${date}`;

    newsTitle.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-trend");
    const saveComment = cardClone.querySelector("#submit-comment");
    const commentTrend = cardClone.querySelector("#comment-trend");
    const shareTrend = cardClone.querySelector("#share-trend");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, "Trending India"));
    } else {
        console.error("Save button not found");
    } 
    if (saveComment) {
        saveComment.addEventListener("click", () => {
            save_comment(article.url, "Trending India", commentTrend.value);
            commentTrend.value = "";
        });
    } else {
        console.error("Save button not found");
    } 



    const comment_sec = cardClone.querySelector("#comment-sec");
    for (var i in comment_data) {
        if (comment_data[i].news_id === article.url) {
            comment_sec.innerHTML += "<span style='color: blue;'>" + comment_data[i].Username + " - " + comment_data[i].comment + "</span><br>";
        }
    }
    

    //share

    shareTrend.addEventListener("click", async () => {
        try {
            await navigator.share({
                title: article.title,
                text: article.title,
                url: article.url
            });
            console.log('Content shared successfully');
        } catch (error) {
            console.error('Error sharing content:', error);
        }
    });

    
}


function handleNavItemClick(id) {
    // Store the clicked item ID in sessionStorage
    sessionStorage.setItem('selectedNavItem', id);
    // Call reload function
    onReload();
}

function onReload() {
    // Reload the page
    window.location.reload();
}

//nav items
let curSelectedNav = null;
function onNavItemClick(id) {
    fetchNewsMain(id);
    trend_div.style.display = "none";
    tech_div.style.display = "none";
    health_div.style.display = "none";
    edu_div.style.display = "none";
    sports_div.style.display = "none";
    cardsContainer_saved.style.display = "none";

    if(cardsContainerMain.style.display === "none"){
        cardsContainerMain.style.display = "flex";
    }
    cardsContainerMain.style.marginTop = "100px";

    cardsContainerMain.innerHTML = "";

    const navItem = document.getElementById(id);
    curSelectedNav?.classList.remove("active");
    curSelectedNav = navItem;
    curSelectedNav.classList.add("active");
}

const searchButton = document.getElementById("search-button");
const searchText = document.getElementById("search-text");

searchButton.addEventListener("click", () => {
    const query = searchText.value;
    if (!query) return;

    fetchNewsMain(query);
    trend_div.style.display = "none";
    tech_div.style.display = "none";
    slide_div.style.display = "none";
    health_div.style.display = "none";
    edu_div.style.display = "none";
    sports_div.style.display = "none";
    cardsContainer_saved.style.display = "none";

    cardsContainerMain.style.marginTop = "100px";

    curSelectedNav?.classList.remove("active");
    curSelectedNav = null;
});

//slideshow
let slideIndex = 0;
window.addEventListener("load",() => showSlides());

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 2000); // Change image every 2 seconds
}


async function fetchNews_2(query) {
    const res2 = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data2 = await res2.json();
    cardsContainer.innerHTML = "";
    data2.articles.forEach((article , index) => {
        if (!article.urlToImage) return;
        const cardClone = newsCardTemplate.content.cloneNode(true);
        fillDataInCard(cardClone, article, index);
        cardsContainer.appendChild(cardClone);
    });             
}

function toggleTrendingButton() {
    const button = document.getElementById("trending-button");
    const span = button.querySelector("#t-span");
    button.classList.toggle("back-arrow");
    if (span.textContent === "Trending India") {        
        fetchNews_2("Trending India");
        span.textContent = "Back";
        tech_div.style.display = "none";
        health_div.style.display = "none";
        edu_div.style.display = "none";
        sports_div.style.display = "none";
        cardsContainer_saved.style.display = "none";

    } else {
        fetchNews("Trending India");
        span.textContent = "Trending India";
        tech_div.style.display = "block";
        health_div.style.display = "block";
        edu_div.style.display = "block";
        sports_div.style.display = "block";

    }
}

window.addEventListener("load", () => fetchNews_tech("Technology"));

async function fetchNews_tech(query) {          
    const res3 = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data3 = await res3.json();
    console.log(data3);

    bindData_tech(data3.articles);
}

function bindData_tech(articles) {
    
    cardsContainer2.innerHTML = "";

    articles.slice(0,3).forEach((article, index) => {
        if (!article.urlToImage) 
            return;
        const cardClone = newsCardTemplate2.content.cloneNode(true);
        fillDataInCard_tech(cardClone, article, index);
        cardsContainer2.appendChild(cardClone);
    });
}

function fillDataInCard_tech(cardClone, article, index) {
    let newsImg2 = cardClone.querySelector("#news-img-2");
    let newsTitle2 = cardClone.querySelector("#news-title-2");
    let newsSource2 = cardClone.querySelector("#news-source-2");
    let newsDesc2 = cardClone.querySelector("#news-desc-2");

    newsImg2.src = article.urlToImage;
    newsTitle2.innerHTML = article.title;
    newsDesc2.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource2.innerHTML = `${article.source.name} · ${date}`;

    newsTitle2.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc2.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-tech");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, "Technology"));
    } else {
        console.error("Save button not found");
    }  
}

async function fetchNews_tech_2(query) {
    const res2 = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data2 = await res2.json();
    cardsContainer2.innerHTML = "";
    data2.articles.forEach((article, index) => {
        if (!article.urlToImage) return;
        const cardClone = newsCardTemplate2.content.cloneNode(true);
        fillDataInCard_tech(cardClone, article, index);
        cardsContainer2.appendChild(cardClone);
    });             
}

function toggleTechButton() {
    
    const button = document.getElementById("tech-button");
    const span = button.querySelector("#tech-span");
    button.classList.toggle("tech-back");
    if (span.textContent === "Technology") {        
        fetchNews_tech_2("Technology");
        span.textContent = "Back";
        trend_div.style.display = "none";
        health_div.style.display = "none";
        edu_div.style.display = "none";
        sports_div.style.display = "none";


    } else {
        fetchNews_tech("Technology");
        span.textContent = "Technology";
        trend_div.style.display = "block";
        health_div.style.display = "block";
        edu_div.style.display = "block";
        sports_div.style.display = "block";
    }
}

//home
function showHome(){
    trend_div.style.display = "block";
    tech_div.style.display = "block";
    health_div.style.display = "block";
    edu_div.style.display = "block";
    sports_div.style.display = "block";
    slide_div.style.display = "block";

    cardsContainer_saved.style.display = "none";
    cardsContainerMain.style.display = "none";
}


//health
window.addEventListener("load", () => fetchNews_health("Health",3));

async function fetchNews_health(query,n) {          
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    bindData_health(data.articles,n);
}

function bindData_health(articles,n) {
    
    cardsContainerHealth.innerHTML = "";

    articles.slice(0,n).forEach((article, index) => {
        if (!article.urlToImage) 
            return;
        const cardClone = newsCardTemplateHealth.content.cloneNode(true);
        fillDataInCard_health(cardClone, article, index);
        cardsContainerHealth.appendChild(cardClone);
    });
}

function fillDataInCard_health(cardClone, article, index) {
    const newsImg = cardClone.querySelector("#news-img-health");
    const newsTitle = cardClone.querySelector("#news-title-health");
    const newsSource = cardClone.querySelector("#news-source-health");
    const newsDesc = cardClone.querySelector("#news-desc-health");

    newsImg.src = article.urlToImage;
    newsTitle.innerHTML = article.title;
    newsDesc.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource.innerHTML = `${article.source.name} · ${date}`;

    newsTitle.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-health");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, "Health"));
    } else {
        console.error("Save button not found");
    }  
}

function toggleHealthButton() {
    
    const button = document.getElementById("health-button");
    const span = button.querySelector("#health-span");
    button.classList.toggle("health-back");
    if (span.textContent === "Health") {        
        fetchNews_health("Health",101);
        span.textContent = "Back";
        trend_div.style.display = "none";
        tech_div.style.display = "none";
        edu_div.style.display = "none";
        sports_div.style.display = "none";        
    } else {
        fetchNews_health("Health",3);
        span.textContent = "Health";
        trend_div.style.display = "block";
        tech_div.style.display = "block";
        edu_div.style.display = "block";
        sports_div.style.display = "block";        
    }
}

//Education

let n_edu = 3;


function toggleEduButton() {
    
    const button = document.getElementById("edu-button");
    const span = button.querySelector("#edu-span");
    button.classList.toggle("edu-back");
    if (span.textContent === "Education") {        
        fetchEdu("Education",101);
        n_edu = 101;
        span.textContent = "Back";
        trend_div.style.display = "none";
        tech_div.style.display = "none";
        health_div.style.display = "none";        
        sports_div.style.display = "none";        
    } else {
        fetchEdu("Education",3);
        n_edu = 3;
        span.textContent = "Education";
        trend_div.style.display = "block";
        tech_div.style.display = "block";
        health_div.style.display = "block";
        sports_div.style.display = "block";        
    }
}

//web socket

var conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function(e) {
            console.log("Connection established!");
            conn.send('fetch_news');

        };

        conn.onmessage = function(e) {
            var news = JSON.parse(e.data);
            console.log("web socket");
            console.log(news);
            bindData_Edu(news.articles, n_edu);
        };

function fetchEdu(){
    conn.send('fetch_news');
}

//window.addEventListener("load", () => fetchNews_Edu("Education",3));

async function fetchNews_Edu(query,n) {          
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    //bindData_Edu(data.articles,n);
}

function bindData_Edu(articles,n) {
    
    cardsContainerEdu.innerHTML = "";

    articles.slice(0,n).forEach((article, index) => {
        if (!article.urlToImage) 
            return;
        const cardClone = newsCardTemplateEdu.content.cloneNode(true);
        fillDataInCard_Edu(cardClone, article, index);
        cardsContainerEdu.appendChild(cardClone);
    });
}

function fillDataInCard_Edu(cardClone, article, index) {
    const newsImg = cardClone.querySelector("#news-img-edu");
    const newsTitle = cardClone.querySelector("#news-title-edu");
    const newsSource = cardClone.querySelector("#news-source-edu");
    const newsDesc = cardClone.querySelector("#news-desc-edu");

    newsImg.src = article.urlToImage;
    newsTitle.innerHTML = article.title;
    newsDesc.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource.innerHTML = `${article.source.name} · ${date}`;

    newsTitle.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-edu");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, "Education"));
    } else {
        console.error("Save button not found");
    }  
}


//sports

window.addEventListener("load", () => fetchNews_Sports("Sports",4));

async function fetchNews_Sports(query,n) {          
    try {
        const res = await fetch(`fetch_api_1.php?query=${query}`);
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        const data = await res.json();
        console.log(data); 

        if (data && data.articles) {
            bindData_Sports(data.articles, n);
        } else {
            console.error('No articles found in the response');
        }
    } catch (error) {
        console.error('Error fetching sports news:', error);
    }
}

function bindData_Sports(articles,n) {
    
    cardsContainerSports.innerHTML = "";

    articles.slice(0,n).forEach((article, index) => {
        if (!article.urlToImage) 
            return;
        const cardClone = newsCardTemplateSports.content.cloneNode(true);
        fillDataInCard_Sports(cardClone, article, index);
        cardsContainerSports.appendChild(cardClone);
    });
}

function fillDataInCard_Sports(cardClone, article, index) {
    const newsImg = cardClone.querySelector("#news-img-sports");
    const newsTitle = cardClone.querySelector("#news-title-sports");
    const newsSource = cardClone.querySelector("#news-source-sports");
    const newsDesc = cardClone.querySelector("#news-desc-sports");

    newsImg.src = article.urlToImage;
    newsTitle.innerHTML = article.title;
    newsDesc.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource.innerHTML = `${article.source.name} · ${date}`;

    newsTitle.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const saveButton = cardClone.querySelector("#save-sports");
    if (saveButton) {
        saveButton.addEventListener("click", () => save_trend(article.url, "Sports"));
    } else {
        console.error("Save button not found");
    }  
}

function toggleSportsButton() {
    
    const button = document.getElementById("sports-button");
    const span = button.querySelector("#sports-span");
    button.classList.toggle("sports-back");
    if (span.textContent === "Sports") {        
        fetchNews_Sports("Sports",101);
        span.textContent = "Back";
        trend_div.style.display = "none";
        tech_div.style.display = "none";
        health_div.style.display = "none"; 
        edu_div.style.display = "none";       
    } else {
        fetchNews_Sports("Sports",4);
        span.textContent = "Sports";
        trend_div.style.display = "block";
        tech_div.style.display = "block";
        health_div.style.display = "block";
        edu_div.style.display = "block";
    }
}

//blog card 1

window.addEventListener("load", () => fetchNews_Top1("Top"));

async function fetchNews_Top1(query) {          
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    bindData_Top1(data.articles);
}

function bindData_Top1(articles) {

    articles.slice(1,2).forEach((article) => {
        if (!article.urlToImage) 
            return;

        const newsImg = document.getElementById("blog-img");
        const newsTitle = document.getElementById("blog-title");
        const newsSource = document.getElementById("blog-source");
        const newsDesc = document.getElementById("blog-desc");
        const newsDate = document.getElementById("blog-date");
        const readMore = document.getElementById("read-more");

        newsImg.style.backgroundImage = `url(${article.urlToImage})`;
        newsTitle.innerHTML = article.title;
        const descriptionWords = article.description.split(" ");
        const truncatedDescription = descriptionWords.slice(0, 10).join(" ");
        newsDesc.innerHTML = truncatedDescription + "...";

        const date = new Date(article.publishedAt).toLocaleString("en-US", {
            
            timeZone: "Asia/Jakarta",
        });

        newsSource.innerHTML = `${article.source.name}`;
        newsDate.innerHTML = `${date}`;    
        
        readMore.addEventListener("click", () => {
            window.open(article.url, "_blank");
        });

    });
}

//blog card 2

window.addEventListener("load", () => fetchNews_Top2("International"));

async function fetchNews_Top2(query) {          
    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    bindData_Top2(data.articles);
}

function bindData_Top2(articles) {

    articles.slice(0,1).forEach((article) => {
        if (!article.urlToImage) 
            return;

        const newsImg = document.getElementById("blog-img-2");
        const newsTitle = document.getElementById("blog-title-2");
        const newsSource = document.getElementById("blog-source-2");
        const newsDesc = document.getElementById("blog-desc-2");
        const newsDate = document.getElementById("blog-date-2");
        const readMore = document.getElementById("read-more-2");

        newsImg.style.backgroundImage = `url(${article.urlToImage})`;
        newsTitle.innerHTML = article.title;

        const descriptionWords = article.description.split(" ");
        const truncatedDescription = descriptionWords.slice(0, 10).join(" ");
        newsDesc.innerHTML = truncatedDescription + "...";

        const date = new Date(article.publishedAt).toLocaleString("en-US", {
            timeZone: "Asia/Jakarta",
        });

        newsSource.innerHTML = `${article.source.name}`;
        newsDate.innerHTML = `${date}`;    
        
        readMore.addEventListener("click", () => {
            window.open(article.url, "_blank");
        });

    });
}



document.getElementById('toggle-footer').addEventListener('click', function() {
    this.classList.toggle('wrong-icon');
    const footer = document.querySelector('footer');
    const bodyContent = document.querySelector('.body-content'); // Assuming .body-content wraps the content

    if (footer.classList.contains('fullscreen')) {
        footer.classList.remove('fullscreen');
        bodyContent.style.display = 'block';
    } else {
        footer.classList.add('fullscreen');
        bodyContent.style.display = 'none';
    }
});




function toggleDots(element){
    const actions = element.nextElementSibling;
    actions.classList.toggle('show');
}
  
function actionSave() {
    // Your save logic here
    alert('Saved for later');
}
  
function actionShare() {
    // Your share logic here
    alert('Shared');
}
  
function actionGoToLink() {
    // Your go to link logic here
    alert('Go to link');
}


//profile click

let profile_pic = document.getElementById("profile-pic");
profile_pic.addEventListener("click", function(){
    let profile_content = document.getElementById("dropdown-content");
    profile_content.classList.toggle('showProfile');
});

//save news

function save_trend(index, title){

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'saved_news.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(xhr.status === 200){
            alert(xhr.responseText);
        }
        else{
            alert("Request failed. pls try again");
        }
    };
    xhr.send('index='+ encodeURIComponent(index) + '&title=' + encodeURIComponent(title));
    
}

//saved news

function saved_news(){

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'saved_news.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(xhr.status === 200){        
            
            const data = JSON.parse(xhr.responseText);

            for(var i in data){
                fetch_saved(data[i].news_id, data[i].news_category, data[i].saved_id);
            }

        }
        else{
            alert("Request failed. pls try again");
        }
    };
    xhr.send();   
    
    trend_div.style.display = "none";
    tech_div.style.display = "none";
    health_div.style.display = "none";
    edu_div.style.display = "none";
    sports_div.style.display = "none";
    cardsContainerMain.style.display = "none";

    if(cardsContainer_saved.style.display === "none"){
        cardsContainer_saved.style.display = "flex";
    }
    cardsContainer_saved.style.marginTop = "100px";
    
    cardsContainer_saved.innerHTML = "";

    document.documentElement.scrollTop = 0;
    
}


async function fetch_saved(id, query, saved_id) {

    const res = await fetch(`${url}${query}&apiKey=${API_KEY}`);
    const data = await res.json();
    console.log(data);

    bindData_saved(data.articles, id, saved_id);
}

function bindData_saved(articles, id, saved_id) {    

    const article = articles.find((article) => article.url === id);
    
    if(article){
        console.log(article.url);
        
        const cardClone = newsCardTemplate_saved.content.cloneNode(true);
        fillDataInCard_saved(cardClone, article, saved_id);

        cardsContainer_saved.appendChild(cardClone);
    }
    else {
        console.error('Article not found for URL:', id);
    }
}

function fillDataInCard_saved(cardClone, article, saved_id) {
    const newsImg_saved = cardClone.querySelector("#news-img-saved");
    const newsTitle_saved = cardClone.querySelector("#news-title-saved");
    const newsSource_saved = cardClone.querySelector("#news-source-saved");
    const newsDesc_saved = cardClone.querySelector("#news-desc-saved");

    newsImg_saved.src = article.urlToImage;
    newsTitle_saved.innerHTML = article.title;
    newsDesc_saved.innerHTML = article.description;

    const date = new Date(article.publishedAt).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    newsSource_saved.innerHTML = `${article.source.name} · ${date}`;

    newsTitle_saved.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });
    newsDesc_saved.addEventListener("click", () => {
        window.open(article.url, "_blank");
    });

    const removeButton = cardClone.querySelector("#remove-saved");
    if (removeButton) {
        removeButton.addEventListener("click", () => remove_saved(saved_id));
    } else {
        console.error("Save button not found");
    }

}

//remove saved


function remove_saved(saved_id){

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'remove_saved.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(xhr.status === 200){
            alert(xhr.responseText);
        }
        else{
            alert("Request failed. pls try again");
        }
    };
    xhr.send('saved_id='+ encodeURIComponent(saved_id));
    
}


//save comment

function save_comment(url, title, comment){

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_comment.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(xhr.status === 200){
            alert(xhr.responseText);
        }
        else{
            alert("Request failed. pls try again");
        }
    };
    xhr.send('url='+ encodeURIComponent(url) + '&title=' + encodeURIComponent(title) + '&comment=' + encodeURIComponent(comment));
    loadComment();
    reload();

}
