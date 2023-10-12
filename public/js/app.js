function generateCommentsList(comments)
{
    let commentsList = document.createElement('ul')
    commentsList.innerHTML = `
                                <button class="remove-style-btn" id="closeModale"><i class="fa-solid fa-xmark"></i></button>
                            `

    for(let comment of comments)
    {
        const commentLi = document.createElement('li')
        commentLi.classList.add('comment-box')

        commentLi.innerHTML = `
                                <h3>${comment.user.username}</h3>
                                <p>${ comment.content }</p>
                            `

        commentsList.append(commentLi);
    }

    return commentsList;
}


function displayModal(e, commentsModale) {
    fetch(`/comments/get/${ e.target.dataset.post }`, {
        method: 'GET'
    })
        .then(response => response.json())
        .then(comments => {
            commentsModale.innerHTML = '';

            const commentsList = generateCommentsList(comments);

            commentsModale.append(commentsList)

            // if (commentsModale.classList.contains('show')) {
            //     commentsModale.classList.remove('show');
            // } else {
            //     commentsModale.classList.add('show');
            // }

            commentsModale.classList.add('show');

            const closeModaleButton = document.querySelector("#closeModale")
            closeModaleButton.addEventListener('click', () => {
                commentsModale.classList.remove('show');
            })


        })



}

    document.addEventListener('DOMContentLoaded', () => {


        const showCommentButtons = document.querySelectorAll("#show-comment-button");
        const commentsModale = document.querySelector("#commentModale")

        for (let b = 0; b < showCommentButtons.length; b++) {
            showCommentButtons[b].addEventListener('click', function(e) {
                displayModal(e, commentsModale)
            });
        }


    })
