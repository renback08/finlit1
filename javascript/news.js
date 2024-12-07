document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = "9db598c0b21b4cfdb1010b07fbba6c60";
    const BASE_URL = "https://newsapi.org/v2/top-headlines";

    fetchBreakingNews();
    fetchTopHeadlines();
    fetchNewsSection("business", "#business .newsBox");
    fetchNewsSection("technology", "#technology .newsBox");
    fetchNewsSection("politics", "#politics .newsBox");

    async function fetchBreakingNews() {
        try {
            const url = `${BASE_URL}?country=us&apiKey=${API_KEY}`;
            const response = await fetch(url);

            if (!response.ok) throw new Error(`Error: ${response.status} - ${response.statusText}`);

            const data = await response.json();
            const breakingNewsArticle = data.articles[0];
            bindBreakingNews(breakingNewsArticle);
        } catch (error) {
            console.error("Error fetching breaking news:", error);
        }
    }

    async function fetchTopHeadlines() {
        try {
            const url = `${BASE_URL}?category=business&apiKey=${API_KEY}`;
            const response = await fetch(url);

            if (!response.ok) throw new Error(`Error: ${response.status} - ${response.statusText}`);

            const data = await response.json();
            const topBusinessArticles = data.articles.slice(0, 10);
            bindTopHeadlines(topBusinessArticles);
        } catch (error) {
            console.error("Error fetching top business headlines:", error);
        }
    }

    async function fetchNewsSection(category, containerSelector) {
        try {
            const url = `${BASE_URL}?category=${category}&apiKey=${API_KEY}`;
            const response = await fetch(url);

            if (!response.ok) throw new Error(`Error: ${response.status} - ${response.statusText}`);

            const data = await response.json();
            const limitedArticles = data.articles.slice(0, 4);
            bindSectionData(limitedArticles, containerSelector);
        } catch (error) {
            console.error(`Error fetching ${category} news:`, error);
        }
    }

    function bindBreakingNews(article) {
        // Handle main breaking news image
        const leftImg = document.querySelector('.left .img');
        if (leftImg) {
            if (article.urlToImage && isValidImageUrl(article.urlToImage)) {
                leftImg.style.backgroundImage = `url(${article.urlToImage})`;
            } else {
                leftImg.style.backgroundImage = 'url("../assets/default-news.jpg")';
            }
        }

        // Handle breaking news console
        const breakingNewsContainer = document.querySelector("#breakingNews");
        if (!breakingNewsContainer) {
            console.log("Breaking news container not found");
            return;
        }

        const imgDiv = breakingNewsContainer.querySelector('.img');
        const titleLink = breakingNewsContainer.querySelector('.title a');

        if (article.urlToImage && isValidImageUrl(article.urlToImage)) {
            imgDiv.style.backgroundImage = `url(${article.urlToImage})`;
        } else {
            imgDiv.style.backgroundImage = 'url("../assets/default-news.jpg")';
        }
        imgDiv.style.backgroundSize = 'cover';
        imgDiv.style.backgroundPosition = 'center';

        titleLink.textContent = article.title;
        titleLink.href = article.url;
        titleLink.target = "_blank";

        breakingNewsContainer.addEventListener('click', () => {
            window.open(article.url, '_blank');
        });

        if (article.description) {
            const description = document.createElement('p');
            description.textContent = article.description;
            description.style.padding = '0 15px 15px';
            description.style.color = '#666';
            breakingNewsContainer.appendChild(description);
        }

        const sourceAttribution = document.createElement('p');
        sourceAttribution.textContent = `Source: ${article.source.name}`;
        sourceAttribution.style.padding = '0 15px 15px';
        sourceAttribution.style.color = '#666';
        breakingNewsContainer.appendChild(sourceAttribution);

        const timestamp = document.createElement('p');
        const publishDate = new Date(article.publishedAt);
        timestamp.textContent = `Published: ${publishDate.toLocaleDateString()}`;
        timestamp.style.padding = '0 15px 15px';
        timestamp.style.color = '#666';
        breakingNewsContainer.appendChild(timestamp);
    }

    function bindTopHeadlines(articles) {
        const topHeadlinesContainer = document.querySelector(".right");
        if (!topHeadlinesContainer) {
            console.log("Top headlines container not found");
            return;
        }

        topHeadlinesContainer.innerHTML = "<h2>Top Headlines</h2>";

        articles.forEach(article => {
            const headlineItem = document.createElement('div');
            headlineItem.classList.add('headline-item');

            const titleLink = document.createElement('h3');
            const link = document.createElement('a');
            link.href = article.url;
            link.textContent = article.title;
            link.target = "_blank";
            titleLink.appendChild(link);

            const description = document.createElement('p');
            description.textContent = article.description || "No description available.";
            description.style.color = "#666";

            headlineItem.appendChild(titleLink);
            headlineItem.appendChild(description);

            headlineItem.addEventListener('click', () => {
                window.open(article.url, '_blank');
            });

            topHeadlinesContainer.appendChild(headlineItem);
        });
    }

    function bindSectionData(articles, containerSelector) {
        const newsBox = document.querySelector(containerSelector);
        if (!newsBox) {
            console.log(`${containerSelector} container not found`);
            return;
        }

        articles.forEach((article, index) => {
            const newsCard = newsBox.children[index];
            if (newsCard) {
                const imgDiv = newsCard.querySelector('.img');
                const titleLink = newsCard.querySelector('.title a');

                if (article.urlToImage && isValidImageUrl(article.urlToImage)) {
                    imgDiv.style.backgroundImage = `url(${article.urlToImage})`;
                } else {
                    imgDiv.style.backgroundImage = 'url("../assets/default-news.jpg")';
                }
                imgDiv.style.backgroundSize = 'cover';
                imgDiv.style.backgroundPosition = 'center';

                titleLink.textContent = article.title;
                titleLink.href = article.url;
                titleLink.target = "_blank";

                newsCard.addEventListener('click', () => {
                    window.open(article.url, '_blank');
                });

                if (article.description) {
                    const description = document.createElement('p');
                    description.textContent = article.description;
                    description.style.padding = '0 15px 15px';
                    description.style.color = '#666';
                    newsCard.appendChild(description);
                }
            }
        });
    }

    function isValidImageUrl(url) {
        if (!url) return false;
        return url.match(/\.(jpeg|jpg|gif|png)$/) != null || url.startsWith('https://');
    }
});
