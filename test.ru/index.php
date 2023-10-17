<?php
    require_once 'header.php';
?>
    <a href="#" class="btn btn-primary new">New Post</a>
    <form style="display: none" method="post" class="form form-new">
        <div class="mb-3">
            <label for="e1" class="form-label">postTitle</label>
            <input name="title" type="text" class="form-control" id="e1">
        </div>
        <div class="mb-3">
            <label for="e2" class="form-label">postBody</label>
            <input name="body" type="text" class="form-control" id="e2">
        </div>
        <div class="mb-3">
            <label for="e3" class="form-label">userId</label>
            <input name="userId" type="text" class="form-control" id="e3">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div  style="gap:10px" class="post-list d-flex flex-wrap">
    </div>
</div>
</body>
</html>

<script>
    const postList = document.querySelector('.post-list')

    async function getPosts() {
       const res = await fetch('http://api.test.ru/posts/');
        res.json().then(res => renderPost(res));
    }
    function renderPost(posts) {
        let html = '';
        posts.forEach((post) => {
            html += `
                <div class="card" style="width: 18rem;">
                  <div class="card-body">
                    <h5 class="card-title">${post.title}</h5>
                    <p class="card-text">${post.body}</p>
                    <a href="posts/${post.id}" class="card-link">Card link</a>
                  </div>
                </div>
            `
        })
        postList.innerHTML = html;
    }

    document.querySelector('.new').addEventListener('click', () => {
        document.querySelector('.form-new').style.display='block';
    })

    document.querySelector('.form-new').addEventListener('submit', createPost);

    async function createPost(event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append('title', event.target[0].value);
        formData.append('body', event.target[1].value);
        formData.append('userId', event.target[2].value);
        const res = await fetch(`http://api.test.ru/posts/`, {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        if(data['error']) {
            alert(data['error']);
        }else {
            postList.innerHTML += `
                 <div class="card" style="width: 18rem;">
                  <div class="card-body">
                    <h5 class="card-title">${data.title}</h5>
                    <p class="card-text">${data.body}</p>
                    <a href="posts/${data.id}" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                  </div>
                </div>
            `
        }
    }

    getPosts();
</script>