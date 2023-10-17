<?php
    require_once 'header.php';
?>

    <div class="post">

    </div>

    <form style="display: none" method="put" class="form form-change">
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
        <input name="id" type="hidden" class="form-control" id="e4">

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    const postBody = document.querySelector('.post')
    let url = new URL(window.location.href);
    const id = url.pathname.split('/')[2];
    const data = {};
    async function getPost() {
        if(!isNaN(id)) {
            const res = await fetch(`http://api.test.ru/posts/${id}/`);
            res.json().then(res => {
                if(res['error']) {
                    alert(res['error']);
                    window.location.href = "http://test.ru/"

                }else {
                    renderPost(res)
                }
            });
        } else {
            alert('Id empty')
        }
    }
    function renderPost(post) {

       let html = `
            <div class="card">
              <h5 class="card-header">${post.title}</h5>
              <div class="card-body">
                <h5 class="card-title">${post.body}</h5>
                <a href="#" class="btn btn-primary delete" data-post="${post.id}">Delete</a>
                <a href="#" class="btn btn-primary change">Change</a>
              </div>
            </div>
        `
        document.querySelector('#e1').value = post.title;
        document.querySelector('#e2').value = post.body;
        document.querySelector('#e3').value = post.userId;
        document.querySelector('#e4').value = post.id;


        postBody.innerHTML = html;


            document.querySelector('.delete').addEventListener('click', deletePost);
            document.querySelector('.change').addEventListener('click', () => {
                document.querySelector('.form.form-change').style.display = 'block';
                data.title = post.title;
                data.body = post.body;
                data.userId = post.userId;
            });
    }

    async function deletePost(event) {
        const id = event.target.dataset.post;
        const res = await fetch('http://api.test.ru/posts/2/', {
            method: "DELETE"
        });
        if(res.status === 200) {
            alert('Delete success');
            window.location.href = "http://test.ru/"
        }else {
            alert('Delete error');
        }
    }

    document.querySelector('.form-change').addEventListener('submit', changePost);

    async function changePost(event) {
        event.preventDefault();

        const dataForm = {
            title:event.target[0].value,
            body:event.target[1].value,
            userId:event.target[2].value,
            id:event.target[3].value,
        }
        const res = await fetch(`http://api.test.ru/posts/${id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataForm)
        });
    }

    getPost();
</script>
</body>

</html>