let twitterChart; // Global variable to store the chart instance

async function fetchTwitterData() {
    try {
        let response = await fetch("http://localhost:8080/social_media_analytics/get_posts.php");
        let data = await response.json();

        console.log("Fetched Data:", data); // Debugging

        if (data.length > 0) {
            displayTweets(data); // Show recent tweets
            generateChart(data); // Generate analytics chart
        } else {
            console.warn("No tweet data available.");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

function generateChart(tweets) {
    const ctx = document.getElementById("twitterChart").getContext("2d");

    let labels = tweets.map(tweet => new Date(tweet.created_at).toLocaleDateString());
    let likes = tweets.map(tweet => tweet.likes);
    let comments = tweets.map(tweet => tweet.comments);
    let shares = tweets.map(tweet => tweet.shares);

    if (twitterChart) {
        twitterChart.destroy();
    }

    if (likes.length === 0 || comments.length === 0 || shares.length === 0) {
        console.warn("No valid data for the chart.");
        return;
    }

    twitterChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels.slice(0, 10),
            datasets: [
                {
                    label: "Likes",
                    data: likes.slice(0, 10),
                    backgroundColor: "rgba(255, 99, 132, 0.6)",
                },
                {
                    label: "Comments",
                    data: comments.slice(0, 10),
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                },
                {
                    label: "Shares",
                    data: shares.slice(0, 10),
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: { display: true, text: "Date" }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: "Engagement" }
                }
            }
        }
    });
}

function displayTweets(tweets) {
    let tweetList = document.getElementById('tweetList');
    tweetList.innerHTML = "";

    tweets.forEach(tweet => {
        let li = document.createElement('li');
        li.classList.add("bg-gray-700", "p-4", "rounded-lg", "shadow-md");
        li.innerHTML = `
            <h3 class="font-bold">${tweet.username || "Unknown User"} <span class="text-gray-400">@${tweet.platform}</span></h3>
            <p class="mt-2">${tweet.content}</p>
            <div class="text-gray-300 mt-2 text-sm">
                ❤️ ${tweet.likes} | 💬 ${tweet.comments} | 🔁 ${tweet.shares}
            </div>
            <small class="text-gray-500">${new Date(tweet.created_at).toLocaleString()}</small>
        `;
        tweetList.appendChild(li);
    });
}

document.addEventListener("DOMContentLoaded", fetchTwitterData);