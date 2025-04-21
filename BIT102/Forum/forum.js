document.addEventListener("DOMContentLoaded", () => {
    const submitBtn = document.querySelector(".submit-button");
    const textarea = document.querySelector("textarea");
    const commentCount = document.getElementById("comment-count");

    // Load saved comments from backend
    loadComments();

    submitBtn.addEventListener("click", () => {
        const content = textarea.value.trim();
        if (!content) return alert("Please enter something before submitting!");

        const username = prompt("Enter your name:");
        if (!username) return;

        const title = prompt("Enter a title for your experience:");
        if (!title) return;

        const comment = {
            name: username,
            title: title,
            content: content,
            date: new Date().toLocaleDateString(),
            time: new Date().toLocaleTimeString()
        };

        // Save to backend
        fetch("save_comment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(comment)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Add the returned ID to the comment for later editing
                comment.id = result.id;
                addCommentToPage(comment);
                textarea.value = "";
            } else {
                alert("Failed to save comment.");
            }
        })
        .catch(error => {
            console.error("Failed to save comment:", error);
            alert("There was a problem saving your comment.");
        });
    });

    function loadComments() {
        fetch("get_comments.php")
            .then(response => response.json())
            .then(comments => {
                commentCount.textContent = `${comments.length + 4} Comments`; // +4 for static ones
                comments.forEach(comment => addCommentToPage(comment, true));
            })
            .catch(error => {
                console.error("Failed to load comments:", error);
                alert("Error loading comments.");
            });
    }

    function addCommentToPage(comment, isInitialLoad = false) {
        const post = document.createElement("div");
        post.className = "post";
        post.innerHTML = `
            <img src="profile_pic/user-profile-default-image.png" alt="User Avatar">
            <div class="post-content">
                <h4>${comment.name}</h4>
                <strong>${comment.title}</strong>
                <p>${comment.content}</p>
                <div class="post-footer">${comment.date} ${comment.time}</div>
                <div class="comment-buttons">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
            </div>
        `;

        // Edit with backend update
        const editBtn = post.querySelector(".edit-btn");
        editBtn.addEventListener("click", () => {
            const newText = prompt("Edit your comment:", comment.content);
            if (newText !== null && newText.trim() !== "") {
                comment.content = newText;
                post.querySelector("p").textContent = newText;

                fetch("edit_comment.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: comment.id,
                        content: newText
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (!result.success) {
                        alert("Failed to update comment on server.");
                    }
                })
                .catch(error => {
                    console.error("Edit failed:", error);
                    alert("Server error while updating comment.");
                });
            }
        });

        const deleteBtn = post.querySelector(".delete-btn");
        deleteBtn.addEventListener("click", () => {
            if (confirm("Are you sure you want to delete this comment?")) {
                fetch("delete_comment.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: comment.id })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        post.remove();
                        updateCommentCount(-1);
                    } else {
                        alert("Failed to delete comment from server.");
                    }
                })
                .catch(error => {
                    console.error("Delete failed:", error);
                    alert("Server error while deleting comment.");
                });
            }
        });
        

        // Add new comment after the header
        commentCount.insertAdjacentElement("afterend", post);

        if (!isInitialLoad) updateCommentCount(1);
    }

    function updateCommentCount(change) {
        let current = parseInt(commentCount.textContent);
        if (isNaN(current)) current = 0;
        const newCount = current + change;
        commentCount.textContent = `${newCount} Comments`;
    }
});
